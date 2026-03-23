@extends('layouts.empresa')

@php
$currentPage = 'agenda';
$currentSubPage = null;
$diasSemana = [
    1 => 'Lun',
    2 => 'Mar',
    3 => 'Mié',
    4 => 'Jue',
    5 => 'Vie',
    6 => 'Sáb',
    7 => 'Dom',
];

$pendientes = $citas->where('estado', 'pendiente')->count();
$confirmadas = $citas->where('estado', 'confirmada')->count();
$canceladas = $citas->where('estado', 'cancelada')->count();
$completadas = $citas->where('estado', 'completada')->count();
$totalCitas = $citas->count();

$citasHoy = $citas->filter(fn($c) => $c->fecha->isToday())->sortBy('hora_inicio');
$citasManana = $citas->filter(fn($c) => $c->fecha->isTomorrow())->sortBy('hora_inicio');
@endphp

@push('styles')
<style>
    /* Modal styles */
    .clx-modal .modal-content { border: 1px solid #ece9f8; border-radius: 16px; box-shadow: 0 20px 40px rgba(90,49,215,0.15); overflow: hidden; }
    .clx-modal .modal-header { background: linear-gradient(135deg, #5a31d7, #7b5ce0); color: #fff; border-bottom: none; padding: 1.25rem 1.5rem; }
    .clx-modal .modal-title { color: #fff; font-weight: 700; }
    .clx-modal .modal-body { padding: 1.5rem; }
    .clx-modal .modal-footer { border-top: 1px solid #f3f4f6; padding: 1rem 1.5rem; }
    .btn-clx-primary { background: #5a31d7; color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; }
    .btn-clx-primary:hover { background: #4a22b8; color: #fff; }
    .btn-clx-outline { background: transparent; color: #5a31d7; border: 1.5px solid #e5e7eb; padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; }
    .btn-clx-outline:hover { border-color: #5a31d7; background: #faf9ff; }
    .clx-select { border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
    .clx-select:focus { border-color: #5a31d7; box-shadow: 0 0 0 3px rgba(90,49,215,0.1); outline: none; }

    /* Agenda layout */
    .agenda-page { padding: 1.5rem; color: #3B4269; }
    .agenda-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 1rem; }
    .agenda-title { font-size: 1.25rem; font-weight: 700; color: #5a31d7; margin: 0; display: flex; align-items: center; gap: 8px; }
    .agenda-subtitle { font-size: 0.78rem; color: #9ca3af; margin: 2px 0 0 0; }
    .agenda-btn-config { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #5a31d7; color: #fff; border-radius: 10px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: background 0.2s; white-space: nowrap; }
    .agenda-btn-config:hover { background: #4a22b8; color: #fff; }

    /* Breadcrumbs */
    .agenda-breadcrumbs { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #9ca3af; margin-bottom: 0.75rem; }
    .agenda-breadcrumbs a { color: #5a31d7; text-decoration: none; font-weight: 500; }
    .agenda-breadcrumbs a:hover { text-decoration: underline; }
    .agenda-breadcrumbs .sep { font-size: 0.55rem; color: #d1d5db; }

    /* Stats strip — compact horizontal */
    .agenda-stats { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 1rem; }
    .agenda-stat { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.78rem; white-space: nowrap; }
    .agenda-stat-num { font-weight: 700; font-size: 0.9rem; }
    .agenda-stat-label { color: #6b7280; }
    .agenda-stat--primary .agenda-stat-num { color: #5a31d7; }
    .agenda-stat--amber .agenda-stat-num { color: #f59e0b; }
    .agenda-stat--green .agenda-stat-num { color: #10b981; }
    .agenda-stat--purple .agenda-stat-num { color: #7c3aed; }
    .agenda-stat--red .agenda-stat-num { color: #ef4444; }

    /* Cards */
    .agenda-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; }
    .agenda-card-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-bottom: 1px solid #f3f4f6; }
    .agenda-card-title { font-size: 0.82rem; font-weight: 600; color: #3B4269; display: flex; align-items: center; gap: 6px; margin: 0; }
    .agenda-card-title i { color: #5a31d7; font-size: 0.78rem; }
    .agenda-card-badge { font-size: 0.65rem; font-weight: 700; color: #fff; background: #5a31d7; padding: 1px 7px; border-radius: 100px; }
    .agenda-card-meta { font-size: 0.7rem; color: #9ca3af; }
    .agenda-card-body { padding: 8px 10px; }
    .agenda-card-empty { text-align: center; padding: 1.5rem 1rem; }
    .agenda-card-empty i { font-size: 1.5rem; color: #e5e7eb; margin-bottom: 6px; }
    .agenda-card-empty p { font-size: 0.78rem; color: #9ca3af; margin: 0; }

    /* Cita row */
    .cita-row { display: flex; align-items: center; gap: 8px; padding: 7px 10px; border-radius: 10px; border: 1px solid #f3f4f6; cursor: pointer; transition: all 0.15s; margin-bottom: 4px; }
    .cita-row:last-child { margin-bottom: 0; }
    .cita-row:hover { border-color: rgba(90,49,215,0.2); background: #faf9ff; }
    .cita-bar { width: 3px; height: 32px; border-radius: 3px; flex-shrink: 0; }
    .cita-info { flex: 1; min-width: 0; }
    .cita-name { font-size: 0.78rem; font-weight: 600; color: #3B4269; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cita-detail { font-size: 0.7rem; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cita-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
    .cita-badge { font-size: 0.65rem; font-weight: 600; padding: 2px 8px; border-radius: 100px; white-space: nowrap; }
    .cita-btn-estado { width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: none; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s; font-size: 0.65rem; }
    .cita-btn-estado:hover { background: #f3f4f6; color: #5a31d7; }

    /* Grids */
    .agenda-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 1rem; }

    /* Horario strip */
    .horario-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; padding: 10px 14px; }
    .horario-day { display: flex; flex-direction: column; align-items: center; padding: 8px 4px; border-radius: 10px; border: 1px solid #f3f4f6; transition: all 0.15s; }
    .horario-day--hoy { border-color: #5a31d7; background: #f8f6ff; }
    .horario-day--cerrado { opacity: 0.4; }
    .horario-day-name { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; }
    .horario-day--hoy .horario-day-name { color: #5a31d7; }
    .horario-day-dot { width: 4px; height: 4px; border-radius: 50%; background: #5a31d7; margin: 3px 0; }
    .horario-day-time { font-size: 0.65rem; font-weight: 500; color: #3B4269; line-height: 1.3; }
    .horario-day--hoy .horario-day-time { color: #5a31d7; }
    .horario-day-closed { font-size: 0.6rem; color: #ef4444; font-weight: 500; margin-top: 3px; }

    /* Flash messages */
    .agenda-flash { display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 10px; font-size: 0.8rem; margin-bottom: 0.75rem; }
    .agenda-flash--success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .agenda-flash--error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 767px) {
        .agenda-page { padding: 1rem 12px; }

        .agenda-header { gap: 8px; }
        .agenda-title { font-size: 1.1rem; }
        .agenda-btn-config { padding: 7px 12px; font-size: 0.75rem; }

        .agenda-stats { gap: 4px; }
        .agenda-stat { padding: 5px 8px; font-size: 0.72rem; }
        .agenda-stat-num { font-size: 0.82rem; }

        .agenda-grid-2 { grid-template-columns: 1fr; gap: 10px; }

        .horario-grid { grid-template-columns: repeat(7, 1fr); gap: 3px; padding: 8px 8px; }
        .horario-day { padding: 6px 2px; }
        .horario-day-name { font-size: 0.58rem; }
        .horario-day-time { font-size: 0.58rem; }

        .cita-row { padding: 6px 8px; gap: 6px; }
        .cita-name { font-size: 0.75rem; }
        .cita-detail { font-size: 0.67rem; }
        .cita-badge { font-size: 0.6rem; padding: 2px 6px; }

        .agenda-card-body { max-height: 300px; overflow-y: auto; }
    }

    @media (max-width: 380px) {
        .agenda-stats { flex-direction: column; }
        .agenda-stat { justify-content: space-between; }

        .horario-grid { grid-template-columns: repeat(4, 1fr); }
    }
</style>
@endpush

@section('content')
<div class="agenda-page">

    {{-- Breadcrumbs --}}
    <nav class="agenda-breadcrumbs">
        <a href="{{ route('empresa.dashboard', $empresa->id) }}">
            <i class="fas fa-home" style="font-size:0.7rem;"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right sep"></i>
        <span style="color:#374151; font-weight:500;">Agenda</span>
    </nav>

    {{-- Header --}}
    <div class="agenda-header">
        <div>
            <h1 class="agenda-title">
                <i class="fas fa-calendar-alt"></i> Agenda
            </h1>
            <p class="agenda-subtitle">Gestiona tus citas y horarios de trabajo.</p>
        </div>
        <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}" class="agenda-btn-config">
            <i class="fas fa-cog"></i> Configurar horarios
        </a>
    </div>

    {{-- Link público compartible --}}
    @include('components.share-link', ['slug' => $empresa->slug])

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="agenda-flash agenda-flash--success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="agenda-flash agenda-flash--error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Stats strip --}}
    <div class="agenda-stats">
        <div class="agenda-stat agenda-stat--primary">
            <i class="fas fa-calendar-check" style="color:#5a31d7; font-size:0.72rem;"></i>
            <span class="agenda-stat-num">{{ $totalCitas }}</span>
            <span class="agenda-stat-label">Total</span>
        </div>
        <div class="agenda-stat agenda-stat--amber">
            <i class="fas fa-clock" style="color:#f59e0b; font-size:0.72rem;"></i>
            <span class="agenda-stat-num">{{ $pendientes }}</span>
            <span class="agenda-stat-label">Pendientes</span>
        </div>
        <div class="agenda-stat agenda-stat--green">
            <i class="fas fa-check-circle" style="color:#10b981; font-size:0.72rem;"></i>
            <span class="agenda-stat-num">{{ $confirmadas }}</span>
            <span class="agenda-stat-label">Confirmadas</span>
        </div>
        <div class="agenda-stat agenda-stat--purple">
            <i class="fas fa-clipboard-check" style="color:#7c3aed; font-size:0.72rem;"></i>
            <span class="agenda-stat-num">{{ $completadas }}</span>
            <span class="agenda-stat-label">Completadas</span>
        </div>
        <div class="agenda-stat agenda-stat--red">
            <i class="fas fa-times-circle" style="color:#ef4444; font-size:0.72rem;"></i>
            <span class="agenda-stat-num">{{ $canceladas }}</span>
            <span class="agenda-stat-label">Canceladas</span>
        </div>
    </div>

    {{-- Citas Hoy + Mañana --}}
    <div class="agenda-grid-2">

        {{-- Hoy --}}
        <div class="agenda-card">
            <div class="agenda-card-header">
                <h2 class="agenda-card-title">
                    <i class="fas fa-calendar-day"></i> Hoy
                    @if($citasHoy->count())
                        <span class="agenda-card-badge">{{ $citasHoy->count() }}</span>
                    @endif
                </h2>
                <span class="agenda-card-meta">{{ now()->locale('es')->isoFormat('ddd D MMM') }}</span>
            </div>
            <div class="agenda-card-body">
                @if($citasHoy->isEmpty())
                    <div class="agenda-card-empty">
                        <i class="fas fa-calendar-check"></i>
                        <p>Sin citas para hoy</p>
                    </div>
                @else
                    @foreach($citasHoy as $cita)
                    @php
                        $estadoColor = match($cita->estado) {
                            'confirmada' => '#10b981',
                            'cancelada'  => '#ef4444',
                            'completada' => '#5a31d7',
                            default      => '#f59e0b',
                        };
                        $hora = substr($cita->hora_inicio, 0, 5);
                        $horaFin = substr($cita->hora_fin, 0, 5);
                    @endphp
                    <div class="cita-row" data-bs-toggle="modal" data-bs-target="#citaDetalle-{{ $cita->id }}">
                        <div class="cita-bar" style="background:{{ $estadoColor }};"></div>
                        <div class="cita-info">
                            <div class="cita-name">{{ $cita->nombre_cliente ?? 'Sin nombre' }}</div>
                            <div class="cita-detail">
                                {{ $hora }}-{{ $horaFin }}
                                @if($cita->servicio) &middot; {{ $cita->servicio->nombre }} @endif
                                @if($cita->trabajador) &middot; {{ $cita->trabajador->nombre }} @endif
                            </div>
                        </div>
                        <div class="cita-actions">
                            <span class="cita-badge" style="background:{{ $estadoColor }}15; color:{{ $estadoColor }};">
                                {{ ucfirst($cita->estado) }}
                            </span>
                            <button type="button" class="cita-btn-estado"
                                    data-bs-toggle="modal" data-bs-target="#citaEstado-{{ $cita->id }}"
                                    onclick="event.stopPropagation();" title="Cambiar estado">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Mañana --}}
        <div class="agenda-card">
            <div class="agenda-card-header">
                <h2 class="agenda-card-title">
                    <i class="fas fa-calendar"></i> Mañana
                    @if($citasManana->count())
                        <span class="agenda-card-badge">{{ $citasManana->count() }}</span>
                    @endif
                </h2>
                <span class="agenda-card-meta">{{ now()->addDay()->locale('es')->isoFormat('ddd D MMM') }}</span>
            </div>
            <div class="agenda-card-body">
                @if($citasManana->isEmpty())
                    <div class="agenda-card-empty">
                        <i class="fas fa-calendar"></i>
                        <p>Sin citas para mañana</p>
                    </div>
                @else
                    @foreach($citasManana as $cita)
                    @php
                        $estadoColor = match($cita->estado) {
                            'confirmada' => '#10b981',
                            'cancelada'  => '#ef4444',
                            'completada' => '#5a31d7',
                            default      => '#f59e0b',
                        };
                        $hora = substr($cita->hora_inicio, 0, 5);
                        $horaFin = substr($cita->hora_fin, 0, 5);
                    @endphp
                    <div class="cita-row" data-bs-toggle="modal" data-bs-target="#citaDetalle-{{ $cita->id }}">
                        <div class="cita-bar" style="background:{{ $estadoColor }};"></div>
                        <div class="cita-info">
                            <div class="cita-name">{{ $cita->nombre_cliente ?? 'Sin nombre' }}</div>
                            <div class="cita-detail">
                                {{ $hora }}-{{ $horaFin }}
                                @if($cita->servicio) &middot; {{ $cita->servicio->nombre }} @endif
                                @if($cita->trabajador) &middot; {{ $cita->trabajador->nombre }} @endif
                            </div>
                        </div>
                        <div class="cita-actions">
                            <span class="cita-badge" style="background:{{ $estadoColor }}15; color:{{ $estadoColor }};">
                                {{ ucfirst($cita->estado) }}
                            </span>
                            <button type="button" class="cita-btn-estado"
                                    data-bs-toggle="modal" data-bs-target="#citaEstado-{{ $cita->id }}"
                                    onclick="event.stopPropagation();" title="Cambiar estado">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>

    {{-- Horario Laboral --}}
    <div class="agenda-card">
        <div class="agenda-card-header">
            <h2 class="agenda-card-title">
                <i class="fas fa-clock"></i> Horario Laboral
            </h2>
            <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}" style="font-size:0.72rem; color:#5a31d7; font-weight:500; text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                <i class="fas fa-edit" style="font-size:0.65rem;"></i> Editar
            </a>
        </div>
        <div class="horario-grid">
            @foreach ($diasSemana as $numero => $nombre)
            @php
                $inicio = $horarios->firstWhere('dia_semana', $numero)?->hora_inicio;
                $fin = $horarios->firstWhere('dia_semana', $numero)?->hora_fin;
                $abierto = $inicio && $fin;
                $esHoy = $numero == now()->dayOfWeekIso;
            @endphp
            <div class="horario-day {{ $esHoy ? 'horario-day--hoy' : '' }} {{ !$abierto ? 'horario-day--cerrado' : '' }}">
                <span class="horario-day-name">{{ $nombre }}</span>
                @if($esHoy)
                    <span class="horario-day-dot"></span>
                @else
                    <span style="height:4px; margin:3px 0;"></span>
                @endif
                @if($abierto)
                    <span class="horario-day-time">{{ substr($inicio, 0, 5) }}</span>
                    <span style="font-size:0.55rem; color:#d1d5db;">a</span>
                    <span class="horario-day-time">{{ substr($fin, 0, 5) }}</span>
                @else
                    <span class="horario-day-closed">Cerrado</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Modals --}}
@foreach($citasHoy->merge($citasManana) as $cita)
    @include('empresa.configuracion.citas.modals._detalle', ['cita' => $cita, 'id' => $empresa->id])
    @include('empresa.configuracion.citas.modals._estado', ['cita' => $cita, 'id' => $empresa->id])
    @include('empresa.configuracion.citas.modals._reprogramar', ['cita' => $cita, 'id' => $empresa->id])
@endforeach

@endsection
