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

$allCitas = collect($eventos)->filter(fn($e) => isset($e['extendedProps']));
$pendientes = $allCitas->where('extendedProps.estado', 'pendiente')->count();
$confirmadas = $allCitas->where('extendedProps.estado', 'confirmada')->count();
$canceladas = $allCitas->where('extendedProps.estado', 'cancelada')->count();
$completadas = $allCitas->where('extendedProps.estado', 'completada')->count();
$totalCitas = $allCitas->count();

$citasHoy = $allCitas->filter(fn($e) => str_starts_with($e['start'] ?? '', now()->format('Y-m-d')));
$citasManana = $allCitas->filter(fn($e) => str_starts_with($e['start'] ?? '', now()->addDay()->format('Y-m-d')));
@endphp

@section('content')
<div class="min-h-screen px-4 md:px-6 py-8" style="background-color: #f6f5f7; color: #3B4269;">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Encabezado --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: #5a31d7;">
                    <i class="fas fa-calendar-alt mr-2"></i>Agenda
                </h1>
                <p class="text-sm text-gray-400 mt-1">Resumen de tus citas y horarios de trabajo.</p>
            </div>
            <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-xl hover:shadow-lg hover:shadow-[#5a31d7]/20 transition-all"
                style="background-color: #5a31d7;">
                <i class="fas fa-cog"></i> Configurar horarios
            </a>
        </div>

        {{-- Resumen rápido --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                <div class="px-3 py-2 flex-1 overflow-y-auto" style="max-height: 260px;">
                    @if($citasHoy->isEmpty())
                        <div class="text-center py-6">
                            <i class="fas fa-calendar-check text-3xl text-gray-200 mb-2"></i>
                            <p class="text-xs text-gray-400">Sin citas para hoy</p>
                        </div>
                    @else
                        <div class="space-y-1.5">
                            @foreach($citasHoy->sortBy('start') as $ev)
                            @php
                                $props = $ev['extendedProps'] ?? [];
                                $estadoColor = match($props['estado'] ?? '') {
                                    'confirmada' => '#10b981',
                                    'cancelada'  => '#ef4444',
                                    'completada' => '#5a31d7',
                                    default      => '#f59e0b',
                                };
                                $hora = substr($ev['start'] ?? '', 11, 5);
                                $horaFin = substr($ev['end'] ?? '', 11, 5);
                            @endphp
                            <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-gray-100 hover:border-[#5a31d7]/20 hover:bg-[#faf9ff] transition-all">
                                <div class="w-1 h-9 rounded-full flex-shrink-0" style="background: {{ $estadoColor }};"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-[#3B4269] truncate">{{ $props['cliente'] ?? 'Sin nombre' }}</p>
                                    <p class="text-xs text-gray-400 truncate">
                                        {{ $hora }}{{ $horaFin ? " - $horaFin" : '' }}
                                        @if($props['servicio'] ?? null) · {{ $props['servicio'] }} @endif
                                        @if($props['trabajador'] ?? null) · {{ $props['trabajador'] }} @endif
                                    </p>
                                </div>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full flex-shrink-0" style="background: {{ $estadoColor }}15; color: {{ $estadoColor }};">
                                    {{ ucfirst($props['estado'] ?? 'pendiente') }}
                                </span>
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
                <div class="px-3 py-2 flex-1 overflow-y-auto" style="max-height: 260px;">
                    @if($citasManana->isEmpty())
                        <div class="text-center py-6">
                            <i class="fas fa-calendar text-3xl text-gray-200 mb-2"></i>
                            <p class="text-xs text-gray-400">Sin citas para mañana</p>
                        </div>
                    @else
                        <div class="space-y-1.5">
                            @foreach($citasManana->sortBy('start') as $ev)
                            @php
                                $props = $ev['extendedProps'] ?? [];
                                $estadoColor = match($props['estado'] ?? '') {
                                    'confirmada' => '#10b981',
                                    'cancelada'  => '#ef4444',
                                    'completada' => '#5a31d7',
                                    default      => '#f59e0b',
                                };
                                $hora = substr($ev['start'] ?? '', 11, 5);
                                $horaFin = substr($ev['end'] ?? '', 11, 5);
                            @endphp
                            <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-gray-100 hover:border-[#5a31d7]/20 hover:bg-[#faf9ff] transition-all">
                                <div class="w-1 h-9 rounded-full flex-shrink-0" style="background: {{ $estadoColor }};"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-[#3B4269] truncate">{{ $props['cliente'] ?? 'Sin nombre' }}</p>
                                    <p class="text-xs text-gray-400 truncate">
                                        {{ $hora }}{{ $horaFin ? " - $horaFin" : '' }}
                                        @if($props['servicio'] ?? null) · {{ $props['servicio'] }} @endif
                                    </p>
                                </div>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full flex-shrink-0" style="background: {{ $estadoColor }}15; color: {{ $estadoColor }};">
                                    {{ ucfirst($props['estado'] ?? 'pendiente') }}
                                </span>
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
@endsection
