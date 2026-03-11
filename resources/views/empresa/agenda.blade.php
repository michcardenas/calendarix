@extends('layouts.empresa')

@php
$currentPage = 'agenda';
$currentSubPage = null;
$diasSemana = [
    1 => 'Lunes',
    2 => 'Martes',
    3 => 'Miércoles',
    4 => 'Jueves',
    5 => 'Viernes',
    6 => 'Sábado',
    7 => 'Domingo',
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
    /* Estilos para modals de citas */
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
    .badge-state { padding: 0.25rem 0.75rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600; }
    .badge-pendiente { background: #fef9c3; color: #92400e; }
    .badge-confirmada { background: #d1fae5; color: #065f46; }
    .badge-cancelada { background: #fee2e2; color: #991b1b; }
    .badge-completada { background: #ede9fe; color: #5b21b6; }
</style>
@endpush

@section('content')
<div class="min-h-screen px-4 md:px-6 py-8" style="background-color: #f6f5f7; color: #3B4269;">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Encabezado --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: #5a31d7;">
                    <i class="fas fa-calendar-alt mr-2"></i>Agenda
                </h1>
                <p class="text-sm text-gray-400 mt-1">Gestiona tus citas y horarios de trabajo.</p>
            </div>
            <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-xl hover:shadow-lg hover:shadow-[#5a31d7]/20 transition-all"
                style="background-color: #5a31d7;">
                <i class="fas fa-cog"></i> Configurar horarios
            </a>
        </div>

        {{-- Flash de éxito --}}
        @if(session('success'))
            <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Resumen rápido --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#f8f6ff] mb-2">
                    <i class="fas fa-calendar-check text-[#5a31d7]"></i>
                </div>
                <p class="text-2xl font-bold text-[#5a31d7]">{{ $totalCitas }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Total citas</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 mb-2">
                    <i class="fas fa-clock text-amber-500"></i>
                </div>
                <p class="text-2xl font-bold text-amber-500">{{ $pendientes }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Pendientes</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50 mb-2">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                </div>
                <p class="text-2xl font-bold text-emerald-500">{{ $confirmadas }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Confirmadas</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-50 mb-2">
                    <i class="fas fa-clipboard-check text-[#5a31d7]"></i>
                </div>
                <p class="text-2xl font-bold text-[#5a31d7]">{{ $completadas }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Completadas</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 mb-2">
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
                <p class="text-2xl font-bold text-red-500">{{ $canceladas }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Canceladas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Citas de hoy --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-[#3B4269]">
                        <i class="fas fa-calendar-day text-[#5a31d7] mr-1"></i>Hoy
                        @if($citasHoy->count())
                            <span class="ml-1 text-xs font-bold text-white bg-[#5a31d7] px-1.5 py-0.5 rounded-full">{{ $citasHoy->count() }}</span>
                        @endif
                    </h2>
                    <span class="text-xs text-gray-400">{{ now()->isoFormat('ddd D MMM') }}</span>
                </div>
                <div class="px-3 py-2 flex-1 overflow-y-auto" style="max-height: 400px;">
                    @if($citasHoy->isEmpty())
                        <div class="text-center py-6">
                            <i class="fas fa-calendar-check text-3xl text-gray-200 mb-2"></i>
                            <p class="text-xs text-gray-400">Sin citas para hoy</p>
                        </div>
                    @else
                        <div class="space-y-1.5">
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
                            <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-gray-100 hover:border-[#5a31d7]/20 hover:bg-[#faf9ff] transition-all cursor-pointer"
                                 data-bs-toggle="modal" data-bs-target="#citaDetalle-{{ $cita->id }}">
                                <div class="w-1 h-9 rounded-full flex-shrink-0" style="background: {{ $estadoColor }};"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-[#3B4269] truncate">{{ $cita->nombre_cliente ?? 'Sin nombre' }}</p>
                                    <p class="text-xs text-gray-400 truncate">
                                        {{ $hora }} - {{ $horaFin }}
                                        @if($cita->servicio) · {{ $cita->servicio->nombre }} @endif
                                        @if($cita->trabajador) · {{ $cita->trabajador->nombre }} @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background: {{ $estadoColor }}15; color: {{ $estadoColor }};">
                                        {{ ucfirst($cita->estado) }}
                                    </span>
                                    <button type="button" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-[#5a31d7] transition-colors"
                                            data-bs-toggle="modal" data-bs-target="#citaEstado-{{ $cita->id }}"
                                            onclick="event.stopPropagation();" title="Cambiar estado">
                                        <i class="fas fa-exchange-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            {{-- Citas de mañana --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-[#3B4269]">
                        <i class="fas fa-calendar text-[#5a31d7] mr-1"></i>Mañana
                        @if($citasManana->count())
                            <span class="ml-1 text-xs font-bold text-white bg-[#5a31d7] px-1.5 py-0.5 rounded-full">{{ $citasManana->count() }}</span>
                        @endif
                    </h2>
                    <span class="text-xs text-gray-400">{{ now()->addDay()->isoFormat('ddd D MMM') }}</span>
                </div>
                <div class="px-3 py-2 flex-1 overflow-y-auto" style="max-height: 400px;">
                    @if($citasManana->isEmpty())
                        <div class="text-center py-6">
                            <i class="fas fa-calendar text-3xl text-gray-200 mb-2"></i>
                            <p class="text-xs text-gray-400">Sin citas para mañana</p>
                        </div>
                    @else
                        <div class="space-y-1.5">
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
                            <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-gray-100 hover:border-[#5a31d7]/20 hover:bg-[#faf9ff] transition-all cursor-pointer"
                                 data-bs-toggle="modal" data-bs-target="#citaDetalle-{{ $cita->id }}">
                                <div class="w-1 h-9 rounded-full flex-shrink-0" style="background: {{ $estadoColor }};"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-[#3B4269] truncate">{{ $cita->nombre_cliente ?? 'Sin nombre' }}</p>
                                    <p class="text-xs text-gray-400 truncate">
                                        {{ $hora }} - {{ $horaFin }}
                                        @if($cita->servicio) · {{ $cita->servicio->nombre }} @endif
                                        @if($cita->trabajador) · {{ $cita->trabajador->nombre }} @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background: {{ $estadoColor }}15; color: {{ $estadoColor }};">
                                        {{ ucfirst($cita->estado) }}
                                    </span>
                                    <button type="button" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-[#5a31d7] transition-colors"
                                            data-bs-toggle="modal" data-bs-target="#citaEstado-{{ $cita->id }}"
                                            onclick="event.stopPropagation();" title="Cambiar estado">
                                        <i class="fas fa-exchange-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Horario Laboral --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-[#3B4269]">
                    <i class="fas fa-clock text-[#5a31d7] mr-1.5"></i>Horario Laboral
                </h2>
                <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}" class="text-xs text-[#5a31d7] hover:underline font-medium">
                    <i class="fas fa-edit mr-0.5"></i> Editar
                </a>
            </div>
            <div class="px-5 py-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
                    @foreach ($diasSemana as $numero => $nombre)
                    @php
                        $inicio = $horarios->firstWhere('dia_semana', $numero)?->hora_inicio;
                        $fin = $horarios->firstWhere('dia_semana', $numero)?->hora_fin;
                        $abierto = $inicio && $fin;
                        $esHoy = $numero == now()->dayOfWeekIso;
                    @endphp
                    <div class="flex flex-col items-center p-3 rounded-xl border transition-all
                        {{ $esHoy ? 'border-[#5a31d7] bg-[#f8f6ff] shadow-sm' : ($abierto ? 'border-gray-100 bg-gray-50/50' : 'border-gray-100 bg-gray-50/30 opacity-50') }}">
                        <span class="text-xs font-semibold uppercase tracking-wide {{ $esHoy ? 'text-[#5a31d7]' : 'text-gray-500' }}">
                            {{ $nombre }}
                        </span>
                        @if($esHoy)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#5a31d7] my-1.5"></span>
                        @else
                            <span class="my-1.5 h-1.5"></span>
                        @endif
                        @if($abierto)
                            <span class="text-xs font-medium {{ $esHoy ? 'text-[#5a31d7]' : 'text-[#3B4269]' }}">
                                {{ substr($inicio, 0, 5) }}
                            </span>
                            <span class="text-xs text-gray-300">a</span>
                            <span class="text-xs font-medium {{ $esHoy ? 'text-[#5a31d7]' : 'text-[#3B4269]' }}">
                                {{ substr($fin, 0, 5) }}
                            </span>
                        @else
                            <span class="text-xs text-red-400 font-medium mt-1">Cerrado</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>

{{-- Modals para citas de hoy y mañana --}}
@foreach($citasHoy->merge($citasManana) as $cita)
    @include('empresa.configuracion.citas.modals._detalle', ['cita' => $cita, 'id' => $empresa->id])
    @include('empresa.configuracion.citas.modals._estado', ['cita' => $cita, 'id' => $empresa->id])
@endforeach

@endsection
