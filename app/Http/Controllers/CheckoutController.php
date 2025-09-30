<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Checkout;
use App\Models\Empresa\ServicioEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\CheckoutDetalle;
use App\Mail\PedidoConfirmado;
use App\Mail\NotificacionGeneral;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index($id, Request $request)
    {
        $empresa = Negocio::findOrFail($id);

        $productos = Producto::where('negocio_id', $id)
            ->where('estado_publicado', 1)
            ->get();

        $servicios = ServicioEmpresa::where('negocio_id', $id)->get();

        $carritoRequest = $request->input('carrito');

        if ($carritoRequest) {
            $carrito = json_decode($carritoRequest, true);

            foreach ($carrito as &$item) {
                $item['cantidad'] = $item['cantidad'] ?? 1;

                if (isset($item['precio']) && !isset($item['precio_unitario'])) {
                    $item['precio_unitario'] = $item['precio'];
                } elseif (!isset($item['precio']) && isset($item['precio_unitario'])) {
                    $item['precio'] = $item['precio_unitario'];
                } elseif (!isset($item['precio']) && !isset($item['precio_unitario'])) {
                    $item['precio'] = 0;
                    $item['precio_unitario'] = 0;
                }
            }

            Session::put('carrito', $carrito);
        } else {
            $carrito = Session::get('carrito', []);
        }

        return view('checkout.index', compact('empresa', 'productos', 'servicios', 'carrito'));
    }

    public function add(Request $request, $empresaId)
    {
        $carrito = Session::get('carrito', []);

        $id = $request->input('producto_id') ?? $request->input('servicio_id');
        $tipo = $request->has('producto_id') ? 'producto' : 'servicio';
        $cantidad = (int) $request->input('cantidad', 1);

        $indiceExistente = collect($carrito)->search(function ($item) use ($id, $tipo) {
            return $item['id'] == $id && $item['tipo'] == $tipo;
        });

        if ($indiceExistente !== false) {
            $carrito[$indiceExistente]['cantidad'] += $cantidad;
        } else {
            if ($tipo === 'producto') {
                $producto = Producto::findOrFail($id);
                $carrito[] = [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'precio_unitario' => $producto->precio_venta,
                    'tipo' => 'producto',
                    'cantidad' => $cantidad,
                ];
            } else {
                $servicio = ServicioEmpresa::findOrFail($id);
                $carrito[] = [
                    'id' => $servicio->id,
                    'nombre' => $servicio->nombre,
                    'precio_unitario' => $servicio->precio,
                    'tipo' => 'servicio',
                    'cantidad' => $cantidad,
                ];
            }
        }

        Session::put('carrito', $carrito);
        return back()->with('success', 'Agregado al carrito.');
    }

// 1) Mostrar modal (sin guardar en BD)
    public function finalizar(Request $request, $negocioId)
    {
        // Carrito desde JSON o sesión
        $items = [];
        if ($request->filled('carrito')) {
            try {
                $items = json_decode($request->input('carrito'), true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $e) {
                Log::error('Error JSON carrito: '.$e->getMessage());
            }
        }
        if (empty($items)) {
            $items = Session::get('carrito', []); // por si ya lo tenías en sesión
        }
        if (empty($items)) {
            return response()->json(['ok'=>false,'errors'=>['general'=>['El carrito está vacío.']]], 422);
        }

        // Calcula total (precio_total = precio_unitario * cantidad)
        $total = 0;
        foreach ($items as $i => $it) {
            $pu   = (float)($it['precio_unitario'] ?? $it['precio'] ?? null);
            $cant = (int)  ($it['cantidad'] ?? 0);
            if ($pu <= 0 || $cant <= 0) {
                return response()->json(['ok'=>false,'errors'=>['general'=>["Ítem #$i inválido."]]], 422);
            }
            $total += $pu * $cant;
        }

        // Guarda en sesión para usar en confirmar()
        Session::put('checkout_preview', [
            'negocio_id' => $negocioId,
            'items'      => $items,
            'total'      => $total, // solo referencia; el total real se recalcula luego
        ]);

        // Renderiza modal con items + total (sin persistir)
        $html = view('checkout.partials._modal_checkout', [
            'items' => $items,
            'total' => $total,
            'negocioId' => $negocioId,
        ])->render();

        return response()->json(['ok'=>true,'html'=>$html]);
    }

    // 2) Finalizar (aquí recién se crea en BD)
    public function confirmar(Request $request, $negocioId)
    {
        try {
            $request->validate([
                'nombre'    => 'required|string',
                'email'     => 'required|email',
                'telefono'  => 'required|string',
                'direccion' => 'required|string',
            ]);

            $preview = Session::get('checkout_preview');
            if (!$preview || ($preview['negocio_id'] ?? null) != $negocioId) {
                return response()->json(['ok'=>false,'errors'=>['general'=>['La sesión de checkout expiró.']]], 422);
            }

            $items = $preview['items'] ?? [];
            if (empty($items)) {
                return response()->json(['ok'=>false,'errors'=>['general'=>['El carrito está vacío.']]], 422);
            }

            // Crea pedido SIN campo total (lo calculamos siempre desde detalles)
            $pedido = \App\Models\Checkout::create([
                'negocio_id'  => $negocioId,
                'user_id'     => auth()->id(),
                'metodo_pago' => 'pendiente',
                'estado_pago' => 'pendiente',
                'nombre'      => $request->nombre,
                'email'       => $request->email,
                'telefono'    => $request->telefono,
                'direccion'   => $request->direccion,
            ]);

            // Insertar detalles: precio_unitario original y precio_total = unit * cantidad
            foreach ($items as $it) {
                $tipo = $it['tipo'] ?? null;
                $idRef = $it['id'] ?? null;
                $cant = (int)($it['cantidad'] ?? 0);

                // 🔒 Recomendado: recuperar precio real desde BD
                if ($tipo === 'producto') {
                    $p = \App\Models\Producto::findOrFail($idRef);
                    $pu = (float)$p->precio;
                    $productoId = $p->id;
                    $servicioId = null;
                } elseif ($tipo === 'servicio') {
                    $s = \App\Models\Empresa\ServicioEmpresa::findOrFail($idRef);
                    $pu = (float)$s->precio;
                    $productoId = null;
                    $servicioId = $s->id;
                } else {
                    return response()->json(['ok'=>false,'errors'=>['general'=>['Tipo de ítem inválido.']]], 422);
                }

                \App\Models\CheckoutDetalle::create([
                    'checkout_id'     => $pedido->id,
                    'producto_id'     => $productoId,
                    'servicio_id'     => $servicioId,
                    'cantidad'        => $cant,
                    'precio_unitario' => $pu,
                    'precio_total'    => $pu * $cant,
                ]);
            }

            // Limpia solo el preview (y si usabas carrito en sesión)
            Session::forget('checkout_preview');
            Session::forget('carrito');

            // (Opcional) correo de confirmación
            // Mail::to($pedido->email)->send(new PedidoConfirmado($pedido));

            return response()->json([
                'ok'       => true,
                'redirect' => route('checkout.success', $pedido->id),
                'message'  => '¡Pedido confirmado!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['ok'=>false,'errors'=>$ve->errors()], 422);
        } catch (\Throwable $e) {
            \Log::error('Error confirmar pedido: '.$e->getMessage());
            return response()->json(['ok'=>false,'errors'=>['general'=>['Error al finalizar el pedido.']]], 500);
        }
    }


    public function guardarDatos(Request $request, $pedidoId)
    {
        try {
            $request->validate([
                'nombre'    => 'required|string',
                'email'     => 'required|email',
                'telefono'  => 'required|string',
                'direccion' => 'required|string',
            ]);

            $pedido = Checkout::with('detalles.producto', 'detalles.servicio')->findOrFail($pedidoId);
            $pedido->fill($request->only('nombre', 'email', 'telefono', 'direccion'));
            $pedido->estado_pago = 'pendiente';
            $pedido->save();

            // Enviar emails de notificación
            $this->enviarEmailsPedido($pedido);

            // Limpia el carrito SOLO tras confirmar datos
            Session::forget('carrito');

            return response()->json([
                'ok'       => true,
                'redirect' => route('checkout.success', $pedido->id),
                'message'  => '¡Pedido confirmado y correo enviado!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'ok'     => false,
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Error guardarDatos: ' . $e->getMessage(), ['pedido_id' => $pedidoId]);
            return response()->json([
                'ok'     => false,
                'errors' => ['general' => ['Hubo un error al confirmar tu pedido. Intenta de nuevo.']]
            ], 500);
        }
    }



    public function pedidos($id)
    {
        $empresa = Negocio::findOrFail($id);
        $checkouts = Checkout::where('negocio_id', $id)
            ->with(['user', 'detalles.producto', 'detalles.servicio'])
            ->latest()
            ->get();

        return view('checkout.pedidos', compact('empresa', 'checkouts'));
    }

    public function cambiarEstado($id)
    {
        $pedido = Checkout::findOrFail($id);
        $pedido->estado_pago = 'pagado';
        $pedido->save();

        return back()->with('success', 'Estado actualizado a pagado.');
    }

    public function remove(Request $request, $id)
    {
        $indice = $request->input('indice');
        $carrito = Session::get('carrito', []);

        if (isset($carrito[$indice])) {
            unset($carrito[$indice]);
            Session::put('carrito', $carrito);
        }

        return back()->with('success', 'Producto o servicio eliminado del carrito.');
    }

    public function redirigir(Request $request, $id)
    {
        $carrito = json_decode($request->input('carrito'), true);

        if (!$carrito || !is_array($carrito)) {
            return back()->with('error', 'El carrito está vacío o no es válido.');
        }

        Session::put('carrito', $carrito);
        return redirect()->route('checkout.index', $id);
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function failure()
    {
        return view('checkout.failure');
    }

    /**
     * Enviar emails de notificación cuando se confirma un pedido
     */
    private function enviarEmailsPedido($pedido)
    {
        try {
            $negocio = Negocio::find($pedido->negocio_id);

            // Construir resumen de productos
            $resumenProductos = [];
            foreach ($pedido->detalles as $detalle) {
                $nombre = $detalle->producto ? $detalle->producto->nombre : ($detalle->servicio->nombre ?? 'Producto');
                $resumenProductos[] = "{$nombre} × {$detalle->cantidad} = $" . number_format($detalle->precio_total, 0, ',', '.');
            }

            // 📧 Email al CLIENTE
            if ($pedido->email) {
                Mail::to($pedido->email)->send(new NotificacionGeneral(
                    asunto: '✅ Pedido Confirmado - #' . $pedido->id,
                    titulo: '¡Gracias por tu pedido!',
                    mensaje: 'Hemos recibido tu pedido exitosamente. El negocio lo revisará y te confirmará la disponibilidad pronto.',
                    detalles: [
                        'Pedido #' => $pedido->id,
                        'Negocio' => $negocio->neg_nombre_comercial ?? 'Negocio',
                        'Productos' => implode("\n", $resumenProductos),
                        'Total' => '$' . number_format($pedido->total, 0, ',', '.'),
                        'Dirección de entrega' => $pedido->direccion,
                        'Estado' => 'Pendiente de confirmación',
                    ],
                    accionTexto: 'Ver mi pedido',
                    accionUrl: route('checkout.success'),
                    tipoIcono: 'success'
                ));
            }

            // 📧 Email al DUEÑO DEL NEGOCIO
            if ($negocio && $negocio->user_id) {
                $dueno = \App\Models\User::find($negocio->user_id);
                if ($dueno && $dueno->email) {
                    Mail::to($dueno->email)->send(new NotificacionGeneral(
                        asunto: '🛒 Nuevo Pedido - #' . $pedido->id,
                        titulo: '¡Tienes un nuevo pedido!',
                        mensaje: 'Un cliente ha realizado un pedido en tu negocio. Revisa los detalles y confirma la disponibilidad de los productos.',
                        detalles: [
                            'Pedido #' => $pedido->id,
                            'Cliente' => $pedido->nombre,
                            'Teléfono' => $pedido->telefono,
                            'Email' => $pedido->email,
                            'Productos' => implode("\n", $resumenProductos),
                            'Total' => '$' . number_format($pedido->total, 0, ',', '.'),
                            'Dirección de entrega' => $pedido->direccion,
                        ],
                        accionTexto: 'Ver pedido',
                        accionUrl: route('empresa.dashboard', $negocio->id),
                        tipoIcono: 'info'
                    ));
                }
            }

            Log::info('Emails de pedido enviados', ['pedido_id' => $pedido->id]);
        } catch (\Throwable $e) {
            Log::error('Error al enviar emails de pedido: ' . $e->getMessage(), [
                'pedido_id' => $pedido->id ?? null
            ]);
            // No lanzamos excepción para no interrumpir el flujo del pedido
        }
    }
}
