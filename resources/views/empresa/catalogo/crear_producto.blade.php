@extends('layouts.empresa')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <h2 class="text-2xl font-bold text-indigo-600 mb-1">🆕 Añadir nuevo producto</h2>
    <p class="text-gray-500 mb-6 text-sm">Completa la información del producto para registrarlo en tu catálogo.</p>

    <form action="{{ route('producto.store') }}" method="POST" enctype="multipart/form-data" id="form_producto_crear">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Panel izquierdo (col-span-2) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Información básica --}}
                <div class="bg-white rounded-xl shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">📄 Información básica</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600">Nombre del producto</label>
                            <input type="text" name="nombre" class="w-full mt-1 rounded border-gray-300 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Código de barras</label>
                            <input type="text" name="codigo_barras" class="w-full mt-1 rounded border-gray-300">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Marca</label>
                            <input type="text" name="marca" class="w-full mt-1 rounded border-gray-300">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Unidad de medida</label>
                            <select name="unidad_medida" class="w-full mt-1 rounded border-gray-300">
                                <option value="unidad">Unidad</option>
                                <option value="ml">Mililitros (ml)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="lt">Litros (lt)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Cantidad</label>
                            <input type="number" step="0.01" name="cantidad" class="w-full mt-1 rounded border-gray-300">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Descripción breve</label>
                            <input type="text" name="descripcion_breve" maxlength="100" class="w-full mt-1 rounded border-gray-300">
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600">Descripción detallada</label>
                            <textarea name="descripcion_larga" rows="3" class="w-full mt-1 rounded border-gray-300"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Categoría</label>
                            <input type="text" name="categoria" class="w-full mt-1 rounded border-gray-300">
                        </div>
                    </div>
                </div>

                {{-- Precios --}}
                <div class="bg-white rounded-xl shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">💰 Precios</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Precio de compra</label>
                            <input type="text" name="precio_compra" class="w-full mt-1 rounded border-gray-300" inputmode="numeric">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Precio de venta</label>
                            <input type="text" name="precio_venta" class="w-full mt-1 rounded border-gray-300" inputmode="numeric">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Precio promocional</label>
                            <input type="text" name="precio_promocional" class="w-full mt-1 rounded border-gray-300" inputmode="numeric">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="activar_oferta" class="rounded border-gray-300 text-indigo-600">
                            <span class="text-sm text-gray-700">Activar oferta</span>
                        </label>
                    </div>
                </div>

                {{-- Inventario --}}
                <div class="bg-white rounded-xl shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">📦 Inventario</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Stock</label>
                            <input type="number" name="stock" class="w-full mt-1 rounded border-gray-300">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Stock mínimo</label>
                            <input type="number" name="stock_minimo" class="w-full mt-1 rounded border-gray-300">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="controla_inventario" class="rounded border-gray-300 text-indigo-600">
                            <span class="text-sm text-gray-700">Controla inventario</span>
                        </label>
                    </div>
                </div>

                {{-- Visibilidad --}}
                <div class="bg-white rounded-xl shadow p-6 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">👁️ Visibilidad</h3>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="estado_publicado" class="rounded border-gray-300 text-indigo-600" checked>
                        <span class="text-sm text-gray-700">Publicado</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="mostrar_en_catalogo" class="rounded border-gray-300 text-indigo-600" checked>
                        <span class="text-sm text-gray-700">Mostrar en catálogo</span>
                    </label>
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-6 py-2 rounded shadow transition">
                        💾 Guardar producto
                    </button>
                </div>

            </div>

            {{-- Panel derecho: imágenes --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">🖼️ Imágenes del producto</h3>
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">Subir imágenes</label>
                        <input type="file" name="imagenes[]" multiple accept="image/*"
                               class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                               onchange="previewMultipleImages(event)">
                    </div>
                    <div id="preview-container" class="mt-4 grid grid-cols-2 gap-4"></div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewMultipleImages(event) {
    const container = document.getElementById('preview-container');
    container.innerHTML = '';

    Array.from(event.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'rounded shadow border border-gray-300';
            img.style.height = '120px';
            img.style.objectFit = 'cover';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
