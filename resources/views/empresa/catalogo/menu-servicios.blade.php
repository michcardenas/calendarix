@extends('layouts.empresa')

@section('content')

{{-- AlpineJS para dropdowns --}}
<script src="//unpkg.com/alpinejs" defer></script>

@php
    $categoriasSistema = [
        ['icon' => 'fa-scissors', 'nombre' => 'Peluquería'],
        ['icon' => 'fa-hand-sparkles', 'nombre' => 'Salón de uñas'],
        ['icon' => 'fa-eye', 'nombre' => 'Cejas y pestañas'],
        ['icon' => 'fa-user-alt', 'nombre' => 'Salón de belleza'],
        ['icon' => 'fa-spa', 'nombre' => 'Spa y sauna'],
        ['icon' => 'fa-heartbeat', 'nombre' => 'Centro estético'],
        ['icon' => 'fa-cut', 'nombre' => 'Barbería'],
        ['icon' => 'fa-dog', 'nombre' => 'Peluquería mascotas'],
        ['icon' => 'fa-user-nurse', 'nombre' => 'Clínica'],
        ['icon' => 'fa-biking', 'nombre' => 'Fitness'],
        ['icon' => 'fa-ellipsis-h', 'nombre' => 'Otros'],
    ];

    $negocio = \App\Models\Negocio::where('user_id', auth()->id())->first();
    $categoriasUsuario = [];

    if ($negocio && $negocio->neg_categorias) {
        $categoriasUsuario = json_decode($negocio->neg_categorias, true) ?? [];
    }
@endphp

<div class="min-h-screen px-6 py-10 text-gray-800">
    <div class="max-w-7xl mx-auto space-y-10">

        {{-- Encabezado --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-3xl font-bold text-[#7E79C9]">🛠️ Menú de servicios</h1>
            <button class="px-4 py-2 text-sm font-medium border border-[#7E79C9] text-[#7E79C9] rounded-md bg-white hover:bg-[#455392] hover:transition"
                    data-bs-toggle="modal" data-bs-target="#modalNuevoServicio">
                <i class="fas fa-plus mr-2"></i> Añadir servicio
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            {{-- Sidebar categorías --}}
            <div class="md:col-span-3">
                <h5 class="text-lg font-semibold mb-3 text-gray-700">Categorías</h5>
                <ul class="space-y-2">
                    <li class="flex justify-between items-center bg-[#7E79C9] text-white px-4 py-2 rounded-md shadow">
                        <span>Todas las categorías</span>
                        <span class="text-sm bg-white text-[#7E79C9] px-2 rounded-full">{{ $servicios->count() }}</span>
                    </li>

                    @foreach($serviciosPorCategoria as $cat => $items)
                        <li class="flex justify-between items-center bg-white px-4 py-2 rounded-md border border-gray-200 hover:bg-gray-50">
                            <span>{{ $cat }}</span>
                            <span class="text-sm bg-gray-100 px-2 rounded-full">{{ $items->count() }}</span>
                        </li>
                    @endforeach

                    @foreach($categoriasUsuario as $cat)
                        @if(!isset($serviciosPorCategoria[$cat]))
                            <li class="flex justify-between items-center bg-white px-4 py-2 rounded-md border border-dashed border-gray-300 hover:bg-gray-50">
                                <span>{{ $cat }}</span>
                                <span class="text-sm text-gray-400">(0)</span>
                            </li>
                        @endif
                    @endforeach

                    <li>
                        <button class="text-[#7E79C9] text-sm underline hover:font-semibold mt-2"
                                data-bs-toggle="modal" data-bs-target="#modalCategoria">
                            + Añadir categoría
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Lista de servicios --}}
            <div class="md:col-span-9">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @forelse($serviciosPorCategoria as $categoria => $servicios)
                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-[#7E79C9] mb-4">{{ $categoria }}</h3>

                        <div class="space-y-3">
                            @foreach($servicios as $servicio)
                                <div class="flex justify-between items-center bg-white p-4 rounded-lg border-l-4 border-[#7E79C9] shadow-sm hover:shadow-md transition">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $servicio->nombre }}</h4>
                                        <p class="text-sm text-gray-500">{{ $servicio->duracion ?? '15 min' }}</p>
                                    </div>
                                    <div class="text-right space-y-1">
                                        <div class="font-semibold text-gray-800">{{ number_format($servicio->precio, 0, ',', '.') }} COP</div>

                                        {{-- Dropdown de acciones --}}
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open" type="button" class="text-gray-600 hover:text-[#7E79C9]">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>

                                            <div x-show="open" @click.outside="open = false" x-transition
                                                 class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-50">
                                                <button class="block px-4 py-2 text-sm hover:bg-gray-100 w-full text-left"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarServicio{{ $servicio->id }}">
                                                    Editar
                                                </button>
                                                <form method="POST" action="{{ route('servicios.duplicar', $servicio->id) }}">
                                                    @csrf
                                                    <button type="submit" class="block px-4 py-2 text-sm hover:bg-gray-100 w-full text-left">Duplicar</button>
                                                </form>
                                                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Reserva rápida</a>
                                                <hr class="my-1">
                                                <form method="POST" action="{{ route('servicios.eliminar', $servicio->id) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100 w-full text-left">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal editar individual --}}
                                @include('empresa.partials.modal-editar-servicio', ['servicio' => $servicio])
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No hay servicios registrados aún.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modales globales --}}
@include('empresa.partials.modal-nuevo-servicio')
@include('empresa.partials.modal-nueva-categoria')

@endsection
