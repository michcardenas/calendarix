<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Empresa\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NegocioController extends Controller
{
    public function show($id, $slug)
    {
        $negocio = Negocio::with(['servicios', 'horarios', 'bloqueos'])->findOrFail($id);

        if (Str::slug($negocio->neg_nombre) !== $slug) {
            return redirect()->route('negocios.show', [
                'id' => $negocio->id,
                'slug' => Str::slug($negocio->neg_nombre)
            ]);
        }

        // Obtener empresa asociada
        $empresa = \App\Models\Empresa\Empresa::find($negocio->neg_empresa_id);

        return view('negocio.perfil', [
            'negocio' => $negocio,
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'negocio',
        ]);
    }
}
