@extends('layouts.empresa')

@section('content')
<style>
    .content-area {
        margin-left: 14rem;
    }
</style>
<div class="container-fluid px-4 py-4">
    <h3 class="mb-4 fw-bold">Añadir un nuevo producto</h3>

    <form action="{{ route('producto.store') }}" method="POST" id="form_producto_crear" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- Información básica --}}
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold">Información básica</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nombre_producto" class="form-label">Nombre del producto</label>
                            <input type="text" name="nombre" id="nombre_producto" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="codigo_barras_producto" class="form-label">Código de barras</label>
                            <input type="text" name="codigo_barras" id="codigo_barras_producto" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="marca_producto" class="form-label">Marca del producto</label>
                            <input type="text" name="marca" id="marca_producto" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unidad_medida_producto" class="form-label">Unidad de medida</label>
                                <select name="unidad_medida" id="unidad_medida_producto" class="form-control">
                                    <option value="unidad">Unidad</option>
                                    <option value="ml">Mililitros (ml)</option>
                                    <option value="g">Gramos (g)</option>
                                    <option value="kg">Kilogramos (kg)</option>
                                    <option value="lt">Litros (lt)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cantidad_producto" class="form-label">Cantidad</label>
                                <input type="number" step="0.01" name="cantidad" id="cantidad_producto" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_breve_producto" class="form-label">Breve descripción</label>
                            <input type="text" name="descripcion_breve" id="descripcion_breve_producto" maxlength="100" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_larga_producto" class="form-label">Descripción detallada</label>
                            <textarea name="descripcion_larga" id="descripcion_larga_producto" rows="4" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="categoria_producto" class="form-label">Categoría del producto</label>
                            <input type="text" name="categoria" id="categoria_producto" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Precios --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold">Precios</div>
                    <div class="card-body row">
                        <div class="col-md-4 mb-3">
                            <label for="precio_compra_producto" class="form-label">Precio de compra</label>
                            <input type="number" name="precio_compra" id="precio_compra_producto" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="precio_venta_producto" class="form-label">Precio de venta</label>
                            <input type="number" name="precio_venta" id="precio_venta_producto" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="precio_promocional_producto" class="form-label">Precio promocional</label>
                            <input type="number" name="precio_promocional" id="precio_promocional_producto" class="form-control">
                        </div>

                        <div class="col-md-12 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activar_oferta_producto" name="activar_oferta">
                            <label class="form-check-label" for="activar_oferta_producto">Activar oferta</label>
                        </div>
                    </div>
                </div>

                {{-- Inventario --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold">Inventario</div>
                    <div class="card-body row">
                        <div class="col-md-6 form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="controla_inventario_producto" name="controla_inventario">
                            <label class="form-check-label" for="controla_inventario_producto">Controlar inventario</label>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock_producto" class="form-label">Stock actual</label>
                            <input type="number" name="stock" id="stock_producto" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock_minimo_producto" class="form-label">Stock mínimo</label>
                            <input type="number" name="stock_minimo" id="stock_minimo_producto" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold">Visibilidad</div>
                    <div class="card-body row">
                        <div class="col-md-6 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="estado_publicado_producto" name="estado_publicado" checked>
                            <label class="form-check-label" for="estado_publicado_producto">Publicado</label>
                        </div>

                        <div class="col-md-6 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="mostrar_catalogo_producto" name="mostrar_en_catalogo" checked>
                            <label class="form-check-label" for="mostrar_catalogo_producto">Mostrar en catálogo</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Guardar producto</button>
                </div>
            </div>

           {{-- Fotos del producto --}}
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">Foto del producto</div>
                    <div class="card-body text-center">

                        {{-- Vista previa si ya existe (al editar) --}}
                        @if(isset($producto) && $producto->imagen)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Foto actual" class="img-fluid rounded mb-2" style="max-height: 200px;">
                                <p class="text-muted">Imagen actual</p>
                            </div>
                        @endif

                        {{-- Campo para cargar nueva imagen --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subir imágenes</label>
                            <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
                        </div>

                        {{-- Vista previa en tiempo real --}}
                        <div id="preview-container" class="border rounded py-3 text-muted" style="background-color: #f8f9fa; display: none;">
                            <img id="preview" src="#" class="img-fluid rounded" style="max-height: 200px;">
                            <p class="mt-2">Vista previa</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/catalogo/producto.js') }}"></script>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const container = document.getElementById('preview-container');

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            container.style.display = 'none';
        }
    }
</script>

@endpush
