@extends('layouts.empresa')

@section('title', 'Citas')

@section('content')
<style>
    /* ===========================
       Calendarix Design Tokens
       =========================== */
    :root {
        /* Colores principales */
        --clx-primary: #455392;
        /* Azul profundo */
        --clx-primary-dark: #3a3e6b;
        /* Más oscuro */
        --clx-primary-light: #7e79c9;
        /* Violeta pastel elegante */

        /* Fondo y acento */
        --clx-secondary: #f6f5f7;
        /* Fondo claro general */
        --clx-accent: #a49ee8;
        /* Acento suave */

        /* Estados */
        --clx-danger: #e74c3c;
        --clx-warning: #f1c40f;
        --clx-dark: #2d2d46;
        /* Texto titular / fuerte */
        --clx-light: #ffffff;

        /* Textos */
        --clx-text: #374151;
        --clx-text-light: #9c9cb9;

        /* Bordes y sombras */
        --clx-border: #dad8ee;
        --clx-shadow: 0 4px 6px -1px rgba(126, 121, 201, 0.1);
        --clx-shadow-lg: 0 10px 15px -3px rgba(126, 121, 201, 0.15);

        /* Estética */
        --clx-radius: 12px;
        --clx-radius-sm: 8px;
        --clx-transition: all 0.3s ease;
    }

    /* ===========================
       Base / Helpers
       =========================== */
    body {
        background: var(--clx-secondary);
        color: var(--clx-text);
    }

    .clx-title {
        color: var(--clx-dark);
    }

    .text-muted {
        color: var(--clx-text-light) !important;
    }

    .clx-card {
        background: var(--clx-light);
        border: 1px solid var(--clx-border);
        border-radius: var(--clx-radius);
        box-shadow: var(--clx-shadow);
    }

    /* Inputs & Selects */
    .clx-input,
    .clx-select {
        border: 1px solid var(--clx-border);
        color: var(--clx-text);
        background: var(--clx-light);
        border-radius: var(--clx-radius-sm);
        transition: var(--clx-transition);
    }

    .clx-input:focus,
    .clx-select:focus {
        border-color: var(--clx-accent);
        box-shadow: 0 0 0 0.2rem rgba(164, 158, 232, 0.25);
    }

    /* Buttons */
    .btn-clx-primary {
        background: var(--clx-primary);
        color: #fff;
        border: 1px solid var(--clx-primary);
        border-radius: var(--clx-radius-sm);
        transition: var(--clx-transition);
    }

    .btn-clx-primary:hover {
        background: var(--clx-primary-dark);
        border-color: var(--clx-primary-dark);
    }

    .btn-clx-outline {
        background: transparent;
        color: var(--clx-primary);
        border: 1px solid var(--clx-primary);
        border-radius: var(--clx-radius-sm);
        transition: var(--clx-transition);
    }

    .btn-clx-outline:hover {
        background: var(--clx-primary);
        color: #fff;
    }

    /* Tabla */
    .clx-table thead th {
        background: var(--clx-primary);
        color: #fff;
        border: none;
    }

    .clx-table tbody tr:hover {
        background: rgba(164, 158, 232, 0.07);
    }

    .clx-table td,
    .clx-table th {
        border-color: var(--clx-border) !important;
    }

    /* Badges por estado */
    .badge-state {
        border-radius: 999px;
        padding: .4rem .6rem;
        font-weight: 600;
    }

    .badge-pendiente {
        background: var(--clx-warning);
        color: #2d2d2d;
    }

    .badge-confirmada {
        background: var(--clx-accent);
        color: #fff;
    }

    .badge-cancelada {
        background: var(--clx-danger);
        color: #fff;
    }

    .badge-completada {
        background: var(--clx-primary-dark);
        color: #fff;
    }

    /* Paginación */
    .pagination .page-link {
        color: var(--clx-primary);
        border-color: var(--clx-border);
    }

    .pagination .page-link:hover {
        background: var(--clx-secondary);
    }

    .pagination .active .page-link {
        background: var(--clx-primary);
        border-color: var(--clx-primary);
    }

    /* Modales Calendarix */
    .clx-modal .modal-content {
        border: 1px solid var(--clx-border);
        border-radius: var(--clx-radius);
        box-shadow: var(--clx-shadow-lg);
    }

    .clx-modal .modal-header {
        background: var(--clx-primary);
        color: #fff;
        border-bottom: none;
    }

    .clx-modal .modal-title {
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 m-0 clx-title">Citas (Empresa #{{ $id }})</h1>
        <a href="{{ route('empresa.configuracion.citas', $id) }}" class="btn btn-clx-outline btn-sm">
            <i class="bi bi-arrow-repeat"></i> Limpiar
        </a>
    </div>

    <div class="clx-card p-3 mb-3">
        <form method="GET" action="{{ route('empresa.configuracion.citas', $id) }}" class="row g-2">
            <div class="col-12 col-md-4">
<input type="text" name="q" value="{{ request('q') }}" class="form-control clx-input"
       placeholder="Buscar: cliente, notas...">
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="desde" value="{{ request('desde') }}" class="form-control clx-input">
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control clx-input">
            </div>
            <div class="col-12 col-md-2">
                <select name="estado[]" class="form-select clx-select" multiple>
                    @foreach($estados as $e)
                    <option value="{{ $e }}" @selected(collect(request('estado'))->contains($e))>
                        {{ ucfirst($e) }}
                    </option>
                    @endforeach
                </select>
                <small class="text-muted">Ctrl/Cmd para multi-selección</small>
            </div>
            <div class="col-6 col-md-2">
                <select name="per_page" class="form-select clx-select">
                    @foreach([15,25,50,100] as $pp)
                    <option value="{{ $pp }}" @selected((int)request('per_page',15)===$pp)>{{ $pp }} / pág</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-12">
                <button class="btn btn-clx-primary"><i class="bi bi-search"></i> Filtrar</button>
            </div>
        </form>
    </div>

    <div class="table-responsive clx-card p-0">
        <table class="table table-hover align-middle clx-table mb-0">
<thead>
  <tr>
    <th>Fecha</th>
    <th>Horario</th>
    <th>Cliente</th>
    <th>Estado</th>
    <th>Notas</th>
    <th class="text-end">Acciones</th>
  </tr>
</thead>
<tbody>
@forelse($citas as $cita)
  <tr>
    {{-- Fecha --}}
    <td>
      <div class="fw-semibold">{{ optional($cita->fecha)->format('Y-m-d') }}</div>
    </td>

    {{-- Horas --}}
    <td>
      <div>
        <span class="text-muted">Inicio:</span>
        {{ $cita->hora_inicio ? \Illuminate\Support\Str::limit($cita->hora_inicio, 5, '') : '—' }}
      </div>
      <div>
        <span class="text-muted">Fin:</span>
        {{ $cita->hora_fin ? \Illuminate\Support\Str::limit($cita->hora_fin, 5, '') : '—' }}
      </div>
    </td>

    {{-- Cliente --}}
    <td>
      <div class="fw-semibold">{{ $cita->nombre_cliente ?? '—' }}</div>
      <small class="text-muted">ID usuario: {{ $cita->user_id ?? '—' }}</small>
    </td>

    {{-- Estado --}}
    <td>
      @php
        $badgeClass = [
          'pendiente'  => 'badge-pendiente',
          'confirmada' => 'badge-confirmada',
          'cancelada'  => 'badge-cancelada',
          'completada' => 'badge-completada',
        ][$cita->estado] ?? 'badge-pendiente';
      @endphp
      <span class="badge badge-state {{ $badgeClass }}">{{ ucfirst($cita->estado) }}</span>
    </td>

    {{-- Notas --}}
    <td class="text-truncate" style="max-width: 260px;">
      {{ \Illuminate\Support\Str::limit($cita->notas, 80) ?? '—' }}
    </td>

    {{-- Acciones (abre modales) --}}
    <td class="text-end">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-clx-outline"
                data-bs-toggle="modal" data-bs-target="#citaDetalle-{{ $cita->id }}">
          <i class="bi bi-eye"></i>
        </button>
        <button type="button" class="btn btn-sm btn-clx-outline"
                data-bs-toggle="modal" data-bs-target="#citaEstado-{{ $cita->id }}">
          <i class="bi bi-arrow-repeat"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger"
                data-bs-toggle="modal" data-bs-target="#citaEliminar-{{ $cita->id }}">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    </td>
  </tr>
@empty
  <tr><td colspan="6" class="text-center py-5 text-muted">Sin resultados</td></tr>
@endforelse
</tbody>
        </table>
    </div>

    {{-- Modales por cada cita de la página actual --}}
    @foreach ($citas as $cita)
    @include('empresa.configuracion.citas.modals._detalle', ['cita' => $cita, 'id' => $id])
    @include('empresa.configuracion.citas.modals._estado', ['cita' => $cita, 'id' => $id])
    @include('empresa.configuracion.citas.modals._eliminar', ['cita' => $cita, 'id' => $id])
    @endforeach

    <div class="d-flex justify-content-end mt-3">
        {{ $citas->links() }}
    </div>
</div>
@endsection