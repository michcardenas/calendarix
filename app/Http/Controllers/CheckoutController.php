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

    public function finalizar(Request $request, $id)
    {
        $carrito = Session::get('carrito', []);
        Log::debug('Contenido del carrito:', $carrito);

        if (empty($carrito)) {
            Log::warning('Carrito vacío al intentar finalizar.');
            return back()->with('error', 'El carrito está vacío.');
        }

        $total = 0;

        foreach ($carrito as $index => $item) {
            // Soportar 'precio' o 'precio_unitario'
            $precioUnitario = $item['precio_unitario'] ?? $item['precio'] ?? null;

            if (!isset($item['cantidad']) || $precioUnitario === null) {
                Log::error("Artículo inválido en índice $index: " . json_encode($item));
                return back()->with('error', 'Hay un artículo inválido en el carrito.');
            }

            $subtotal = $precioUnitario * $item['cantidad'];
            Log::debug("Artículo $index subtotal: $subtotal", $item);
            $total += $subtotal;
        }

        Log::info("Total calculado del carrito: $total");

        $pedido = Checkout::create([
            'negocio_id' => $id,
            'user_id' => auth()->id(),
            'metodo_pago' => 'pendiente',
            'estado_pago' => 'pendiente',
            'total' => $total,
        ]);

        Log::info("Pedido creado con ID: {$pedido->id}");

        foreach ($carrito as $index => $item) {
            $precioUnitario = $item['precio_unitario'] ?? $item['precio'] ?? 0;
            $cantidad = $item['cantidad'] ?? 1;

            $detalle = [
                'checkout_id'     => $pedido->id,
                'producto_id'     => $item['tipo'] === 'producto' ? ($item['id'] ?? null) : null,
                'servicio_id'     => $item['tipo'] === 'servicio' ? ($item['id'] ?? null) : null,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precioUnitario,
                'precio_total'    => $precioUnitario * $cantidad,
            ];

            Log::debug("Insertando detalle $index:", $detalle);
            CheckoutDetalle::create($detalle);
        }

        Session::forget('carrito');
        Log::info("Carrito limpiado y redirigiendo a checkout.confirmar con ID {$pedido->id}");

        return redirect()->route('checkout.confirmar', $pedido->id);
    }

    public function confirmar($id)
    {
        $pedido = Checkout::with('detalles.producto', 'detalles.servicio')->findOrFail($id);
        return view('checkout.confirmar', compact('pedido'));
    }

    public function guardarDatos(Request $request, $id)
    {
        try {
            // Validar entrada del formulario
            $request->validate([
                'nombre'    => 'required|string',
                'email'     => 'required|email',
                'telefono'  => 'required|string',
                'direccion' => 'required|string',
            ]);

            // Buscar el pedido con relaciones
            $pedido = Checkout::with('detalles.producto', 'detalles.servicio')->findOrFail($id);

            // Guardar los datos del cliente
            $pedido->fill($request->only('nombre', 'email', 'telefono', 'direccion'));
            $pedido->estado_pago = 'pendiente';
            $pedido->save();

            // Verificar si el email quedó guardado correctamente
            if (!filter_var($pedido->email, FILTER_VALIDATE_EMAIL)) {
                Log::error('❌ Email inválido después de guardar:', ['email' => $pedido->email, 'pedido_id' => $pedido->id]);
                return back()->with('error', 'Correo inválido. No se pudo enviar confirmación.');
            }

            // Enviar el correo
            Mail::to($pedido->email)->send(new PedidoConfirmado($pedido));
            Log::info('✅ Pedido confirmado y correo enviado correctamente.', ['pedido_id' => $pedido->id, 'email' => $pedido->email]);

            return redirect()->route('checkout.success', $pedido->id)
                ->with('success', '¡Pedido confirmado y correo enviado!');
        } catch (\Exception $e) {
            Log::error('❌ Error al confirmar pedido: ' . $e->getMessage(), [
                'pedido_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Hubo un error al confirmar tu pedido. Intenta de nuevo.');
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
}
