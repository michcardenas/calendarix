<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Negocio;
use App\Models\Empresa\ServicioEmpresa;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CatalogoController extends Controller
{
    public function menuServicios()
    {
        $negocio = \App\Models\Negocio::where('user_id', Auth::id())->first();

        if (!$negocio) {
            // Redirecciona al usuario a una vista de configuración o muestra un mensaje
            return redirect()->route('dashboard')->with('error', 'Primero debes registrar tu negocio.');
        }

        $categorias = is_string($negocio->neg_categorias)
            ? json_decode($negocio->neg_categorias, true)
            : ($negocio->neg_categorias ?? []);

        $servicios = ServicioEmpresa::where('negocio_id', $negocio->id)->get();
        $serviciosPorCategoria = $servicios->groupBy('categoria');

        return view('empresa.catalogo.menu-servicios', [
            'categorias' => $categorias,
            'empresa' => $negocio,
            'currentPage' => 'catalogo',
            'currentSubPage' => 'servicios',
            'servicios' => $servicios,
            'serviciosPorCategoria' => $serviciosPorCategoria,
        ]);
    }

    public function guardarCategoria(Request $request)
    {
        Log::debug('📝 Ingresando a guardarCategoria');

        $request->validate([
            'nueva_categoria' => 'required|string|max:255',
        ]);
        Log::debug('✅ Validación pasada', ['nueva_categoria' => $request->nueva_categoria]);

        $negocio = \App\Models\Negocio::where('user_id', auth()->id())->first();
        if (!$negocio) {
            Log::error('❌ No se encontró el negocio del usuario', ['user_id' => auth()->id()]);
            return redirect()->back()->with('error', 'No se encontró el negocio del usuario.');
        }

        Log::debug('🔍 Negocio encontrado', ['negocio_id' => $negocio->id]);

        $categorias = is_string($negocio->neg_categorias)
            ? json_decode($negocio->neg_categorias, true)
            : [];

        Log::debug('📂 Categorías actuales', ['categorias' => $categorias]);

        $categorias[] = $request->nueva_categoria;

        $negocio->neg_categorias = json_encode($categorias);
        $negocio->save();

        Log::info('✅ Categoría añadida correctamente', [
            'categorias_actualizadas' => $negocio->neg_categorias,
        ]);

        return redirect()->back()->with('success', 'Categoría añadida correctamente.');
    }

    public function guardarServicio(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'precio' => 'required|numeric',
            'categoria_existente' => 'nullable|string',
            'categoria_nueva' => 'nullable|string|max:255',
        ]);

        $negocio = \App\Models\Negocio::where('user_id', auth()->id())->first();

        if (!$negocio) {
            return redirect()->route('dashboard')->with('error', 'Debes configurar primero tu negocio.');
        }

        $categoria = $request->categoria_nueva ?: $request->categoria_existente;

        ServicioEmpresa::create([
            'negocio_id' => $negocio->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria' => $categoria,
        ]);

        // Guardar nueva categoría en el JSON del negocio si aplica
        if ($request->filled('categoria_nueva')) {
            $categorias = is_string($negocio->neg_categorias)
                ? json_decode($negocio->neg_categorias, true)
                : [];

            if (!in_array($categoria, $categorias)) {
                $categorias[] = $categoria;
                $negocio->neg_categorias = json_encode($categorias);
                $negocio->save();
            }
        }

        return redirect()->route('catalogo.servicios')->with('success', 'Servicio creado correctamente.');
    }

    public function editarServicio($id)
    {
        $servicio = ServicioEmpresa::findOrFail($id);
        return view('empresa.catalogo.editar-servicio', compact('servicio'));
    }

    public function actualizarServicio(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'categoria' => 'required',
        ]);

        $servicio = ServicioEmpresa::findOrFail($id);
        $servicio->update($request->only('nombre', 'descripcion', 'precio', 'categoria'));

        return redirect()->route('catalogo.servicios')->with('success', 'Servicio actualizado.');
    }

    public function duplicarServicio($id)
    {
        $original = ServicioEmpresa::findOrFail($id);
        $duplicado = $original->replicate();
        $duplicado->nombre = $duplicado->nombre . ' (copia)';
        $duplicado->save();

        return back()->with('success', 'Servicio duplicado.');
    }

    public function eliminarServicio($id)
    {
        $servicio = ServicioEmpresa::findOrFail($id);
        $servicio->delete();

        return back()->with('success', 'Servicio eliminado.');
    }

    public function formCrearServicio()
    {
        $negocio = \App\Models\Negocio::where('user_id', Auth::id())->first();

        if (!$negocio) {
            return redirect()->route('dashboard')->with('error', 'Debes configurar primero tu negocio.');
        }

        $categorias = is_string($negocio->neg_categorias)
            ? json_decode($negocio->neg_categorias, true)
            : [];

        return view('empresa.catalogo.crear-servicio', [
            'categorias' => $categorias,
            'empresa' => $negocio,
            'currentPage' => 'catalogo',
            'currentSubPage' => 'servicios',
        ]);
    }
}
