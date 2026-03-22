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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    /* ===== PAGE LAYOUT ===== */
    .config-page { padding: 1.5rem; color: #3B4269; }
    .config-breadcrumbs { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #9ca3af; margin-bottom: 0.75rem; }
    .config-breadcrumbs a { color: #5a31d7; text-decoration: none; font-weight: 500; }
    .config-breadcrumbs a:hover { text-decoration: underline; }
    .config-breadcrumbs .sep { font-size: 0.55rem; color: #d1d5db; }

    .config-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .config-title { font-size: 1.25rem; font-weight: 700; color: #5a31d7; margin: 0; display: flex; align-items: center; gap: 8px; }
    .config-subtitle { font-size: 0.78rem; color: #9ca3af; margin: 2px 0 0 0; }

    /* ===== CARDS ===== */
    .config-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; margin-bottom: 1rem; }
    .config-card-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-bottom: 1px solid #f3f4f6; }
    .config-card-title { font-size: 0.85rem; font-weight: 600; color: #3B4269; margin: 0; }
    .config-card-desc { font-size: 0.72rem; color: #9ca3af; margin: 1px 0 0 0; }
    .config-card-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; color: #ef4444; background: #fef2f2; padding: 3px 10px; border-radius: 100px; font-weight: 600; }
    .config-card-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #ef4444; }

    /* ===== CALENDAR ===== */
    .config-calendar { padding: 10px; }

    /* ===== HORARIO TABLE ===== */
    .horario-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .horario-table thead tr { background: #f9f8fc; }
    .horario-table th { padding: 8px 12px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #5a31d7; text-align: left; }
    .horario-table th.th-center { text-align: center; }
    .horario-table td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; }
    .horario-table tr:last-child td { border-bottom: none; }
    .horario-table tr:hover { background: #faf9fd; }
    .horario-day-name { font-weight: 500; color: #3B4269; font-size: 0.82rem; }
    .horario-time-input {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 8px;
        font-size: 0.8rem;
        width: 100%;
        max-width: 120px;
        font-family: inherit;
        color: #3B4269;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .horario-time-input:focus { outline: none; border-color: #5a31d7; box-shadow: 0 0 0 2px rgba(90,49,215,0.15); }
    .horario-btn-copy {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 6px; border: none;
        background: transparent; color: #5a31d7; font-size: 0.72rem;
        font-weight: 500; cursor: pointer; transition: all 0.15s;
    }
    .horario-btn-copy:hover { background: #5a31d7; color: #fff; }

    /* Toggle switch */
    .toggle-switch { position: relative; display: inline-block; width: 34px; height: 18px; cursor: pointer; }
    .toggle-track { position: absolute; inset: 0; background: #d1d5db; border-radius: 999px; transition: background 0.2s; }
    .toggle-track::after {
        content: ''; position: absolute; top: 2px; left: 2px;
        width: 14px; height: 14px; background: white; border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15); transition: transform 0.2s;
    }
    .dia-activo:checked + .toggle-track { background: #5a31d7; }
    .dia-activo:checked + .toggle-track::after { transform: translateX(16px); }
    .horario-row.inactivo .dia-inicio,
    .horario-row.inactivo .dia-fin { opacity: 0.35; }

    /* ===== BUTTONS ===== */
    .config-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 0.5rem; }
    .config-btn-save {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; background: #5a31d7; color: #fff;
        border: none; border-radius: 10px; font-size: 0.82rem;
        font-weight: 600; cursor: pointer; transition: background 0.2s;
        font-family: inherit;
    }
    .config-btn-save:hover { background: #7b5ce0; }
    .config-btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; background: #fff; color: #3B4269;
        border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.82rem;
        font-weight: 500; text-decoration: none; transition: all 0.2s;
    }
    .config-btn-back:hover { background: #f9fafb; border-color: #d1d5db; }

    /* ===== FULLCALENDAR OVERRIDES ===== */
    .fc { --fc-border-color: #f0f0f5; --fc-today-bg-color: rgba(90,49,215,0.04); font-family: inherit; }
    .fc .fc-toolbar-title { font-size: 0.95rem; font-weight: 600; color: #3B4269; }
    .fc .fc-button {
        background: white; border: 1px solid #e5e7eb; padding: 4px 10px;
        border-radius: 8px; font-size: 0.7rem; font-weight: 500; color: #3B4269;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .fc .fc-button:hover { background: #5a31d7; border-color: #5a31d7; color: white; }
    .fc .fc-button-active { background: #5a31d7 !important; border-color: #5a31d7 !important; color: white !important; }
    .fc a { text-decoration: none !important; }
    .fc .fc-col-header-cell-cushion { color: #9c9cb9; font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .fc .fc-daygrid-day-number { color: #3B4269; font-weight: 500; font-size: 0.75rem; }
    .fc .fc-day-today .fc-daygrid-day-number {
        background: #5a31d7; color: white; border-radius: 50%;
        width: 24px; height: 24px; display: inline-flex;
        align-items: center; justify-content: center; margin: 2px;
    }
    .fc .fc-daygrid-event { background-color: #fee2e2; border-radius: 6px; }
    .fc .fc-bg-event {
        background: repeating-linear-gradient(-45deg, rgba(220,38,38,0.12), rgba(220,38,38,0.12) 4px, rgba(220,38,38,0.04) 4px, rgba(220,38,38,0.04) 8px) !important;
        border: 2px solid rgba(220,38,38,0.25) !important; border-radius: 6px !important; opacity: 1 !important;
    }
    .fc .fc-daygrid-day.bloqueado-day { position: relative; }
    .fc .fc-daygrid-day.bloqueado-day .fc-daygrid-day-number { color: #dc2626; font-weight: 700; }
    .fc .fc-daygrid-day.bloqueado-day .fc-daygrid-day-frame { background: rgba(220,38,38,0.03); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 767px) {
        .config-page { padding: 1rem 12px; }
        .config-header { gap: 8px; }
        .config-title { font-size: 1.05rem; }

        .config-calendar { padding: 6px; }
        .fc .fc-toolbar { flex-direction: column; gap: 6px; }
        .fc .fc-toolbar-title { font-size: 0.85rem; }
        .fc .fc-button { padding: 4px 8px; font-size: 0.65rem; }
        .fc .fc-daygrid-day-number { font-size: 0.68rem; }
        .fc .fc-col-header-cell-cushion { font-size: 0.58rem; }
        .fc .fc-day-today .fc-daygrid-day-number { width: 20px; height: 20px; font-size: 0.65rem; }

        /* Table -> stacked cards on mobile */
        .horario-table thead { display: none; }
        .horario-table,
        .horario-table tbody,
        .horario-table tr,
        .horario-table td { display: block; width: 100%; }
        .horario-table tr {
            padding: 10px 12px; margin-bottom: 6px;
            border: 1px solid #f3f4f6; border-radius: 10px;
            background: #fff;
        }
        .horario-table tr:hover { background: #faf9fd; }
        .horario-table td { padding: 3px 0; border-bottom: none; }
        .horario-table td:first-child { margin-bottom: 4px; }

        /* Horario row as flex on mobile */
        .horario-row-mobile-grid {
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .horario-time-input { max-width: none; min-height: 40px; }

        .config-actions { flex-direction: column; }
        .config-btn-save, .config-btn-back { width: 100%; justify-content: center; min-height: 44px; }
    }
</style>
@endpush

@section('content')
<div class="config-page">

    {{-- Breadcrumbs --}}
    <nav class="config-breadcrumbs">
        <a href="{{ route('empresa.dashboard', $empresa->id) }}">
            <i class="fas fa-home" style="font-size:0.7rem;"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right sep"></i>
        <a href="{{ route('empresa.agenda', $empresa->id) }}">Agenda</a>
        <i class="fas fa-chevron-right sep"></i>
        <span style="color:#374151; font-weight:500;">Configurar</span>
    </nav>

    {{-- Header --}}
    <div class="config-header">
        <div>
            <h1 class="config-title">
                <i class="fas fa-cog"></i> Configurar Agenda
            </h1>
            <p class="config-subtitle">Fechas bloqueadas y horarios laborales.</p>
        </div>
    </div>

    <form action="{{ route('agenda.guardar_bloqueados', $empresa->id) }}" method="POST">
        @csrf
        <input type="hidden" name="fechas_bloqueadas" id="fechas_bloqueadas">

        {{-- Calendario de fechas bloqueadas --}}
        <div class="config-card">
            <div class="config-card-header">
                <div>
                    <h2 class="config-card-title">Fechas bloqueadas</h2>
                    <p class="config-card-desc">Haz clic en los dias que deseas bloquear.</p>
                </div>
                <span class="config-card-badge" id="contador-bloqueados">
                    <span class="config-card-badge-dot"></span>
                    <span id="num-bloqueados">0</span> bloqueados
                </span>
            </div>
            <div class="config-calendar" id="calendar"></div>
        </div>

        {{-- Horarios Laborales --}}
        <div class="config-card">
            <div class="config-card-header">
                <div>
                    <h2 class="config-card-title">Horario laboral por dia</h2>
                    <p class="config-card-desc">Apertura y cierre para cada dia.</p>
                </div>
            </div>
            <table class="horario-table">
                <thead>
                    <tr>
                        <th>Dia</th>
                        <th class="th-center">Estado</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diasSemana as $numero => $nombre)
                    @php
                    $diaConvertido = $numero % 7;
                    $horario = $horarios->firstWhere('dia_semana', $diaConvertido);
                    $isActive = $horario && $horario->activo;
                    @endphp
                    <tr data-dia="{{ $numero }}" class="horario-row {{ $isActive ? 'activo' : 'inactivo' }}">
                        <td>
                            <span class="horario-day-name">{{ $nombre }}</span>
                        </td>
                        <td style="text-align:center;">
                            <label class="toggle-switch">
                                <input type="checkbox" name="dias_laborales[{{ $numero }}][activo]" value="1"
                                    class="dia-activo sr-only"
                                    {{ $isActive ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                            </label>
                        </td>
                        <td>
                            <input type="time" name="dias_laborales[{{ $numero }}][inicio]"
                                class="horario-time-input dia-inicio"
                                value="{{ $horario->hora_inicio ?? '' }}">
                        </td>
                        <td>
                            <input type="time" name="dias_laborales[{{ $numero }}][fin]"
                                class="horario-time-input dia-fin"
                                value="{{ $horario->hora_fin ?? '' }}">
                        </td>
                        <td style="text-align:right;">
                            @if($numero > 1)
                                <button type="button" onclick="clonarDiaAnterior({{ $numero }})"
                                    class="horario-btn-copy" title="Copiar horario del dia anterior">
                                    <i class="fas fa-copy"></i>
                                    <span class="hidden sm:inline">Copiar</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Botones --}}
        <div class="config-actions">
            <button type="submit" class="config-btn-save">
                <i class="fas fa-save"></i> Guardar configuracion
            </button>
            <a href="{{ route('empresa.agenda', $empresa->id) }}" class="config-btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </form>
</div>
@endsection

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
            height: 'auto',
            contentHeight: 'auto',
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
