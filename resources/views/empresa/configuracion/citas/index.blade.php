@extends('layouts.empresa')

@section('title', 'Citas')

@section('content')
<style>
    .citas-page { max-width: 1100px; }

    /* Filtros */
    .filtros-card {
        background: #fff;
        border: 1px solid #ece9f8;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 1px 4px rgba(90,49,215,0.06);
    }
    .filtro-input {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 0.82rem;
        color: #374151;
        background: #fff;
        transition: all 0.2s;
        outline: none;
    }
    .filtro-input:focus {
        border-color: #5a31d7;
        box-shadow: 0 0 0 3px rgba(90,49,215,0.1);
    }
    .filtro-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 5px;
        display: block;
    }

    /* Chips de estado para filtro */
    .estado-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    .estado-chip input { display: none; }
    .estado-chip-pendiente { background: #fef9c3; color: #92400e; border-color: #fef9c3; }
    .estado-chip-pendiente.active { border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,0.2); }
    .estado-chip-confirmada { background: #d1fae5; color: #065f46; border-color: #d1fae5; }
    .estado-chip-confirmada.active { border-color: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); }
    .estado-chip-cancelada { background: #fee2e2; color: #991b1b; border-color: #fee2e2; }
    .estado-chip-cancelada.active { border-color: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,0.2); }
    .estado-chip-completada { background: #ede9fe; color: #5b21b6; border-color: #ede9fe; }
    .estado-chip-completada.active { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,0.2); }

    /* Cards de citas */
    .cita-card {
        background: #fff;
        border: 1px solid #f0ecf8;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .cita-card:hover {
        border-color: #5a31d7;
        box-shadow: 0 4px 16px rgba(90,49,215,0.08);
    }
    .cita-fecha {
        text-align: center;
        min-width: 52px;
        flex-shrink: 0;
    }
    .cita-fecha-dia {
        font-size: 1.5rem;
        font-weight: 800;
        color: #5a31d7;
        line-height: 1;
    }
    .cita-fecha-mes {
        font-size: 0.65rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 2px;
    }
    .cita-divider {
        width: 1px;
        height: 48px;
        background: #f0ecf8;
        flex-shrink: 0;
    }
    .cita-info { flex: 1; min-width: 0; }
    .cita-cliente {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cita-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px 14px;
        margin-top: 4px;
        font-size: 0.75rem;
        color: #6b7280;
    }
    .cita-meta i { color: #5a31d7; font-size: 0.65rem; margin-right: 3px; }
    .cita-acciones {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    /* Badge estado */
    .badge-estado {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .badge-estado-pendiente  { background: #fef9c3; color: #92400e; }
    .badge-estado-confirmada { background: #d1fae5; color: #065f46; }
    .badge-estado-cancelada  { background: #fee2e2; color: #991b1b; }
    .badge-estado-completada { background: #ede9fe; color: #5b21b6; }

    /* Botones de accion */
    .btn-accion {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        color: #6b7280;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-accion:hover { border-color: #5a31d7; color: #5a31d7; background: #faf9ff; }
    .btn-accion-danger:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    @media (max-width: 640px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
    .stat-card {
        background: #fff;
        border: 1px solid #ece9f8;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
    }
    .stat-number {
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: 4px;
    }

    /* Filtros grid responsive */
    .filtros-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 12px;
        align-items: end;
    }
    @media (max-width: 768px) { .filtros-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .filtros-grid { grid-template-columns: 1fr; } }

    /* Modales */
    .clx-modal .modal-content { border: 1px solid #ece9f8; border-radius: 16px; box-shadow: 0 20px 40px rgba(90,49,215,0.15); overflow: hidden; }
    .clx-modal .modal-header { background: linear-gradient(135deg, #5a31d7, #7b5ce0); color: #fff; border-bottom: none; padding: 1.25rem 1.5rem; }
    .clx-modal .modal-title { color: #fff; font-weight: 700; }
    .clx-modal .modal-body { padding: 1.5rem; }
    .clx-modal .modal-footer { border-top: 1px solid #f3f4f6; padding: 1rem 1.5rem; }

    /* Pagination */
    .pagination { gap: 4px; }
    .pagination .page-link {
        border: 1px solid #e5e7eb;
        border-radius: 8px !important;
        color: #5a31d7;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 12px;
    }
    .pagination .page-link:hover { background: #faf9ff; border-color: #5a31d7; }
    .pagination .active .page-link { background: #5a31d7; border-color: #5a31d7; color: #fff; }

    /* Responsive cita card */
    @media (max-width: 640px) {
        .cita-card { flex-wrap: wrap; gap: 10px; }
        .cita-divider { display: none; }
        .cita-acciones { width: 100%; justify-content: flex-end; padding-top: 6px; border-top: 1px solid #f3f4f6; }
    }
</style>

<div class="citas-page px-2">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#5a31d7;margin:0;">
                <i class="fas fa-calendar-check" style="margin-right:8px;opacity:0.7;"></i>Citas
            </h1>
            <p style="font-size:0.82rem;color:#9ca3af;margin:4px 0 0 0;">Gestiona las citas de tu negocio.</p>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $totalCitas = $citas->total();
        $pendientes = \App\Models\Cita::where('negocio_id', $id)->where('estado', 'pendiente')->count();
        $confirmadas = \App\Models\Cita::where('negocio_id', $id)->where('estado', 'confirmada')->count();
        $hoy = \App\Models\Cita::where('negocio_id', $id)->whereDate('fecha', today())->count();
    @endphp
    <div class="stats-row" style="margin-bottom:1.25rem;">
        <div class="stat-card">
            <div class="stat-number" style="color:#5a31d7;">{{ $totalCitas }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color:#f59e0b;">{{ $pendientes }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color:#10b981;">{{ $confirmadas }}</div>
            <div class="stat-label">Confirmadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color:#6366f1;">{{ $hoy }}</div>
            <div class="stat-label">Hoy</div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="filtros-card" style="margin-bottom:1.25rem;">
        <form method="GET" action="{{ route('empresa.configuracion.citas', $id) }}">
            <div class="filtros-grid">
                <div>
                    <label class="filtro-label">Buscar</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="filtro-input"
                           placeholder="Cliente, notas...">
                </div>
                <div>
                    <label class="filtro-label">Desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="filtro-input">
                </div>
                <div>
                    <label class="filtro-label">Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="filtro-input">
                </div>
                <div>
                    <label class="filtro-label">Trabajador</label>
                    <select name="trabajador_id" class="filtro-input" style="appearance:auto;">
                        <option value="">Todos</option>
                        @foreach($trabajadores as $t)
                            <option value="{{ $t->id }}" @selected(request('trabajador_id') == $t->id)>{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Estados como chips --}}
            <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-top:14px;">
                <span class="filtro-label" style="margin:0;padding-top:2px;">Estado:</span>
                @foreach($estados as $e)
                    @php $isActive = collect(request('estado'))->contains($e); @endphp
                    <label class="estado-chip estado-chip-{{ $e }} {{ $isActive ? 'active' : '' }}"
                           onclick="this.classList.toggle('active')">
                        <input type="checkbox" name="estado[]" value="{{ $e }}" {{ $isActive ? 'checked' : '' }}>
                        <i class="fas fa-circle" style="font-size:6px;"></i>
                        {{ ucfirst($e) }}
                    </label>
                @endforeach

                <div style="margin-left:auto;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <select name="per_page" class="filtro-input" style="width:auto;appearance:auto;padding:7px 10px;">
                        @foreach([15,25,50,100] as $pp)
                            <option value="{{ $pp }}" @selected((int)request('per_page',15)===$pp)>{{ $pp }} / pag</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;background:#5a31d7;color:#fff;font-size:0.82rem;font-weight:700;border:none;border-radius:10px;cursor:pointer;white-space:nowrap;transition:all 0.2s;">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['q','desde','hasta','estado','trabajador_id']))
                        <a href="{{ route('empresa.configuracion.citas', $id) }}"
                           style="display:inline-flex;align-items:center;gap:4px;padding:8px 14px;font-size:0.75rem;font-weight:600;color:#6b7280;background:#f3f4f6;border-radius:10px;text-decoration:none;white-space:nowrap;transition:all 0.2s;">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Lista de citas --}}
    <div style="display:flex;flex-direction:column;gap:10px;">
        @forelse($citas as $cita)
            @php
                $hIni = $cita->hora_inicio ? \Illuminate\Support\Str::limit($cita->hora_inicio, 5, '') : null;
                $hFin = $cita->hora_fin ? \Illuminate\Support\Str::limit($cita->hora_fin, 5, '') : null;
                try {
                    $durMin = ($hIni && $hFin)
                      ? \Carbon\Carbon::createFromTimeString($hIni.':00')->diffInMinutes(\Carbon\Carbon::createFromTimeString($hFin.':00'))
                      : null;
                } catch (\Throwable $e) { $durMin = null; }
                $fechaCarbon = $cita->fecha ? \Carbon\Carbon::parse($cita->fecha) : null;
                $badgeClass = [
                    'pendiente'  => 'badge-estado-pendiente',
                    'confirmada' => 'badge-estado-confirmada',
                    'cancelada'  => 'badge-estado-cancelada',
                    'completada' => 'badge-estado-completada',
                ][$cita->estado] ?? 'badge-estado-pendiente';
            @endphp
            <div class="cita-card">
                {{-- Fecha --}}
                <div class="cita-fecha">
                    @if($fechaCarbon)
                        <div class="cita-fecha-dia">{{ $fechaCarbon->format('d') }}</div>
                        <div class="cita-fecha-mes">{{ $fechaCarbon->locale('es')->isoFormat('MMM YY') }}</div>
                    @else
                        <div class="cita-fecha-dia">—</div>
                    @endif
                </div>

                <div class="cita-divider"></div>

                {{-- Info --}}
                <div class="cita-info">
                    <div class="cita-cliente">{{ $cita->nombre_cliente ?? 'Sin nombre' }}</div>
                    <div class="cita-meta">
                        @if($hIni && $hFin)
                            <span><i class="far fa-clock"></i>{{ $hIni }} – {{ $hFin }}@if($durMin) ({{ $durMin }}min)@endif</span>
                        @endif
                        @if(isset($cita->trabajador) && $cita->trabajador)
                            <span><i class="fas fa-user"></i>{{ $cita->trabajador->nombre }}</span>
                        @endif
                        @if(optional($cita->servicio)->nombre)
                            <span><i class="fas fa-concierge-bell"></i>{{ $cita->servicio->nombre }}</span>
                        @endif
                        @if($cita->notas)
                            <span><i class="fas fa-sticky-note"></i>{{ \Illuminate\Support\Str::limit($cita->notas, 40) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Estado --}}
                <span class="badge-estado {{ $badgeClass }}">
                    <i class="fas fa-circle" style="font-size:5px;"></i>
                    {{ ucfirst($cita->estado) }}
                </span>

                {{-- Acciones --}}
                <div class="cita-acciones">
                    <button type="button" class="btn-accion" title="Ver detalle"
                            data-bs-toggle="modal" data-bs-target="#citaDetalle-{{ $cita->id }}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn-accion" title="Cambiar estado"
                            data-bs-toggle="modal" data-bs-target="#citaEstado-{{ $cita->id }}">
                        <i class="fas fa-exchange-alt"></i>
                    </button>
                    <button type="button" class="btn-accion btn-accion-danger" title="Eliminar"
                            data-bs-toggle="modal" data-bs-target="#citaEliminar-{{ $cita->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:3rem 1rem;background:#fff;border:1px solid #ece9f8;border-radius:16px;">
                <div style="width:56px;height:56px;border-radius:50%;background:#f0ecfb;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fas fa-calendar-times" style="color:#5a31d7;font-size:1.3rem;opacity:0.5;"></i>
                </div>
                <p style="font-weight:600;color:#374151;margin:0 0 4px 0;">No se encontraron citas</p>
                <p style="font-size:0.82rem;color:#9ca3af;margin:0;">Intenta ajustar los filtros de busqueda.</p>
            </div>
        @endforelse
    </div>

    {{-- Paginacion --}}
    @if($citas->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $citas->links() }}
        </div>
    @endif
</div>

{{-- Modales --}}
@foreach ($citas as $cita)
    @include('empresa.configuracion.citas.modals._detalle', ['cita' => $cita, 'id' => $id])
    @include('empresa.configuracion.citas.modals._estado',   ['cita' => $cita, 'id' => $id])
    @include('empresa.configuracion.citas.modals._eliminar', ['cita' => $cita, 'id' => $id])
@endforeach
@endsection
