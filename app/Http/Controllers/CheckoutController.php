<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index($id)
    {
        $empresa = Negocio::findOrFail($id);
        $productos = Producto::where('negocio_id', $id)->where('estado_publicado', 1)->get();

        // Carrito en sesión
        $carrito = Session::get('carrito', []);

        return view('checkout.index', compact('empresa', 'productos', 'carrito'));
    }

    public function add(Request $request, $id)
    {
        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad', 1);

        $producto = Producto::findOrFail($productoId);

        $carrito = Session::get('carrito', []);

        // Agregar o actualizar producto en carrito
        $carrito[$productoId] = [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio_unitario' => $producto->precio_venta,
            'cantidad' => isset($carrito[$productoId]) ? $carrito[$productoId]['cantidad'] + $cantidad : $cantidad,
        ];

        Session::put('carrito', $carrito);

        return back()->with('success', 'Producto agregado al checkout.');
    }

    public function finalizar(Request $request, $id)
    {
        $carrito = Session::get('carrito', []);

        foreach ($carrito as $item) {
            Checkout::create([
                'negocio_id' => $id,
                'producto_id' => $item['id'],
                'user_id' => auth()->id(),
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'precio_total' => $item['precio_unitario'] * $item['cantidad'],
                'metodo_pago' => 'pendiente',
                'estado_pago' => 'pendiente',
            ]);
        }

        Session::forget('carrito');

        return back()->with('success', '¡Pedido registrado!');
    }

    public function pedidos($id)
    {
        $empresa = Negocio::findOrFail($id);
        $checkouts = Checkout::where('negocio_id', $id)->with('producto', 'user')->latest()->get();

        return view('checkout.pedidos', compact('empresa', 'checkouts'));
    }

    public function cambiarEstado($id)
    {
        $pedido = Checkout::findOrFail($id);
        $pedido->estado_pago = 'pagado';
        $pedido->save();
 
        return back()->with('success', 'Estado actualizado a pagado.');
    }
}
