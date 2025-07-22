@extends('layouts.empresa')

@php
$currentPage = 'agenda';
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
    <div class="max-w-7xl mx-auto space-y-10">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-3xl font-bold" style="color: #4a5eaa;">⚙️ Configuración de Agenda</h1>
        </div>

        <form action="{{ route('agenda.guardar_bloqueados', $empresa->id) }}" method="POST" class="space-y-10">
            @csrf

            <input type="hidden" name="fechas_bloqueadas" id="fechas_bloqueadas">

            {{-- Calendario --}}
            <section>
                <h2 class="text-lg font-semibold mb-3" style="color: #4a5eaa;">📅 Fechas bloqueadas</h2>
                <div id="calendar" class="bg-white rounded-xl shadow-sm p-4"></div>
            </section>

            {{-- Horarios Laborales --}}
            <section>
                <h2 class="text-lg font-semibold mb-3" style="color: #4a5eaa;">🕓 Horario laboral por día</h2>
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm text-gray-700">
                        <thead style="background-color: #f6f5f7;" class="text-left text-xs uppercase text-[#4a5eaa]">
                            <tr>
                                <th class="px-4 py-3">Día</th>
                                <th class="px-4 py-3">Activo</th>
                                <th class="px-4 py-3">Hora inicio</th>
                                <th class="px-4 py-3">Hora fin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($diasSemana as $numero => $nombre)
                            @php
                            $diaConvertido = $numero % 7;
                            $horario = $horarios->firstWhere('dia_semana', $diaConvertido);
                            @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $nombre }}</td>
                                <td class="px-4 py-2">
                                    <input type="checkbox" name="dias_laborales[{{ $numero }}][activo]" value="1"
                                        class="h-4 w-4 text-[#4a5eaa] border-gray-300 rounded"
                                        {{ $horario && $horario->activo ? 'checked' : '' }}>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="time" name="dias_laborales[{{ $numero }}][inicio]" class="form-input w-full rounded border-gray-300"
                                        value="{{ $horario->hora_inicio ?? '' }}">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="time" name="dias_laborales[{{ $numero }}][fin]" class="form-input w-full rounded border-gray-300"
                                        value="{{ $horario->hora_fin ?? '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Botones --}}
            <div class="flex flex-wrap gap-4">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2 bg-[#4a5eaa] text-white rounded-md hover:bg-[#6C88C4] transition text-sm font-medium">
                    Guardar configuración
                </button>

                <a href="{{ route('empresa.agenda', $empresa->id) }}"
                    class="inline-flex items-center px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-medium">
                    Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    .fc {
        --fc-border-color: transparent;
        --fc-today-bg-color: #f6f5f7;
        font-family: 'Segoe UI', sans-serif;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4a5eaa;
    }

    .fc .fc-button {
        background: #4a5eaa;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        color: white;
    }

    .fc .fc-button:hover {
        background: #6C88C4;
    }

    .fc a {
        text-decoration: none !important;
    }

    .fc .fc-col-header-cell-cushion,
    .fc .fc-daygrid-day-number {
        color: #3B4269;
        font-weight: 500;
    }

    .fc .fc-daygrid-event {
        background-color: #fee2e2;
        border-radius: 6px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fechasSeleccionadas = @json($fechasBloqueadas ?? []);
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            locale: 'es',
            selectMirror: true,
            select: function(info) {
                const fecha = info.startStr;
                if (fechasSeleccionadas.includes(fecha)) {
                    fechasSeleccionadas = fechasSeleccionadas.filter(f => f !== fecha);
                } else {
                    fechasSeleccionadas.push(fecha);
                }
                actualizarEventos();
            },
            events: fechasSeleccionadas.map(fecha => ({
                start: fecha,
                display: 'background',
                backgroundColor: '#fee2e2'
            })),
            eventDisplay: 'background',
            height: 650,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            }
        });

        function actualizarEventos() {
            calendar.removeAllEvents();
            fechasSeleccionadas.forEach(fecha => {
                calendar.addEvent({
                    start: fecha,
                    display: 'background',
                    backgroundColor: '#fee2e2'
                });
            });

            document.getElementById('fechas_bloqueadas').value = fechasSeleccionadas.join(',');
        }

        calendar.render();
        actualizarEventos();
    });
</script>
@endpush
