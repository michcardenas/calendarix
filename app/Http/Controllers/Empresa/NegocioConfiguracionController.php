<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;
use Illuminate\Support\Facades\Auth;

class NegocioConfiguracionController extends Controller
{
    public function guardar(Request $request)
{
    $request->validate([
        'confneg_nombre' => 'nullable|string|max:255',
        'confneg_pais' => 'nullable|string|max:100',
        'confneg_facebook' => 'nullable|url',
        'confneg_instagram' => 'nullable|url',
        'confneg_web' => 'nullable|url',
        'empresa_id' => 'required|exists:negocios,id',
    ]);

    $negocio = \App\Models\Negocio::findOrFail($request->empresa_id);

    // Solo si el usuario autenticado es dueño del negocio
    if ($negocio->user_id !== auth()->id()) {
        abort(403, 'No autorizado');
    }

    // Actualiza campos que vengan del formulario (si existen)
    if ($request->filled('confneg_nombre')) {
        $negocio->neg_nombre_comercial = $request->confneg_nombre;
    }

    if ($request->filled('confneg_pais')) {
        $negocio->neg_pais = $request->confneg_pais;
    }

    // Enlaces externos
    if ($request->has('confneg_facebook')) {
        $negocio->neg_facebook = $request->confneg_facebook;
    }

    if ($request->has('confneg_instagram')) {
        $negocio->neg_instagram = $request->confneg_instagram;
    }

    if ($request->has('confneg_web')) {
        $negocio->neg_sitio_web = $request->confneg_web;
    }

    $negocio->save();

    return back()->with('success', 'Información del negocio actualizada correctamente.');
}

public function centros()
{
    $empresa = auth()->user()->negocios->first(); // o ajusta según cómo accedes a la empresa

    $centros = collect();
    if ($empresa->neg_direccion) {
        $centros->push([
            'nombre' => $empresa->neg_nombre_comercial ?? 'Centro principal',
            'direccion' => $empresa->neg_direccion,
        ]);
    }

    return view('empresa.configuracion.centros', [
        'empresa' => $empresa,
        'centros' => $centros,
        'currentPage' => 'configuracion',
        'currentSubPage' => 'centros'
    ]);
}

public function actualizarCentro(Request $request, $id)
{
    if ($id === 'principal') {
        $negocio = auth()->user()->negocios->first(); // o ajusta si hay varios
        $negocio->neg_direccion = $request->input('direccion');
        $negocio->save();

        return back()->with('success', 'Dirección actualizada correctamente.');
    }

    abort(404);
}

public function procedencia()
{
    $empresa = auth()->user()->negocios->first();

    $procedencias = collect([
        (object)['id' => 1, 'nombre' => 'Instagram'],
        (object)['id' => 2, 'nombre' => 'Google'],
        (object)['id' => 3, 'nombre' => 'Facebook'],
    ]);

    return view('empresa.configuracion.procedencia', [
        'empresa' => $empresa, // ✅ Esto es lo que faltaba
        'procedencias' => $procedencias,
        'currentPage' => 'configuracion',
        'currentSubPage' => 'procedencia'
    ]);
}


public function actualizarProcedencia(Request $request)
{
    $request->validate([
        'neg_instagram' => 'nullable|string|max:255',
        'neg_facebook' => 'nullable|string|max:255',
        'neg_sitio_web' => 'nullable|string|max:255',
    ]);

    $negocio = auth()->user()->negocios->first(); // Asegúrate de que tenga un negocio

    $negocio->neg_instagram = $request->neg_instagram;
    $negocio->neg_facebook = $request->neg_facebook;
    $negocio->neg_sitio_web = $request->neg_sitio_web;

    $negocio->save();

    return back()->with('success', 'Procedencia actualizada correctamente.');
}



}
