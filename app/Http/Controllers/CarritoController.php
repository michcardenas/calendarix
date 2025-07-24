<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $carrito = Session::get('carrito', []);

        $cantidad = (int) $request->input('cantidad', 1);

        $nuevo = [
            'id' => $request->input('id'),
            'nombre' => $request->input('nombre'),
            'precio_unitario' => $request->input('precio'),
            'tipo' => $request->input('tipo'),
            'cantidad' => $cantidad,
        ];

        // Verificar si ya existe en el carrito
        $indiceExistente = collect($carrito)->search(
            fn($item) =>
            $item['id'] == $nuevo['id'] && $item['tipo'] == $nuevo['tipo']
        );

        if ($indiceExistente !== false) {
            $carrito[$indiceExistente]['cantidad'] += $cantidad;
        } else {
            $carrito[] = $nuevo;
        }

        Session::put('carrito', $carrito);

        return response()->json(['success' => true, 'carrito' => $carrito]);
    }
}
