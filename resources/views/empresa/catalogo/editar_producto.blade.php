@extends('layouts.empresa')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    {{-- Encabezado --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#3B4D9A]">✏️ Editar Producto</h2>
        <p class="text-sm text-[#6C7DBB]">Actualiza los datos de tu producto y gestiona su visibilidad, precios e inventario.</p>
    </div>

    {{-- Errores --}}
    @if ($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700 rounded p-4 mb-6">
        <strong class="font-semibold">⚠️ Ocurrió un error:</strong>
        <ul class="list-disc pl-5 mt-2 text-sm space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('producto.actualizar', $producto->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Información General --}}
        <div class="bg-[#f6f5f7] shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold text-[#3B4D9A] mb-4">🧾 Información general</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Nombre</label>
                    <input type="text" name="nombre" class="mt-1 w-full border-gray-300 rounded" required value="{{ old('nombre', $producto->nombre) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Código de barras</label>
                    <input type="text" name="codigo_barras" class="mt-1 w-full border-gray-300 rounded" value="{{ old('codigo_barras', $producto->codigo_barras) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Marca</label>
                    <input type="text" name="marca" class="mt-1 w-full border-gray-300 rounded" value="{{ old('marca', $producto->marca) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Unidad de medida</label>
                    <input type="text" name="unidad_medida" class="mt-1 w-full border-gray-300 rounded" value="{{ old('unidad_medida', $producto->unidad_medida) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Cantidad</label>
                    <input type="number" step="0.01" name="cantidad" class="mt-1 w-full border-gray-300 rounded" value="{{ old('cantidad', $producto->cantidad) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Categoría</label>
                    <input type="text" name="categoria" class="mt-1 w-full border-gray-300 rounded" value="{{ old('categoria', $producto->categoria) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Descripción breve</label>
                    <input type="text" name="descripcion_breve" class="mt-1 w-full border-gray-300 rounded" value="{{ old('descripcion_breve', $producto->descripcion_breve) }}">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-[#3B4D9A]">Descripción larga</label>
                    <textarea name="descripcion_larga" rows="3" class="mt-1 w-full border-gray-300 rounded">{{ old('descripcion_larga', $producto->descripcion_larga) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Precios --}}
        <div class="bg-[#f6f5f7] shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold text-[#3B4D9A] mb-4">💰 Precios</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Precio de compra</label>
                    <input type="text" name="precio_compra" class="mt-1 w-full border-gray-300 rounded" value="{{ old('precio_compra', $producto->precio_compra) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Precio de venta</label>
                    <input type="text" name="precio_venta" class="mt-1 w-full border-gray-300 rounded" value="{{ old('precio_venta', $producto->precio_venta) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Precio promocional</label>
                    <input type="text" name="precio_promocional" class="mt-1 w-full border-gray-300 rounded" value="{{ old('precio_promocional', $producto->precio_promocional) }}">
                </div>
            </div>
            <div class="mt-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="activar_oferta" class="rounded border-gray-300 text-[#3B4D9A]"
                        {{ old('activar_oferta', $producto->activar_oferta) ? 'checked' : '' }}>
                    <span class="text-sm text-[#3B4D9A]">Activar oferta</span>
                </label>
            </div>
        </div>

        {{-- Inventario --}}
        <div class="bg-[#f6f5f7] shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold text-[#3B4D9A] mb-4">📦 Inventario</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Stock</label>
                    <input type="number" name="stock" class="mt-1 w-full border-gray-300 rounded" value="{{ old('stock', $producto->stock) }}">
                </div>
                <div>
                    <label class="text-sm font-medium text-[#3B4D9A]">Stock mínimo</label>
                    <input type="number" name="stock_minimo" class="mt-1 w-full border-gray-300 rounded" value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                </div>
            </div>
            <div class="mt-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="controla_inventario" class="rounded border-gray-300 text-[#3B4D9A]"
                        {{ old('controla_inventario', $producto->controla_inventario) ? 'checked' : '' }}>
                    <span class="text-sm text-[#3B4D9A]">Controla inventario</span>
                </label>
            </div>
        </div>

        {{-- Visibilidad --}}
        <div class="bg-[#f6f5f7] shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold text-[#3B4D9A] mb-4">👁️ Visibilidad del producto</h3>
            <div class="space-y-3">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="estado_publicado" class="rounded border-gray-300 text-[#3B4D9A]"
                        {{ old('estado_publicado', $producto->estado_publicado) ? 'checked' : '' }}>
                    <span class="text-sm text-[#3B4D9A]">Publicado</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="mostrar_en_catalogo" class="rounded border-gray-300 text-[#3B4D9A]"
                        {{ old('mostrar_en_catalogo', $producto->mostrar_en_catalogo) ? 'checked' : '' }}>
                    <span class="text-sm text-[#3B4D9A]">Mostrar en catálogo</span>
                </label>
            </div>
        </div>

        {{-- Imagen principal --}}
        @if($producto->imagen)
        <div class="mt-6">
            <label class="text-sm font-medium text-[#3B4D9A] block mb-2">📸 Imagen actual:</label>
            <div class="relative w-full max-w-xs rounded overflow-hidden border border-gray-300 shadow group">
                <img src="{{ $producto->imagen }}"
                    alt="Imagen actual del producto"
                    class="object-cover w-full h-40 transition-transform duration-300 group-hover:scale-105 rounded">
            </div>
        </div>
        @endif

        <div>
            <label class="text-sm font-medium text-[#3B4D9A]">Nueva imagen (opcional)</label>
            <input type="file" name="imagen" class="mt-1 block w-full text-sm border border-gray-300 rounded">
        </div>

        <div>
            <label class="text-sm font-medium text-[#3B4D9A]">Agregar nuevas imágenes</label>
            <input type="file" name="imagenes[]" multiple class="mt-1 block w-full text-sm border border-gray-300 rounded">
        </div>

        <div class="text-right pt-6">
            <a href="{{ route('producto.panel', ['id' => $empresa->id]) }}"
                class="inline-block px-5 py-2 text-sm text-[#3B4D9A] border border-gray-300 rounded hover:bg-gray-50 transition">Cancelar</a>
            <button type="submit"
                class="inline-block px-5 py-2 ml-3 text-sm font-semibold bg-[#3B4D9A] text-white rounded hover:bg-[#2e3f84] transition">
                💾 Guardar cambios
            </button>
        </div>
    </form>

    {{-- Galería --}}
    @if($producto->imagenes && $producto->imagenes->count())
    <div class="mt-10">
        <h4 class="text-lg font-semibold text-[#3B4D9A] mb-4">🖼️ Galería de Imágenes</h4>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($producto->imagenes as $img)
            <div class="relative group overflow-hidden rounded-lg border border-gray-300 shadow bg-white">
                <img src="{{ $img->ruta }}"
                    alt="Imagen del producto"
                    class="object-cover w-full h-32 transition-transform duration-300 group-hover:scale-105">

                <form action="{{ route('producto.imagen.eliminar', $img->id) }}" method="POST"
                    class="absolute top-1 right-1 z-10">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow"
                        title="Eliminar imagen">
                        ✕
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
