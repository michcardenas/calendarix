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
<div class="ml-[16.25rem]  min-h-screen px-6 py-10 text-gray-800">
    <div class="max-w-7xl mx-auto space-y-10">
        {{-- Encabezado --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-3xl font-bold">📆 Agenda</h1>
            <a href="{{ route('empresa.agenda.configurar', $empresa->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md bg-white hover:bg-gray-50 transition no-underline">
                Configurar horarios
            </a>
        </div>

        {{-- Horarios Laborales --}}
        <section>
            <h2 class="text-lg font-semibold mb-3">🕓 Horario Laboral</h2>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Día</th>
                            <th class="px-4 py-3">Inicio</th>
                            <th class="px-4 py-3">Fin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($diasSemana as $numero => $nombre)
                        @php
                        $inicio = $horarios->firstWhere('dia_semana', $numero)?->hora_inicio;
                        $fin = $horarios->firstWhere('dia_semana', $numero)?->hora_fin;
                        @endphp
                        <tr>
                            <td class="px-4 py-2">{{ $nombre }}</td>
                            <td class="px-4 py-2">
                                {{ $inicio ? \Carbon\Carbon::createFromFormat('H:i:s', $inicio)->format('g:i A') : 'Cerrado' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $fin ? \Carbon\Carbon::createFromFormat('H:i:s', $fin)->format('g:i A') : 'Cerrado' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Calendario --}}
        <section>
            <h2 class="text-lg font-semibold mb-3">📅 Calendario</h2>
            <div id="calendar" class="bg-white rounded-xl shadow-sm p-4"></div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Quitar subrayado de enlaces del calendario y botón */
    .fc a,
    a.no-underline {
        text-decoration: none !important;
    }

    .fc {
        --fc-border-color: transparent;
        --fc-today-bg-color: #fefce8;
        /* amarillo pálido para el día actual */
        --fc-page-bg-color: transparent;
        font-family: 'Segoe UI', sans-serif;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
    }

    .fc .fc-button {
        background: #4f46e5;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        color: white;
        transition: background 0.2s ease;
    }

    .fc .fc-button:hover {
        background: #4338ca;
    }

    .fc .fc-daygrid-event {
        background-color: #e0e7ff;
        border: none;
        padding: 2px 6px;
        font-size: 0.75rem;
        color: #3730a3;
        border-radius: 6px;
        font-weight: 500;
        margin-top: 4px;
    }

    .fc .fc-col-header-cell-cushion,
    .fc .fc-daygrid-day-number {
        color: #6b7280;
        font-weight: 500;
    }

    .fc .fc-scrollgrid {
        border-radius: 0.75rem;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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