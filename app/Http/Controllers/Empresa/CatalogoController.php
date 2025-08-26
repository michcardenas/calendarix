<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Negocio;
use App\Models\Empresa\ServicioEmpresa;

class CatalogoController extends Controller
{
    /**
     * Carga el negocio por id y valida que pertenezca al usuario autenticado.
     */
    private function negocioOrAbort(int $id): Negocio
    {
        $negocio = Negocio::findOrFail($id);
        if ((int)$negocio->user_id !== (int)Auth::id()) {
            abort(403, 'No tienes permisos sobre este negocio.');
        }
        return $negocio;
    }

    /**
     * Retorna las categorías como array desde el campo JSON del negocio.
     */
    private function categorias(Negocio $negocio): array
    {
        if (is_string($negocio->neg_categorias) && $negocio->neg_categorias !== '') {
            return json_decode($negocio->neg_categorias, true) ?: [];
        }
        return is_array($negocio->neg_categorias) ? $negocio->neg_categorias : [];
    }

    /**
     * GET: Listado de servicios y agrupación por categoría.
     * Ruta: GET /empresa/{id}/catalogo/servicios (nombre: empresa.catalogo.servicios)
     */
    public function menuServicios(int $id)
    {
        $empresa  = $this->negocioOrAbort($id);
        $categorias = $this->categorias($empresa);

        $servicios = ServicioEmpresa::where('negocio_id', $empresa->id)->get();
        $serviciosPorCategoria = $servicios->groupBy('categoria');

        return view('empresa.catalogo.menu-servicios', [
            'empresa'               => $empresa,
            'categorias'            => $categorias,
            'servicios'             => $servicios,
            'serviciosPorCategoria' => $serviciosPorCategoria,
            'currentPage'           => 'catalogo',
            'currentSubPage'        => 'servicios',
        ]);
    }

    /**
     * GET: Formulario para crear servicio.
     * Ruta: GET /empresa/{id}/catalogo/servicios/crear (nombre: empresa.catalogo.servicios.crear)
     */
    public function formCrearServicio(int $id)
    {
        $empresa    = $this->negocioOrAbort($id);
        $categorias = $this->categorias($empresa);

        return view('empresa.catalogo.crear-servicio', [
            'empresa'        => $empresa,
            'categorias'     => $categorias,
            'currentPage'    => 'catalogo',
            'currentSubPage' => 'servicios',
        ]);
    }

    /**
     * POST: Guardar servicio.
     * Ruta: POST /empresa/{id}/catalogo/servicios (nombre: empresa.catalogo.servicios.guardar)
     */
    public function guardarServicio(Request $request, int $id)
    {
        $empresa = $this->negocioOrAbort($id);

        $request->validate([
            'nombre'              => 'required|string|max:255',
            'descripcion'         => 'nullable|string',
            'precio'              => 'required|numeric',
            'categoria_existente' => 'nullable|string|max:255',
            'categoria_nueva'     => 'nullable|string|max:255',
        ]);

        $categoria = $request->categoria_nueva ?: $request->categoria_existente;

        ServicioEmpresa::create([
            'negocio_id'  => $empresa->id,
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'categoria'   => $categoria,
        ]);

        // Persistir nueva categoría si aplica
        if ($request->filled('categoria_nueva')) {
            $cats = $this->categorias($empresa);
            if (!in_array($categoria, $cats, true)) {
                $cats[] = $categoria;
                $empresa->neg_categorias = json_encode(array_values(array_unique($cats)));
                $empresa->save();
            }
        }

        return redirect()
            ->route('empresa.catalogo.servicios', $empresa->id)
            ->with('success', 'Servicio creado correctamente.');
    }

    /**
     * POST: Guardar categoría.
     * Ruta: POST /empresa/{id}/catalogo/categorias (nombre: empresa.catalogo.categorias.guardar)
     */
    public function guardarCategoria(Request $request, int $id)
    {
        $request->validate([
            'nueva_categoria' => 'required|string|max:255',
        ]);

        $empresa = $this->negocioOrAbort($id);
        $cats    = $this->categorias($empresa);
        $cats[]  = $request->nueva_categoria;

        $empresa->neg_categorias = json_encode(array_values(array_unique($cats)));
        $empresa->save();

        return back()->with('success', 'Categoría añadida correctamente.');
    }

    /**
     * GET: Editar servicio (scoped al negocio).
     * Ruta: GET /empresa/{id}/catalogo/servicios/{servicio}/editar (nombre: empresa.catalogo.servicios.editar)
     */
    public function editarServicio(int $id, int $servicio)
    {
        $empresa   = $this->negocioOrAbort($id);
        $servicioM = ServicioEmpresa::where('negocio_id', $empresa->id)->findOrFail($servicio);
        $categorias = $this->categorias($empresa);

        return view('empresa.catalogo.editar-servicio', [
            'empresa'        => $empresa,
            'servicio'       => $servicioM,
            'categorias'     => $categorias,
            'currentPage'    => 'catalogo',
            'currentSubPage' => 'servicios',
        ]);
    }

    /**
     * PUT: Actualizar servicio (scoped al negocio).
     * Ruta: PUT /empresa/{id}/catalogo/servicios/{servicio} (nombre: empresa.catalogo.servicios.actualizar)
     */
    public function actualizarServicio(Request $request, int $id, int $servicio)
    {
        $empresa = $this->negocioOrAbort($id);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'precio'      => 'required|numeric',
            'categoria'   => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $servicioM = ServicioEmpresa::where('negocio_id', $empresa->id)->findOrFail($servicio);
        $servicioM->update($request->only('nombre', 'descripcion', 'precio', 'categoria'));

        return redirect()
            ->route('empresa.catalogo.servicios', $empresa->id)
            ->with('success', 'Servicio actualizado.');
    }

    /**
     * POST: Duplicar servicio (scoped al negocio).
     * Ruta: POST /empresa/{id}/catalogo/servicios/{servicio}/duplicar (nombre: empresa.catalogo.servicios.duplicar)
     */
    public function duplicarServicio(int $id, int $servicio)
    {
        $empresa  = $this->negocioOrAbort($id);
        $original = ServicioEmpresa::where('negocio_id', $empresa->id)->findOrFail($servicio);

        $duplicado = $original->replicate();
        $duplicado->nombre = $duplicado->nombre . ' (copia)';
        $duplicado->negocio_id = $empresa->id;
        $duplicado->save();

        return back()->with('success', 'Servicio duplicado.');
    }

    /**
     * DELETE: Eliminar servicio (scoped al negocio).
     * Ruta: DELETE /empresa/{id}/catalogo/servicios/{servicio} (nombre: empresa.catalogo.servicios.eliminar)
     */
    public function eliminarServicio(int $id, int $servicio)
    {
        $empresa   = $this->negocioOrAbort($id);
        $servicioM = ServicioEmpresa::where('negocio_id', $empresa->id)->findOrFail($servicio);
        $servicioM->delete();

        return back()->with('success', 'Servicio eliminado.');
    }
}
