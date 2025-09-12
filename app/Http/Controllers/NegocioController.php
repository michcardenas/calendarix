<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Empresa\Empresa;
use App\Models\Trabajador; // ← IMPORTANTE
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NegocioController extends Controller
{
    public function show($id, $slug)
    {
        $negocio = Negocio::with(['servicios', 'horarios', 'bloqueos', 'productos'])->findOrFail($id);

        if (Str::slug($negocio->neg_nombre) !== $slug) {
            return redirect()->route('negocios.show', [
                'id'   => $negocio->id,
                'slug' => Str::slug($negocio->neg_nombre)
            ]);
        }

        $empresa = \App\Models\Empresa\Empresa::with('productos')->find($negocio->neg_empresa_id);

        // 🔹 Trabajadores del negocio (para el modal y JS)
        $trabajadores = Trabajador::where('negocio_id', $negocio->id)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return view('negocio.perfil', [
            'negocio'       => $negocio,
            'empresa'       => $empresa,
            'trabajadores'  => $trabajadores,   // ← NUEVO
            'currentPage'   => 'configuracion',
            'currentSubPage'=> 'negocio',
        ]);
    }
}