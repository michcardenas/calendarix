@extends('layouts.empresa')

@section('content')
<style>
    .content-area {
        margin-left: 14rem;
    }
</style>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Productos</h4>
        <a href="{{ route('producto.crear') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo producto
        </a>
    </div>

    @if($productos->isEmpty())
        <div class="alert alert-info">No hay productos registrados.</div>
    @else
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Marca</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td>
                        @if($producto->imagenes->first())
                            <img src="{{ asset('storage/' . $producto->imagenes->first()->ruta) }}" class="img-thumbnail" style="max-height: 80px;">
                        @endif
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->codigo_barras }}</td>
                    <td>{{ $producto->marca }}</td>
                    <td>$ {{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('producto.editar', $producto->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <form action="{{ route('producto.eliminar', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
