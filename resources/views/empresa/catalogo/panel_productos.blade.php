@extends('layouts.empresa')

@section('content')
<div class="px-10 py-10">

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-600 mb-1">📦 Productos</h2>
            <p class="text-gray-500 text-sm">Consulta y administra los productos registrados en tu catálogo.</p>
        </div>
        <a href="{{ route('producto.crear') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded shadow hover:bg-indigo-700 transition">
            <i class="bi bi-plus-circle mr-2"></i> Nuevo producto
        </a>
    </div>

    {{-- Contenido --}}
    @if($productos->isEmpty())
    <div class="bg-indigo-50 text-indigo-700 px-4 py-3 rounded shadow text-center text-sm flex items-center justify-center gap-2">
        <i class="bi bi-box-seam-fill"></i> No hay productos registrados.
    </div>
    @else
    <div class="overflow-x-auto bg-white rounded-lg shadow ring-1 ring-gray-200">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 text-indigo-700 uppercase text-xs font-semibold border-b">
                <tr>
                    <th class="px-4 py-3 w-[100px]">Imagen</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Código</th>
                    <th class="px-4 py-3">Marca</th>
                    <th class="px-4 py-3">Precio venta</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($productos as $producto)
                <tr>
                    <td class="px-4 py-3">
                        @if($producto->imagenes->first())
                        <img src="{{ $producto->imagenes->first()->ruta }}"
                            alt="Imagen del producto"
                            class="h-16 w-24 object-cover rounded shadow-sm border border-gray-200">
                        @else
                        <span class="text-gray-400 text-xs">Sin imagen</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $producto->nombre }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $producto->codigo_barras ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $producto->marca ?? '—' }}</td>
                    <td class="px-4 py-3 font-semibold text-indigo-600">
                        ${{ number_format($producto->precio_venta, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">{{ $producto->stock ?? 0 }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('producto.editar', $producto->id) }}"
                            class="inline-flex items-center justify-center px-2 py-1 text-indigo-600 border border-indigo-300 rounded hover:bg-indigo-50 transition text-xs shadow-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('producto.eliminar', $producto->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center px-2 py-1 text-red-600 border border-red-300 rounded hover:bg-red-50 transition text-xs shadow-sm">
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