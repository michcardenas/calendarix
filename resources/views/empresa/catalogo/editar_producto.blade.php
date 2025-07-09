@extends('layouts.empresa')

@section('content')
<style>
    .content-area {
        margin-left: 14rem;
    }
</style>
<div class="container mt-4">
    <h4 class="mb-4">Editar Producto</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Corrige los siguientes errores:<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('producto.actualizar', $producto->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">Información del Producto</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="prod_nombre" class="form-label">Nombre</label>
                        <input type="text" id="prod_nombre" name="nombre" class="form-control prod_nombre"
                            value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="prod_codigo" class="form-label">Código de barras</label>
                        <input type="text" id="prod_codigo" name="codigo_barras" class="form-control prod_codigo"
                            value="{{ old('codigo_barras', $producto->codigo_barras) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="prod_marca" class="form-label">Marca</label>
                        <input type="text" id="prod_marca" name="marca" class="form-control prod_marca"
                            value="{{ old('marca', $producto->marca) }}">
                    </div>

                    <div class="col-md-3">
                        <label for="prod_unidad" class="form-label">Unidad de medida</label>
                        <input type="text" id="prod_unidad" name="unidad_medida" class="form-control prod_unidad"
                            value="{{ old('unidad_medida', $producto->unidad_medida) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="prod_cantidad" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" id="prod_cantidad" name="cantidad" class="form-control prod_cantidad"
                            value="{{ old('cantidad', $producto->cantidad) }}">
                    </div>

                    <div class="col-md-12">
                        <label for="prod_desc_breve" class="form-label">Descripción breve</label>
                        <input type="text" id="prod_desc_breve" name="descripcion_breve" class="form-control prod_desc_breve"
                            value="{{ old('descripcion_breve', $producto->descripcion_breve) }}">
                    </div>

                    <div class="col-md-12">
                        <label for="prod_desc_larga" class="form-label">Descripción larga</label>
                        <textarea id="prod_desc_larga" name="descripcion_larga" class="form-control prod_desc_larga"
                            rows="4">{{ old('descripcion_larga', $producto->descripcion_larga) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="prod_categoria" class="form-label">Categoría</label>
                        <input type="text" id="prod_categoria" name="categoria" class="form-control prod_categoria"
                            value="{{ old('categoria', $producto->categoria) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="prod_precio_compra" class="form-label">Precio de compra</label>
                        <input type="number" step="0.01" id="prod_precio_compra" name="precio_compra" class="form-control prod_precio_compra"
                            value="{{ old('precio_compra', $producto->precio_compra) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="prod_precio_venta" class="form-label">Precio de venta</label>
                        <input type="number" step="0.01" id="prod_precio_venta" name="precio_venta" class="form-control prod_precio_venta"
                            value="{{ old('precio_venta', $producto->precio_venta) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="prod_precio_promocional" class="form-label">Precio promocional</label>
                        <input type="number" step="0.01" id="prod_precio_promocional" name="precio_promocional" class="form-control prod_precio_promocional"
                            value="{{ old('precio_promocional', $producto->precio_promocional) }}">
                    </div>

                    <div class="col-md-2">
                        <label for="prod_stock" class="form-label">Stock</label>
                        <input type="number" id="prod_stock" name="stock" class="form-control prod_stock"
                            value="{{ old('stock', $producto->stock) }}">
                    </div>

                    <div class="col-md-2">
                        <label for="prod_stock_min" class="form-label">Stock mínimo</label>
                        <input type="number" id="prod_stock_min" name="stock_minimo" class="form-control prod_stock_min"
                            value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                    </div>

                    <div class="col-md-12 d-flex flex-wrap gap-3 mt-3">
                        <div class="form-check">
                            <input class="form-check-input prod_check" type="checkbox" name="activar_oferta" id="prod_oferta"
                                {{ $producto->activar_oferta ? 'checked' : '' }}>
                            <label class="form-check-label" for="prod_oferta">Activar oferta</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input prod_check" type="checkbox" name="controla_inventario" id="prod_inventario"
                                {{ $producto->controla_inventario ? 'checked' : '' }}>
                            <label class="form-check-label" for="prod_inventario">Controla inventario</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input prod_check" type="checkbox" name="estado_publicado" id="prod_publicado"
                                {{ $producto->estado_publicado ? 'checked' : '' }}>
                            <label class="form-check-label" for="prod_publicado">Publicado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input prod_check" type="checkbox" name="mostrar_en_catalogo" id="prod_catalogo"
                                {{ $producto->mostrar_en_catalogo ? 'checked' : '' }}>
                            <label class="form-check-label" for="prod_catalogo">Mostrar en catálogo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Imagen actual (si hay) --}}
        @if($producto->imagen)
            <div class="mb-3">
                <label class="form-label">Imagen actual:</label><br>
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" class="img-thumbnail" style="max-height: 200px;">
            </div>
        @endif

        <div class="mb-3">
            <label for="prod_imagen" class="form-label">Nueva imagen (opcional)</label>
            <input class="form-control prod_imagen" type="file" name="imagen" id="prod_imagen">
        </div>

        {{-- Galería de imágenes asociadas --}}
        @if($producto->imagenes && $producto->imagenes->count())
            <div class="mb-4">
                <label class="form-label">Galería de imágenes</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach($producto->imagenes as $img)
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $img->ruta) }}" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                            
                            {{-- Botón para eliminar imagen --}}
                            <form action="{{ route('producto.imagen.eliminar', $img->id) }}" method="POST" style="position: absolute; top: 4px; right: 4px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-close"></button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Subida de nuevas imágenes --}}
        <div class="mb-3">
            <label for="prod_imagenes" class="form-label">Agregar nuevas imágenes</label>
            <input class="form-control prod_imagenes" type="file" name="imagenes[]" id="prod_imagenes" multiple>
        </div>

        {{-- Botones de acción --}}

        <div class="text-end">
            <a href="{{ route('producto.panel') }}" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
