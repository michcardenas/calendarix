<?php

namespace App\Http\Controllers\negocio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;
use Illuminate\Support\Facades\Log;

class NegocioController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        return view('negocio.creacion-negocio', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'neg_nombre' => 'required|string|max:100',
            'neg_apellido' => 'required|string|max:100',
            'neg_email' => 'required|email|max:255|unique:negocios',
            'neg_telefono' => 'required|string|max:20',
            'neg_pais' => 'nullable|string|max:100',
            'neg_facebook' => 'nullable|string|max:255',
            'neg_instagram' => 'nullable|string|max:255',
            'neg_imagen' => 'nullable|image|max:2048',
            'neg_nombre_comercial' => 'nullable|string|max:255',
            'neg_sitio_web' => 'nullable|string|max:255',
            'neg_categorias' => 'nullable|string',
            'neg_equipo' => 'nullable|string|max:255',
            'neg_direccion' => 'nullable|string|max:255',
            'neg_virtual' => 'nullable|boolean',
            'neg_direccion_confirmada' => 'nullable|boolean',
            'configuracion_bloques' => 'nullable|string',
            'neg_acepto' => 'accepted',
        ]);

        // Imagen
        if ($request->hasFile('neg_imagen')) {
            $file = $request->file('neg_imagen');
            $filename = uniqid('negocio_') . '.' . $file->getClientOriginalExtension();
            $destination = '/home/u533926615/domains/calendarix.uy/public_html/images';
            $file->move($destination, $filename);
            $validated['neg_imagen'] = '/images/' . $filename;
        }

        $validated['neg_virtual'] = $request->has('neg_virtual');
        $validated['neg_direccion_confirmada'] = $request->has('neg_direccion_confirmada');
        $validated['neg_acepto'] = true;
        $validated['user_id'] = auth()->id();

        $negocio = \App\Models\Negocio::create($validated);

        // ✅ Guardar ID en sesión
        session(['negocio_id' => $negocio->id]);

        return redirect()->route('negocio.datos');
    }



    public function datosNegocio()
    {
        return view('negocio.datos-negocio');
    }

    public function guardarNombre(Request $request)
    {
        $request->validate([
            'neg_nombre_comercial' => 'required|string|max:255',
            'neg_sitio_web' => 'nullable|url|max:255',
        ]);

        $negocio = \App\Models\Negocio::where('user_id', auth()->id())->firstOrFail();

        $negocio->update([
            'neg_nombre_comercial' => $request->neg_nombre_comercial,
            'neg_sitio_web' => $request->neg_sitio_web,
        ]);

        return redirect()->route('negocio.categorias')->with('success', 'Datos guardados correctamente.');
    }

    public function categorias()
    {
        return view('negocio.categorias');
    }

    public function guardarCategorias(Request $request)
    {
        $request->validate([
            'neg_categorias' => 'required|array|min:1|max:4',
        ]);

        session(['negocio_categorias' => $request->neg_categorias]);

        return redirect()->route('negocio.equipo');
    }

    public function equipo()
    {
        return view('negocio.equipo');
    }

    public function guardarEquipo(Request $request)
    {
        $request->validate([
            'neg_equipo' => 'required|string|max:50',
        ]);

        session(['negocio_equipo' => $request->neg_equipo]);

        return redirect()->route('negocio.ubicacion');
    }

    public function ubicacion()
    {
        return view('negocio.ubicacion');
    }

    public function guardarUbicacion(Request $request)
    {
        $request->validate([
            'neg_direccion' => 'nullable|string|max:255',
            'neg_virtual'   => 'nullable|in:1',
        ]);

        session([
            'negocio_direccion' => $request->neg_direccion,
            'negocio_virtual'   => $request->neg_virtual ? true : false,
        ]);

        return redirect()->route('negocio.verificacion');
    }




    public function verificarDireccion()
    {
        return view('negocio.verificar-direccion');
    }

    public function guardarVerificacion(Request $request)
    {
        $negocioId = session('negocio_id');

        $empresa = Negocio::where('id', $negocioId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$empresa) {
            return redirect()->route('negocio.create')
                ->withErrors(['No se encontró ningún negocio válido en progreso.']);
        }

        $direccion = session('negocio_direccion');
        $esVirtual = session('negocio_virtual');

        if ($direccion !== null || $esVirtual !== null) {
            $empresa->neg_direccion = $direccion;
            $empresa->neg_virtual   = $esVirtual ? 1 : 0;
            $empresa->save();

            Log::debug('✅ Negocio actualizado desde sesión', [
                'id' => $empresa->id,
                'neg_direccion' => $empresa->neg_direccion,
                'neg_virtual' => $empresa->neg_virtual,
            ]);
        }

        // Limpieza de sesión
        session()->forget(['negocio_direccion', 'negocio_virtual', 'negocio_id']);

        return redirect()->route('empresa.dashboard', ['id' => $empresa->id]);
    }

    public function index()
    {
        $misEmpresas = \App\Models\Negocio::where('user_id', auth()->id())->get();

        return view('empresa.dashboard', compact('misEmpresas'));
    }

    public function show($id)
    {
        $empresa = \App\Models\Negocio::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail(); // Seguridad: solo puede ver empresas propias

        return view('empresa.dashboard-empresa', compact('empresa'));
    }

    public function destroy(Negocio $negocio)
    {
        if ($negocio->user_id !== auth()->id()) {
            abort(403);
        }

        $negocio->delete();

        return back()->with('status', 'negocio-eliminado');
    }
}
