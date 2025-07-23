<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\ImagenProducto;
use Illuminate\Support\Facades\Log;


class ProductoController extends Controller
{
    public function create($id)
    {
        $empresa = Negocio::findOrFail($id); // o Empresa::findOrFail($id);

        return view('empresa.catalogo.crear_producto', [
            'empresa' => $empresa,
            'currentPage' => 'catalogo',
            'currentSubPage' => 'productos',
        ]);
    }


    public function store(Request $request)
    {
        Log::debug('📩 Iniciando store de producto', $request->all());

        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:100',
            'marca' => 'nullable|string|max:100',
            'unidad_medida' => 'required|string|max:20',
            'cantidad' => 'nullable|numeric',
            'descripcion_breve' => 'nullable|string|max:255',
            'descripcion_larga' => 'nullable|string',
            'categoria' => 'nullable|string|max:100',
            'precio_compra' => 'nullable|numeric',
            'precio_venta' => 'nullable|numeric',
            'precio_promocional' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'stock_minimo' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'negocio_id' => 'required|exists:negocios,id',
        ]);

        $datos = $request->only([
            'nombre',
            'codigo_barras',
            'marca',
            'unidad_medida',
            'cantidad',
            'descripcion_breve',
            'descripcion_larga',
            'categoria',
            'precio_compra',
            'precio_venta',
            'precio_promocional',
            'stock',
            'stock_minimo',
            'negocio_id'
        ]);

        $datos['user_id'] = auth()->id();
        $datos['activar_oferta'] = $request->has('activar_oferta');
        $datos['controla_inventario'] = $request->has('controla_inventario');
        $datos['estado_publicado'] = $request->has('estado_publicado');
        $datos['mostrar_en_catalogo'] = $request->has('mostrar_en_catalogo');
        $datos['negocio_id'] = $request->negocio_id;

        Log::debug('📦 Datos preparados para crear producto', $datos);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = uniqid('producto_') . '.' . $file->getClientOriginalExtension();
            $destination = '/home/u533926615/domains/calendarix.uy/public_html/images';
            $file->move($destination, $filename);
            $datos['imagen'] = '/images/' . $filename;

            Log::debug('🖼 Imagen principal cargada', ['ruta' => $datos['imagen']]);
        }

        try {
            $producto = Producto::create([
                'user_id' => auth()->id(),
                'nombre' => $request->nombre,
                'codigo_barras' => $request->codigo_barras,
                'marca' => $request->marca,
                'unidad_medida' => $request->unidad_medida,
                'cantidad' => $request->cantidad,
                'descripcion_breve' => $request->descripcion_breve,
                'descripcion_larga' => $request->descripcion_larga,
                'categoria' => $request->categoria,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'precio_promocional' => $request->precio_promocional,
                'activar_oferta' => $request->has('activar_oferta'),
                'controla_inventario' => $request->has('controla_inventario'),
                'stock' => $request->stock,
                'stock_minimo' => $request->stock_minimo,
                'estado_publicado' => $request->has('estado_publicado'),
                'mostrar_en_catalogo' => $request->has('mostrar_en_catalogo'),
                'imagen' => $datos['imagen'] ?? null,
                'negocio_id' => $request->negocio_id,
            ]);

            Log::debug('✅ Producto creado exitosamente', ['producto_id' => $producto->id]);
        } catch (\Exception $e) {
            Log::error('❌ Error al crear el producto', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return back()->with('error', 'Error al crear el producto. Revisa los logs.');
        }

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $filename = uniqid('producto_multi_') . '.' . $imagen->getClientOriginalExtension();
                $destination = '/home/u533926615/domains/calendarix.uy/public_html/images';
                $imagen->move($destination, $filename);

                \App\Models\ImagenProducto::create([
                    'producto_id' => $producto->id,
                    'ruta' => '/images/' . $filename,
                ]);

                Log::debug('📷 Imagen adicional guardada', ['ruta' => '/images/' . $filename]);
            }
        }

        return redirect()->route('producto.panel', ['id' => $request->negocio_id])
            ->with('success', 'Producto creado correctamente.');
    }


    public function panel($id)
    {
        $empresa = Negocio::findOrFail($id); // busca el negocio por ID directamente

        // Productos únicamente de ese negocio (relación negocio_id)
        $productos = Producto::where('negocio_id', $id)->get();

        // Si el usuario tiene varios negocios (opcional para dropdown u otros)
        $negocios = Negocio::where('user_id', auth()->id())->get();

        return view('empresa.catalogo.panel_productos', [
            'negocios' => $negocios,
            'productos' => $productos,
            'empresa' => $empresa,
            'currentPage' => 'catalogo',
            'currentSubPage' => 'productos_ver',
        ]);
    }

    public function edit(Producto $producto)
    {
        $empresa = Negocio::where('user_id', auth()->id())->latest()->first();

        return view('empresa.catalogo.editar_producto', [
            'producto' => $producto,
            'empresa' => $empresa,
            'currentPage' => 'catalogo',
            'currentSubPage' => 'productos',
        ]);
    }

    public function update(Request $request, Producto $producto)
    {
        Log::debug('📩 Entró al método update del producto', $request->all());

        $request->merge([
            'precio_compra' => self::parseMoneda($request->precio_compra),
            'precio_venta' => self::parseMoneda($request->precio_venta),
            'precio_promocional' => self::parseMoneda($request->precio_promocional),
        ]);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:100',
            'marca' => 'nullable|string|max:100',
            'unidad_medida' => 'required|string|max:20',
            'cantidad' => 'nullable|numeric',
            'descripcion_breve' => 'nullable|string|max:255',
            'descripcion_larga' => 'nullable|string',
            'categoria' => 'nullable|string|max:100',
            'precio_compra' => 'nullable|numeric',
            'precio_venta' => 'nullable|numeric',
            'precio_promocional' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'stock_minimo' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $producto->fill($request->except(['imagen', 'imagenes']));

        $producto->activar_oferta = $request->has('activar_oferta');
        $producto->controla_inventario = $request->has('controla_inventario');
        $producto->estado_publicado = $request->has('estado_publicado');
        $producto->mostrar_en_catalogo = $request->has('mostrar_en_catalogo');

        // Imagen principal (con ruta fija)
        if ($request->hasFile('imagen')) {
            $filename = 'producto_' . uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension();
            $request->file('imagen')->move('/home/u533926615/domains/calendarix.uy/public_html/images', $filename);
            $producto->imagen = '/images/' . $filename;
        }

        $producto->save();

        // Imágenes adicionales (con ruta fija)
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $img) {
                $filename = 'producto_multi_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move('/home/u533926615/domains/calendarix.uy/public_html/images', $filename);

                \App\Models\ImagenProducto::create([
                    'producto_id' => $producto->id,
                    'ruta' => '/images/' . $filename,
                ]);
            }
        }

        return redirect()->route('producto.panel', ['id' => $producto->negocio_id])
            ->with('success', 'Producto actualizado correctamente.');
    }




    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('producto.panel', ['id' => $producto->negocio_id])
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function eliminarImagen($id)
    {
        $imagen = \App\Models\ImagenProducto::findOrFail($id);

        // Elimina el archivo físico del storage
        \Storage::disk('public')->delete($imagen->ruta);

        // Elimina el registro de la BD
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    private static function parseMoneda($valor)
    {
        // Elimina cualquier símbolo de moneda, puntos de miles y cambia comas por puntos
        return $valor !== null
            ? floatval(str_replace([',', '$', ' '], ['.', '', ''], preg_replace('/[^\d,\.]/', '', $valor)))
            : null;
    }
}
