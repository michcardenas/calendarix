<?php

namespace App\Http\Controllers\negocio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;

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
            'neg_acepto' => 'accepted',
            'neg_imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('neg_imagen')) {
            $path = $request->file('neg_imagen')->store('negocios', 'public');
            $validated['neg_imagen'] = $path;
        }

        $validated['neg_acepto'] = true;

        // ✅ Aquí agregamos el ID del usuario autenticado
        $validated['user_id'] = auth()->id();

        // ✅ Guardamos el negocio
        $negocio = \App\Models\Negocio::create($validated);

        // ✅ Redirección al siguiente paso
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

        // Aquí puedes guardar estos datos en otra tabla o en la misma si ya existe

        return redirect()->route('negocio.categorias');

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
            'neg_virtual' => 'nullable|in:1',
        ]);

        session([
            'negocio_direccion' => $request->neg_direccion,
            'negocio_virtual' => $request->neg_virtual ? true : false,
        ]);

        return redirect()->route('negocio.verificacion');
    }

    public function verificarDireccion()
    {
        return view('negocio.verificar-direccion');
    }

    public function guardarVerificacion(Request $request)
    {
        // Recuperar el último negocio creado por el usuario
        $empresa = Negocio::where('user_id', auth()->id())->latest()->first();

        if (!$empresa) {
            return redirect()->route('negocio.create')->withErrors(['No se encontró ningún negocio para este usuario.']);
        }

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
