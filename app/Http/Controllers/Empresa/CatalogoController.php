<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
            $arr = json_decode($negocio->neg_categorias, true);
            return is_array($arr) ? $arr : [];
        }
        return is_array($negocio->neg_categorias) ? $negocio->neg_categorias : [];
    }

    /**
     * Normaliza una categoría (trim + colapsa espacios).
     */
    private function normalizeCategory(?string $cat): ?string
    {
        if ($cat === null) return null;
        $cat = trim(preg_replace('/\s+/u', ' ', $cat));
        return $cat !== '' ? $cat : null;
    }

    /**
     * Agrega una categoría al JSON del negocio si no existe (case-insensitive).
     */
    private function addCategoryIfMissing(Negocio $negocio, ?string $categoria): void
    {
        $categoria = $this->normalizeCategory($categoria);
        if ($categoria === null) return;

        $cats = $this->categorias($negocio);
        $lower = array_map(fn($c) => mb_strtolower($c ?? ''), $cats);

        if (!in_array(mb_strtolower($categoria), $lower, true)) {
            $cats[] = $categoria;
            $negocio->neg_categorias = json_encode(array_values($cats), JSON_UNESCAPED_UNICODE);
            $negocio->save();
        }
    }

    /**
     * Sanea un precio que puede venir con miles/decimales locales.
     * Acepta: "10.000", "10.000,50", "10,50", "10000.50".
     */
    private function normalizeMoney($value): float
    {
        $v = trim((string)$value);

        if (strpos($v, ',') !== false && strpos($v, '.') !== false) {
            // "10.000,50"
            $v = str_replace('.', '', $v);
            $v = str_replace(',', '.', $v);
        } elseif (strpos($v, ',') !== false) {
            // "10,50"
            $v = str_replace(',', '.', $v);
        } else {
            // "10.000" (miles sin decimales)
            if (preg_match('/^\d{1,3}(\.\d{3})+$/', $v)) {
                $v = str_replace('.', '', $v);
            }
        }
        return (float)$v;
    }

    /**
     * GET: Listado de servicios y agrupación por categoría.
     * Ruta: GET /empresa/{id}/catalogo/servicios (nombre: empresa.catalogo.servicios)
     */
    public function menuServicios(int $id)
    {
        $empresa    = $this->negocioOrAbort($id);
        $categorias = $this->categorias($empresa);

        $servicios = ServicioEmpresa::where('negocio_id', $empresa->id)
            ->orderBy('nombre')
            ->get();

        // Mapear null → "Sin categoría" para agrupar mejor
        $serviciosPorCategoria = $servicios->groupBy(function ($s) {
            return $s->categoria ?: 'Sin categoría';
        });

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
            'precio'              => 'required', // se sanea abajo
            'duracion'            => 'nullable|string|max:50',
            'categoria_existente' => 'nullable|string|max:255',
            'categoria_nueva'     => 'nullable|string|max:255',
            'categoria'           => 'nullable|string|max:255', // por si el front la manda así
        ]);

        $precio = $this->normalizeMoney($request->precio);

        // Prioridad: nueva > existente > categoria
        $categoriaRaw = $request->filled('categoria_nueva')
            ? $request->input('categoria_nueva')
            : ($request->input('categoria_existente') ?? $request->input('categoria'));

        $categoria = $this->normalizeCategory($categoriaRaw);

        $servicio = ServicioEmpresa::create([
            'negocio_id'  => $empresa->id,
            'nombre'      => trim($request->nombre),
            'descripcion' => $request->descripcion,
            'precio'      => $precio,
            'categoria'   => $categoria,
            'duracion'    => $request->duracion,
        ]);

        $this->addCategoryIfMissing($empresa, $categoria);

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

        $empresa   = $this->negocioOrAbort($id);
        $categoria = $this->normalizeCategory($request->nueva_categoria);

        $this->addCategoryIfMissing($empresa, $categoria);

        return back()->with('success', 'Categoría añadida correctamente.');
    }

    /**
     * GET: Editar servicio (scoped al negocio).
     * Ruta: GET /empresa/{id}/catalogo/servicios/{servicio}/editar (nombre: empresa.catalogo.servicios.editar)
     */
    public function editarServicio(int $id, int $servicio)
    {
        $empresa    = $this->negocioOrAbort($id);
        $servicioM  = ServicioEmpresa::where('negocio_id', $empresa->id)->findOrFail($servicio);
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
            'precio'      => 'required', // saneamos abajo
            'categoria'   => 'required|string|max:255',
            'duracion'    => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $servicioM = ServicioEmpresa::where('negocio_id', $empresa->id)->findOrFail($servicio);

        $payload = [
            'nombre'      => trim($request->nombre),
            'descripcion' => $request->descripcion,
            'precio'      => $this->normalizeMoney($request->precio),
            'categoria'   => $this->normalizeCategory($request->categoria),
            'duracion'    => $request->duracion,
        ];

        $servicioM->update($payload);
        $this->addCategoryIfMissing($empresa, $payload['categoria']);

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
