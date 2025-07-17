@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold">✏️ Editar Producto</h2>
        <p class="text-muted">Actualiza los datos de tu producto y gestiona su visibilidad, precios e inventario.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>⚠️ Ocurrió un error:</strong> Revisa los campos marcados.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('producto.actualizar', $producto->id) }}" enctype="multipart/form-data" class="mb-5">
        @csrf
        @method('PUT')

        {{-- DATOS GENERALES --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white fw-semibold fs-5">🧾 Información general</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ old('nombre', $producto->nombre) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Código de barras</label>
                    <input type="text" name="codigo_barras" class="form-control" value="{{ old('codigo_barras', $producto->codigo_barras) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control" value="{{ old('marca', $producto->marca) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unidad de medida</label>
                    <input type="text" name="unidad_medida" class="form-control" required value="{{ old('unidad_medida', $producto->unidad_medida) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cantidad</label>
                    <input type="number" step="0.01" name="cantidad" class="form-control" value="{{ old('cantidad', $producto->cantidad) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria" class="form-control" value="{{ old('categoria', $producto->categoria) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Descripción breve</label>
                    <input type="text" name="descripcion_breve" class="form-control" value="{{ old('descripcion_breve', $producto->descripcion_breve) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Descripción larga</label>
                    <textarea name="descripcion_larga" class="form-control" rows="3">{{ old('descripcion_larga', $producto->descripcion_larga) }}</textarea>
                </div>
            </div>
        </div>

        {{-- PRECIOS --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white fw-semibold fs-5">💰 Precios</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Precio de compra</label>
                    <input type="text" name="precio_compra" class="form-control" inputmode="numeric" value="{{ old('precio_compra', $producto->precio_compra) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Precio de venta</label>
                    <input type="text" name="precio_venta" class="form-control" inputmode="numeric" value="{{ old('precio_venta', $producto->precio_venta) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Precio promocional</label>
                    <input type="text" name="precio_promocional" class="form-control" inputmode="numeric" value="{{ old('precio_promocional', $producto->precio_promocional) }}">
                </div>
                <div class="col-md-12 form-check form-switch ps-4 mt-2">
                    <input class="form-check-input" type="checkbox" id="activar_oferta_producto" name="activar_oferta" {{ old('activar_oferta', $producto->activar_oferta) ? 'checked' : '' }}>
                    <label class="form-check-label" for="activar_oferta_producto">Activar oferta</label>
                </div>
            </div>
        </div>

        {{-- INVENTARIO --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white fw-semibold fs-5">📦 Inventario</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Stock mínimo</label>
                    <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                </div>
                <div class="col-md-12 form-check form-switch ps-4">
                    <input type="checkbox" name="controla_inventario" class="form-check-input" id="controla_inventario" {{ old('controla_inventario', $producto->controla_inventario) ? 'checked' : '' }}>
                    <label class="form-check-label" for="controla_inventario">Controla inventario</label>
                </div>
            </div>
        </div>

        {{-- VISIBILIDAD --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white fw-semibold fs-5">👁️ Visibilidad del producto</div>
            <div class="card-body row ps-4">
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" name="estado_publicado" class="form-check-input" id="estado_publicado" {{ old('estado_publicado', $producto->estado_publicado) ? 'checked' : '' }}>
                    <label for="estado_publicado" class="form-check-label">Publicado</label>
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox" name="mostrar_en_catalogo" class="form-check-input" id="mostrar_en_catalogo" {{ old('mostrar_en_catalogo', $producto->mostrar_en_catalogo) ? 'checked' : '' }}>
                    <label for="mostrar_en_catalogo" class="form-check-label">Mostrar en catálogo</label>
                </div>
            </div>
        </div>

        {{-- IMAGEN PRINCIPAL --}}
        @if($producto->imagen)
            <div class="mb-3">
                <label class="form-label">Imagen actual:</label><br>
                <img src="{{ asset('storage/' . $producto->imagen) }}" class="img-thumbnail rounded" style="max-height: 150px;">
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Nueva imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Agregar nuevas imágenes</label>
            <input type="file" name="imagenes[]" multiple class="form-control">
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('producto.panel') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
        </div>
    </form>

    {{-- GALERÍA --}}
    @if($producto->imagenes && $producto->imagenes->count())
        <div class="mt-5">
            <h5 class="fw-semibold">🖼️ Galería de Imágenes</h5>
            <div class="d-flex flex-wrap gap-3">
                @foreach($producto->imagenes as $img)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $img->ruta) }}" class="img-thumbnail border" style="width: 110px; height: 110px; object-fit: cover;">
                        <form action="{{ route('producto.imagen.eliminar', $img->id) }}" method="POST" style="position: absolute; top: 4px; right: 4px;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger btn-close" title="Eliminar imagen"></button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
