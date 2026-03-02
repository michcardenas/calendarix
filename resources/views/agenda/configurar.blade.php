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
    <div class="max-w-7xl mx-auto space-y-8">

        <div>
            <h1 class="text-2xl font-bold" style="color: #5a31d7;">Configuracion de Agenda</h1>
            <p class="text-sm text-gray-400 mt-1">Gestiona tus fechas bloqueadas y horarios laborales.</p>
        </div>

        <form action="{{ route('agenda.guardar_bloqueados', $empresa->id) }}" method="POST" class="space-y-8">
            @csrf

            <input type="hidden" name="fechas_bloqueadas" id="fechas_bloqueadas">

            {{-- Calendario --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-[#3B4269]">Fechas bloqueadas</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Haz clic en los dias que deseas bloquear.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs text-red-400 bg-red-50 px-2.5 py-1 rounded-full" id="contador-bloqueados">
                        <span class="w-2 h-2 rounded-full bg-red-400"></span>
                        <span id="num-bloqueados">0</span> bloqueados
                    </span>
                </div>
                <div id="calendar" class="p-4"></div>
            </section>

            {{-- Horarios Laborales --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-[#3B4269]">Horario laboral por dia</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Define los horarios de apertura y cierre para cada dia.</p>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#f9f8fc] text-xs uppercase tracking-wide text-[#5a31d7]">
                            <th class="px-6 py-3 text-left font-semibold">Dia</th>
                            <th class="px-4 py-3 text-center font-semibold">Estado</th>
                            <th class="px-4 py-3 text-left font-semibold">Inicio</th>
                            <th class="px-4 py-3 text-left font-semibold">Fin</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($diasSemana as $numero => $nombre)
                        @php
                        $diaConvertido = $numero % 7;
                        $horario = $horarios->firstWhere('dia_semana', $diaConvertido);
                        $isActive = $horario && $horario->activo;
                        @endphp
                        <tr data-dia="{{ $numero }}" class="border-b border-gray-50 hover:bg-[#faf9fd] transition-colors horario-row {{ $isActive ? 'activo' : 'inactivo' }}">
                            <td class="px-6 py-3">
                                <span class="font-medium text-[#3B4269]">{{ $nombre }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="dias_laborales[{{ $numero }}][activo]" value="1"
                                        class="dia-activo sr-only"
                                        {{ $isActive ? 'checked' : '' }}>
                                    <span class="toggle-track"></span>
                                </label>
                            </td>
                            <td class="px-4 py-3">
                                <input type="time" name="dias_laborales[{{ $numero }}][inicio]"
                                    class="form-input rounded-lg border-gray-200 text-sm px-3 py-1.5 focus:ring-[#5a31d7] focus:border-[#5a31d7] dia-inicio w-full max-w-[140px]"
                                    value="{{ $horario->hora_inicio ?? '' }}">
                            </td>
                            <td class="px-4 py-3">
                                <input type="time" name="dias_laborales[{{ $numero }}][fin]"
                                    class="form-input rounded-lg border-gray-200 text-sm px-3 py-1.5 focus:ring-[#5a31d7] focus:border-[#5a31d7] dia-fin w-full max-w-[140px]"
                                    value="{{ $horario->hora_fin ?? '' }}">
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($numero > 1)
                                    <button type="button" onclick="clonarDiaAnterior({{ $numero }})"
                                        class="inline-flex items-center gap-1 text-[#5a31d7] hover:bg-[#5a31d7] hover:text-white px-2.5 py-1 rounded-md transition-all text-xs font-medium"
                                        title="Copiar horario del dia anterior">
                                        <i class="fas fa-copy"></i>
                                        <span class="hidden sm:inline">Copiar</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            {{-- Botones --}}
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#5a31d7] text-white rounded-xl hover:bg-[#7b5ce0] hover:shadow-lg hover:shadow-[#5a31d7]/20 transition-all text-sm font-semibold">
                    <i class="fas fa-save"></i> Guardar configuracion
                </button>

                <a href="{{ route('empresa.agenda', $empresa->id) }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-[#3B4269] border border-gray-200 rounded-xl hover:bg-gray-50 transition-all text-sm font-medium">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    /* Toggle switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 36px;
        height: 20px;
        cursor: pointer;
    }
    .toggle-track {
        position: absolute;
        inset: 0;
        background: #d1d5db;
        border-radius: 999px;
        transition: background 0.2s;
    }
    .toggle-track::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }
    .dia-activo:checked + .toggle-track {
        background: #5a31d7;
    }
    .dia-activo:checked + .toggle-track::after {
        transform: translateX(16px);
    }

    /* Fila inactiva */
    .horario-row.inactivo .dia-inicio,
    .horario-row.inactivo .dia-fin {
        opacity: 0.4;
    }

    /* FullCalendar */
    .fc {
        --fc-border-color: #f0f0f5;
        --fc-today-bg-color: rgba(90, 49, 215, 0.04);
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

    .fc a {
        text-decoration: none !important;
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
        background-color: #fee2e2;
        border-radius: 6px;
    }

    /* Dias bloqueados */
    .fc .fc-bg-event {
        background: repeating-linear-gradient(
            -45deg,
            rgba(220, 38, 38, 0.12),
            rgba(220, 38, 38, 0.12) 4px,
            rgba(220, 38, 38, 0.04) 4px,
            rgba(220, 38, 38, 0.04) 8px
        ) !important;
        border: 2px solid rgba(220, 38, 38, 0.25) !important;
        border-radius: 6px !important;
        opacity: 1 !important;
    }

    .fc .fc-daygrid-day.bloqueado-day {
        position: relative;
    }

    .fc .fc-daygrid-day.bloqueado-day .fc-daygrid-day-number {
        color: #dc2626;
        font-weight: 700;
    }

    .fc .fc-daygrid-day.bloqueado-day .fc-daygrid-day-frame {
        background: rgba(220, 38, 38, 0.03);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fechasSeleccionadas = @json($fechasBloqueadas ?? []);
        const calendarEl = document.getElementById('calendar');

        function actualizarContador() {
            document.getElementById('num-bloqueados').textContent = fechasSeleccionadas.length;
        }

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
                backgroundColor: 'rgba(220, 38, 38, 0.12)'
            })),
            eventDisplay: 'background',
            dayCellDidMount: function(info) {
                const dateStr = info.date.toISOString().split('T')[0];
                if (fechasSeleccionadas.includes(dateStr)) {
                    info.el.classList.add('bloqueado-day');
                }
            },
            height: 650,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            }
        });

        function actualizarEventos() {
            calendar.removeAllEvents();

            document.querySelectorAll('.bloqueado-day').forEach(el => el.classList.remove('bloqueado-day'));

            fechasSeleccionadas.forEach(fecha => {
                calendar.addEvent({
                    start: fecha,
                    display: 'background',
                    backgroundColor: 'rgba(220, 38, 38, 0.12)'
                });

                const cell = document.querySelector(`[data-date="${fecha}"]`);
                if (cell) cell.classList.add('bloqueado-day');
            });

            document.getElementById('fechas_bloqueadas').value = fechasSeleccionadas.join(',');
            actualizarContador();
        }

        calendar.render();
        actualizarEventos();

        // Toggle visual de filas activas/inactivas
        document.querySelectorAll('.dia-activo').forEach(cb => {
            cb.addEventListener('change', function() {
                const row = this.closest('.horario-row');
                row.classList.toggle('activo', this.checked);
                row.classList.toggle('inactivo', !this.checked);
            });
        });

        // Auto-activar al poner horario
        document.querySelectorAll('.dia-inicio, .dia-fin').forEach(input => {
            input.addEventListener('change', function() {
                const fila = this.closest('tr');
                const checkbox = fila.querySelector('.dia-activo');
                if (this.value) {
                    checkbox.checked = true;
                    fila.classList.add('activo');
                    fila.classList.remove('inactivo');
                }
            });
        });
    });

    function clonarDiaAnterior(diaActual) {
        const diaAnterior = diaActual - 1;
        const filaAnterior = document.querySelector(`tr[data-dia="${diaAnterior}"]`);
        const filaActual = document.querySelector(`tr[data-dia="${diaActual}"]`);

        if (!filaAnterior || !filaActual) return;

        const wasChecked = filaAnterior.querySelector('.dia-activo').checked;
        filaActual.querySelector('.dia-activo').checked = wasChecked;
        filaActual.querySelector('.dia-inicio').value = filaAnterior.querySelector('.dia-inicio').value;
        filaActual.querySelector('.dia-fin').value = filaAnterior.querySelector('.dia-fin').value;

        filaActual.classList.toggle('activo', wasChecked);
        filaActual.classList.toggle('inactivo', !wasChecked);
    }
</script>
@endpush
