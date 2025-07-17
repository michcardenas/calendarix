@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-3">🆕 Añadir nuevo producto</h2>
    <p class="text-muted">Completa la información del producto para registrarlo en tu catálogo.</p>

    <form action="{{ route('producto.store') }}" method="POST" id="form_producto_crear" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            {{-- Panel izquierdo: información --}}
            <div class="col-lg-8">

                {{-- Información básica --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white fw-semibold fs-5">📄 Información básica</div>
                    <div class="card-body row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nombre del producto</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Código de barras</label>
                            <input type="text" name="codigo_barras" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unidad de medida</label>
                            <select name="unidad_medida" class="form-control">
                                <option value="unidad">Unidad</option>
                                <option value="ml">Mililitros (ml)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="lt">Litros (lt)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cantidad</label>
                            <input type="number" step="0.01" name="cantidad" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Descripción breve</label>
                            <input type="text" name="descripcion_breve" maxlength="100" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Descripción detallada</label>
                            <textarea name="descripcion_larga" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="categoria" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Precios --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white fw-semibold fs-5">💰 Precios</div>
                    <div class="card-body row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Precio de compra</label>
                            <input type="text" name="precio_compra" class="form-control" inputmode="numeric">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio de venta</label>
                            <input type="text" name="precio_venta" class="form-control" inputmode="numeric">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio promocional</label>
                            <input type="text" name="precio_promocional" class="form-control" inputmode="numeric">
                        </div>
                        <div class="col-md-12 form-check form-switch ps-4">
                            <input type="checkbox" name="activar_oferta" class="form-check-input" id="activar_oferta_producto">
                            <label for="activar_oferta_producto" class="form-check-label">Activar oferta</label>
                        </div>
                    </div>
                </div>

                {{-- Inventario --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white fw-semibold fs-5">📦 Inventario</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stock mínimo</label>
                            <input type="number" name="stock_minimo" class="form-control">
                        </div>
                        <div class="col-md-12 form-check form-switch ps-4">
                            <input type="checkbox" name="controla_inventario" class="form-check-input" id="controla_inventario_producto">
                            <label for="controla_inventario_producto" class="form-check-label">Controla inventario</label>
                        </div>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white fw-semibold fs-5">👁️ Visibilidad</div>
                    <div class="card-body row ps-4">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" name="estado_publicado" class="form-check-input" id="estado_publicado_producto" checked>
                            <label for="estado_publicado_producto" class="form-check-label">Publicado</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="mostrar_en_catalogo" class="form-check-input" id="mostrar_catalogo_producto" checked>
                            <label for="mostrar_catalogo_producto" class="form-check-label">Mostrar en catálogo</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">💾 Guardar producto</button>
                </div>
            </div>

            {{-- Panel derecho: imágenes --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white fw-semibold fs-5">🖼️ Imágenes del producto</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Subir imágenes</label>
                            <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*" onchange="previewMultipleImages(event)">
                        </div>

                        <div id="preview-container" class="d-flex flex-wrap gap-2 mt-3"></div>
                    </div>
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
            img.className = 'img-thumbnail rounded';
            img.style.height = '120px';
            img.style.objectFit = 'cover';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
