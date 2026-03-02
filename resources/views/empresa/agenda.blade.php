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
@endphp

@section('content')
<div class="min-h-screen px-6 py-10" style="background-color: #f6f5f7; color: #3B4269;">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Encabezado --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: #5a31d7;">Agenda</h1>
                <p class="text-sm text-gray-400 mt-1">Visualiza tus citas y horarios de trabajo.</p>
            </div>
            <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-xl hover:shadow-lg hover:shadow-[#5a31d7]/20 transition-all"
                style="background-color: #5a31d7;">
                <i class="fas fa-cog"></i> Configurar horarios
            </a>
        </div>

        {{-- Horarios Laborales --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-[#3B4269]">Horario Laboral</h2>
                <p class="text-xs text-gray-400 mt-0.5">Resumen de los horarios de apertura y cierre.</p>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#f9f8fc] text-xs uppercase tracking-wide text-[#5a31d7]">
                        <th class="px-6 py-3 text-left font-semibold">Dia</th>
                        <th class="px-4 py-3 text-left font-semibold">Inicio</th>
                        <th class="px-4 py-3 text-left font-semibold">Fin</th>
                        <th class="px-4 py-3 text-center font-semibold">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diasSemana as $numero => $nombre)
                    @php
                    $inicio = $horarios->firstWhere('dia_semana', $numero)?->hora_inicio;
                    $fin = $horarios->firstWhere('dia_semana', $numero)?->hora_fin;
                    $abierto = $inicio && $fin;
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-[#faf9fd] transition-colors {{ !$abierto ? 'opacity-50' : '' }}">
                        <td class="px-6 py-3">
                            <span class="font-medium text-[#3B4269]">{{ $nombre }}</span>
                        </td>
                        <td class="px-4 py-3 text-[#3B4269]">
                            {{ $inicio ? \Carbon\Carbon::createFromFormat('H:i:s', $inicio)->format('g:i A') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-[#3B4269]">
                            {{ $fin ? \Carbon\Carbon::createFromFormat('H:i:s', $fin)->format('g:i A') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($abierto)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Abierto
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                    Cerrado
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- Calendario --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-[#3B4269]">Calendario</h2>
                <p class="text-xs text-gray-400 mt-0.5">Vista general de tus citas programadas.</p>
            </div>
            <div id="calendar" class="p-4"></div>
        </section>

    </div>
</div>
@endsection

@push('styles')
<style>
    .fc a,
    a.no-underline {
        text-decoration: none !important;
    }

    .fc {
        --fc-border-color: #f0f0f5;
        --fc-today-bg-color: rgba(90, 49, 215, 0.04);
        --fc-page-bg-color: transparent;
        font-family: 'Segoe UI', sans-serif;
    }

    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #3B4269;
    }

    .fc .fc-button {
        background: white;
        border: 1px solid #e5e7eb;
        padding: 0.35rem 0.7rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #3B4269;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }

    .fc .fc-button:hover {
        background: #5a31d7;
        border-color: #5a31d7;
        color: white;
    }

    .fc .fc-button-active {
        background: #5a31d7 !important;
        border-color: #5a31d7 !important;
        color: white !important;
    }

    .fc .fc-col-header-cell-cushion {
        color: #9c9cb9;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .fc .fc-daygrid-day-number {
        color: #3B4269;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .fc .fc-day-today .fc-daygrid-day-number {
        background: #5a31d7;
        color: white;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
    }

    .fc .fc-daygrid-event {
        background-color: #ede9fe;
        border: none;
        padding: 2px 8px;
        font-size: 0.75rem;
        color: #5a31d7;
        border-radius: 6px;
        font-weight: 500;
        margin-top: 4px;
    }

    .fc .fc-scrollgrid {
        border-radius: 0.75rem;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const eventos = @json($eventos);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: eventos,
        });

        calendar.render();
    });
</script>
@endpush
