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
            'empresa_id' => 'required|exists:negocios,id',

            // Campos básicos
            'confneg_nombre' => 'nullable|string|max:255',
            'confneg_pais' => 'nullable|string|max:100',

            // Datos personales
            'confneg_nombre_real' => 'nullable|string|max:255',
            'confneg_apellido' => 'nullable|string|max:255',
            'confneg_email' => 'nullable|email|max:255',
            'confneg_telefono' => 'nullable|string|max:20',

            // Operativos
            'confneg_equipo' => 'nullable|string|max:255',
            'confneg_direccion' => 'nullable|string|max:255',
            'confneg_virtual' => 'nullable|in:1',
            'confneg_direccion_confirmada' => 'nullable|in:1',

            // Enlaces
            'confneg_facebook' => 'nullable|url|max:255',
            'confneg_instagram' => 'nullable|url|max:255',
            'confneg_web' => 'nullable|url|max:255',

            // Archivos
            'confneg_imagen' => 'nullable|image|max:2048',
            'confneg_portada' => 'nullable|image|max:4096',
        ]);

        $negocio = \App\Models\Negocio::findOrFail($request->empresa_id);

        // Seguridad: solo el dueño puede editar
        if ($negocio->user_id !== auth()->id()) {
            abort(403, 'No autorizado');
        }

        // Datos básicos
        $negocio->neg_nombre_comercial = $request->confneg_nombre;
        $negocio->neg_pais = $request->confneg_pais;

        // Datos personales
        $negocio->neg_nombre = $request->confneg_nombre_real;
        $negocio->neg_apellido = $request->confneg_apellido;
        $negocio->neg_email = $request->confneg_email;
        $negocio->neg_telefono = $request->confneg_telefono;

        // Estructura del negocio
        $negocio->neg_equipo = $request->confneg_equipo;
        $negocio->neg_direccion = $request->confneg_direccion;
        $negocio->neg_virtual = $request->has('confneg_virtual');
        $negocio->neg_direccion_confirmada = $request->has('confneg_direccion_confirmada');

        // Redes y sitio
        $negocio->neg_facebook = $request->confneg_facebook;
        $negocio->neg_instagram = $request->confneg_instagram;
        $negocio->neg_sitio_web = $request->confneg_web;

        // Imagen (logo)
        if ($request->hasFile('confneg_imagen')) {
            $file = $request->file('confneg_imagen');
            $filename = uniqid('negocio_') . '.' . $file->getClientOriginalExtension();
            $destination = '/home/u533926615/domains/calendarix.uy/public_html/images';
            $file->move($destination, $filename);
            $negocio->neg_imagen = '/images/' . $filename;
        }

        // Portada
        if ($request->hasFile('confneg_portada')) {
            $file = $request->file('confneg_portada');
            $filename = uniqid('portada_') . '.' . $file->getClientOriginalExtension();
            $destination = '/home/u533926615/domains/calendarix.uy/public_html/images';
            $file->move($destination, $filename);
            $negocio->neg_portada = '/images/' . $filename;
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
