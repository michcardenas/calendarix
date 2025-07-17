@extends('layouts.empresa')

@section('content')
<div class="container py-5">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-indigo-600">📦 Productos</h2>
            <p class="text-muted mb-0">Consulta y administra los productos registrados en tu catálogo.</p>
        </div>
        <a href="{{ route('producto.crear') }}" class="btn btn-primary px-4 shadow-sm rounded">
            <i class="bi bi-plus-circle me-2"></i> Nuevo producto
        </a>
    </div>

    {{-- Contenido --}}
    @if($productos->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded">
            <i class="bi bi-box-seam-fill me-2"></i>
            No hay productos registrados.
        </div>
    @else
        <div class="table-responsive rounded shadow-sm">
            <table class="table align-middle table-hover border rounded-3 overflow-hidden bg-white">
                <thead class="table-light text-indigo-600">
                    <tr>
                        <th style="width: 100px;">Imagen</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Marca</th>
                        <th>Precio venta</th>
                        <th>Stock</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>
                            @if($producto->imagenes->first())
                                <img src="{{ asset('storage/' . $producto->imagenes->first()->ruta) }}" alt="Imagen del producto"
                                     class="img-thumbnail rounded shadow-sm" style="height: 70px; width: 100px; object-fit: cover;">
                            @else
                                <span class="text-muted small">Sin imagen</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-gray-800">{{ $producto->nombre }}</td>
                        <td class="text-muted">{{ $producto->codigo_barras ?? '—' }}</td>
                        <td class="text-muted">{{ $producto->marca ?? '—' }}</td>
                        <td class="fw-semibold text-indigo-600">$ {{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td>{{ $producto->stock ?? 0 }}</td>
                        <td class="text-end">
                            <a href="{{ route('producto.editar', $producto->id) }}"
                               class="btn btn-sm btn-outline-primary rounded shadow-sm me-2">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('producto.eliminar', $producto->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
