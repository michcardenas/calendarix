@extends('layouts.empresa')

@section('content')

<style>
    body {
        background-color: #f3f0fa;
    }

    .form-wrapper {
        max-width: 600px;
        background-color: #fff;
        padding: 2rem;
        margin: 2rem auto;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .form-label {
        font-weight: 500;
        color: #333;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 10px 14px;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #5e2b8c;
        outline: none;
        box-shadow: 0 0 0 0.15rem rgba(94, 43, 140, 0.2);
    }

    .btn-guardar {
        background-color: #5e2b8c;
        color: white;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-guardar:hover {
        background-color: #4a1e70;
    }

    .card-servicio {
        transition: box-shadow 0.2s;
        background-color: #fff;
        border-left: 5px solid #5e2b8c;
    }

    .card-servicio:hover {
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
    }

    .badge {
        background-color: #5e2b8c;
        color: white;
    }

    .list-group-item.active {
        background-color: #5e2b8c !important;
        border-color: #5e2b8c !important;
        color: #fff !important;
    }

    .text-primary {
        color: #5e2b8c !important;
    }

    .btn-outline-primary {
        border-color: #5e2b8c;
        color: #5e2b8c;
    }

    .btn-outline-primary:hover {
        background-color: #5e2b8c;
        color: #fff;
    }
</style>

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
@endphp

<div class="ml-[16.25rem]  min-h-screen px-6 py-10 text-gray-800">
    <div class="max-w-7xl mx-auto space-y-10">
        {{-- Encabezado --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-3xl font-bold">🛠️ Menú de servicios</h1>
            <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md bg-white hover:bg-gray-50 transition no-underline" data-bs-toggle="modal" data-bs-target="#modalNuevoServicio">
                <i class="bi bi-plus-lg mr-2"></i> Añadir servicio
            </button>
        </div>

        <div class="grid grid-cols-12 gap-6">
            {{-- Sidebar --}}
            <div class="col-span-12 md:col-span-3">
                <h5 class="mb-3">Categorías</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center active">
                        Todas las categorías
                        <span class="badge">{{ $servicios->count() }}</span>
                    </li>
                    @foreach($serviciosPorCategoria as $cat => $items)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $cat }}
                            <span class="badge bg-secondary">{{ $items->count() }}</span>
                        </li>
                    @endforeach
                    <li class="list-group-item text-primary text-decoration-underline cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                        Añadir categoría
                    </li>
                </ul>
            </div>

            {{-- Servicios --}}
            <div class="col-span-12 md:col-span-9">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @forelse($serviciosPorCategoria as $categoria => $servicios)
                    <div class="mb-8">
                        <h5 class="text-lg font-bold text-[#5e2b8c] mb-3">{{ $categoria }}</h5>
                        @foreach($servicios as $servicio)

                            {{-- Modal editar --}}
                            <div class="modal fade" id="modalEditarServicio{{ $servicio->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <form method="POST" action="{{ route('servicios.actualizar', $servicio->id) }}" class="modal-content">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar servicio</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="col-md-6">
                                                <label class="form-label">Nombre del servicio</label>
                                                <input type="text" name="nombre" value="{{ $servicio->nombre }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Precio</label>
                                                <input type="number" name="precio" value="{{ $servicio->precio }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea name="descripcion" class="form-control" rows="2">{{ $servicio->descripcion }}</textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Categoría</label>
                                                <input type="text" name="categoria" value="{{ $servicio->categoria }}" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Card servicio --}}
                            <div class="card-servicio border rounded p-3 mb-2 d-flex justify-content-between align-items-center shadow-sm">
                                <div>
                                    <div class="fw-bold">{{ $servicio->nombre }}</div>
                                    <small>{{ $servicio->duracion ?? '15 min' }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">{{ number_format($servicio->precio, 0, ',', '.') }} COP</div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEditarServicio{{ $servicio->id }}">Editar</button></li>
                                            <li>
                                                <form action="{{ route('servicios.duplicar', $servicio->id) }}" method="POST">@csrf
                                                    <button type="submit" class="dropdown-item">Duplicar</button>
                                                </form>
                                            </li>
                                            <li><a class="dropdown-item" href="#">Enlace de reserva rápida</a></li>
                                            <li><a class="dropdown-item" href="#">Archivar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('servicios.eliminar', $servicio->id) }}" method="POST">@csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Eliminar permanentemente</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                @empty
                    <p>No hay servicios disponibles aún.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal Nueva Categoría --}}
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('catalogo.categorias.guardar') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Añadir categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Nombre de la categoría</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-guardar">Añadir</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Nuevo Servicio --}}
<div class="modal fade" id="modalNuevoServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('servicios.guardar') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Añadir nuevo servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6">
                    <label class="form-label">Nombre del servicio</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Precio (COP)</label>
                    <input type="text" name="precio" id="precioServicio" class="form-control" required inputmode="numeric">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Duración estimada</label>
                    <input type="text" name="duracion" class="form-control" placeholder="Ej: 30 minutos">
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Selecciona categoría</label>
                    <select name="categoria_existente" class="form-control">
                        <option value="">-- Escoge una existente --</option>
                        @foreach($serviciosPorCategoria as $categoria => $items)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">O escribe una nueva categoría</label>
                    <input type="text" name="categoria_nueva" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-guardar">Guardar servicio</button>
            </div>
        </form>
    </div>
</div>

@endsection
