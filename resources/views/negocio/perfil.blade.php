@extends('layouts.app')

@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
<style>
    body::before,
    body::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
        pointer-events: none;
    }
    body::before {
        background:
            radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28), transparent 60%),
            radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25), transparent 60%),
            radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3), transparent 60%),
            radial-gradient(circle 130px at 70% 35%, rgba(90, 78, 187, 0.3), transparent 60%),
            radial-gradient(circle 180px at 85% 10%, rgba(126, 121, 201, 0.25), transparent 60%),
            radial-gradient(circle 100px at 20% 75%, rgba(90, 78, 187, 0.22), transparent 60%),
            radial-gradient(circle 120px at 45% 90%, rgba(126, 121, 201, 0.26), transparent 60%),
            radial-gradient(circle 140px at 80% 80%, rgba(90, 78, 187, 0.3), transparent 60%);
        animation: bubblesBefore 18s ease-in-out infinite;
    }
    body::after {
        background:
            radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2), transparent 60%),
            radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28), transparent 60%),
            radial-gradient(circle 120px at 55% 45%, rgba(126, 121, 201, 0.24), transparent 60%),
            radial-gradient(circle 140px at 75% 55%, rgba(90, 78, 187, 0.22), transparent 60%),
            radial-gradient(circle 160px at 90% 35%, rgba(126, 121, 201, 0.23), transparent 60%),
            radial-gradient(circle 100px at 10% 85%, rgba(90, 78, 187, 0.2), transparent 60%),
            radial-gradient(circle 150px at 40% 10%, rgba(126, 121, 201, 0.28), transparent 60%),
            radial-gradient(circle 130px at 60% 85%, rgba(90, 78, 187, 0.25), transparent 60%);
        animation: bubblesAfter 22s ease-in-out infinite reverse;
    }
    @keyframes bubblesBefore { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-30px); } }
    @keyframes bubblesAfter  { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(30px); } }

    /* Estilos para días pasados en el calendario */
    .fc-day-past {
        background-color: #f3f4f6 !important;
        opacity: 0.6;
        cursor: not-allowed !important;
    }
    .fc-day-past .fc-daygrid-day-number {
        color: #9ca3af !important;
        text-decoration: line-through;
    }

    /* Estilos para días bloqueados */
    .fc-day-blocked {
        background-color: #fee2e2 !important;
        cursor: not-allowed !important;
    }
    .fc-day-blocked .fc-daygrid-day-number {
        color: #dc2626 !important;
        font-weight: bold;
    }

    /* Días disponibles - hover effect */
    .fc .fc-daygrid-day:not(.fc-day-past):not(.fc-day-blocked):hover {
        background-color: #e0e7ff !important;
        cursor: pointer;
    }

    /* Estilos para eventos de citas en el calendario */
    .fc-event {
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .fc-event:hover {
        opacity: 0.85;
    }

    /* Estilos según estado de la cita */
    .fc-event[style*="rgb(99, 102, 241)"] { /* Pendiente - Indigo */
        border-left: 3px solid #4f46e5;
    }
    .fc-event[style*="rgb(16, 185, 129)"] { /* Confirmada - Verde */
        border-left: 3px solid #059669;
    }
    .fc-event[style*="rgb(239, 68, 68)"] { /* Cancelada - Rojo */
        border-left: 3px solid #dc2626;
        opacity: 0.7;
    }

    /* Tooltip para eventos de citas */
    .fc-event-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

{{-- Botón carrito flotante --}}
<div class="fixed top-6 right-6 z-50">
    <button id="carritoButton" class="relative bg-[#4a5eaa] text-white px-4 py-2 rounded-full shadow-lg hover:bg-[#3d4e94] transition">
        🛒 Carrito
        <span id="carritoCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">0</span>
    </button>
</div>

{{-- Modales --}}
@include('empresa.partials.carrito-modal', ['empresa' => $negocio])
@include('empresa.partials.modal-agendar', [
  'negocio'      => $negocio,
  'servicios'    => $negocio->servicios,   // opcional: ya llegan por relación
  'trabajadores' => $trabajadores          // ← NUEVO
])


<div class="py-10 relative z-10">
    <div class="max-w-6xl mx-auto">

        {{-- Portada --}}
        <div class="relative w-full h-80 rounded-2xl overflow-hidden shadow-2xl mb-14 bg-[#4a5eaa]">
            @if($negocio->neg_portada)
                <img src="{{ $negocio->neg_portada }}" class="absolute inset-0 w-full h-full object-cover" alt="Portada">
            @endif
        </div>

        {{-- Encabezado --}}
        <div class="relative -mt-32 flex items-center gap-8 px-10 pb-10">
            <div class="shrink-0">
                <img src="{{ $negocio->neg_imagen ?? '/images/default-user.png' }}"
                     class="w-40 h-40 rounded-full border-4 border-white object-cover shadow-xl bg-white"
                     alt="Avatar">
            </div>
            <div>
                <h1 class="text-4xl font-extrabold text-[#4a5eaa]">{{ $negocio->neg_nombre_comercial }}</h1>
                <p class="text-lg text-[#3B4269B3]">{{ $negocio->neg_categoria }}</p>
            </div>
        </div>

        {{-- Secciones --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-10">
            <div class="space-y-6">
                {{-- Información --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">📄 Información del negocio</h3>
                    <ul class="text-base text-gray-700 space-y-2">
                        <li><strong>Email:</strong> {{ $negocio->neg_email }}</li>
                        <li><strong>Teléfono:</strong> {{ $negocio->neg_telefono }}</li>
                        <li><strong>Dirección:</strong> {{ $negocio->neg_direccion }}</li>
                    </ul>
                </div>

                {{-- Servicios --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">💼 Servicios</h3>
                    @if($negocio->servicios->count())
                        <ul class="list-disc pl-5 text-base text-gray-700 space-y-2">
                            @foreach($negocio->servicios as $servicio)
                                <li class="flex justify-between items-center">
                                    <span>{{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-base text-gray-500">Este negocio aún no tiene servicios registrados.</p>
                    @endif
                </div>

                {{-- Productos --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">🛒 Productos</h3>
                    @if($negocio->productos && $negocio->productos->count())
                        <ul class="divide-y divide-gray-200">
                            @foreach($negocio->productos as $producto)
                                <li class="py-3">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $producto->imagen ?? '/images/product-placeholder.png' }}"
                                             class="w-14 h-14 object-cover rounded-md border"
                                             alt="{{ $producto->nombre }}">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800">{{ $producto->nombre }}</h4>
                                            <p class="text-sm text-gray-600">{{ $producto->descripcion_breve }}</p>
                                            <div class="flex items-center justify-between mt-1">
                                                <p class="text-sm text-[#4a5eaa] font-semibold">
                                                    ${{ number_format($producto->precio_venta, 0, ',', '.') }}
                                                    @if($producto->activar_oferta && $producto->precio_promocional)
                                                        <span class="text-sm line-through text-gray-400 ml-2">
                                                            ${{ number_format($producto->precio_promocional, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </p>
                                                <button class="agregar-carrito bg-green-500 hover:bg-green-600 text-white text-xs px-2 py-1 rounded"
                                                        data-tipo="producto"
                                                        data-id="{{ $producto->id }}"
                                                        data-nombre="{{ $producto->nombre }}"
                                                        data-precio="{{ $producto->precio_venta }}">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-base text-gray-500">Este negocio aún no tiene productos registrados.</p>
                    @endif
                </div>
            </div>

            {{-- Horarios y Calendario --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- 📆 Citas del día (NUEVO) --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-[#4a5eaa]">📆 Citas del día</h3>
                        <input type="date" id="fechaCitas" class="border rounded-lg px-3 py-2"
                               value="{{ now()->toDateString() }}">
                    </div>
                    <div id="citasDiaVacio" class="text-gray-500 hidden">No hay citas para esta fecha.</div>
                    <ul id="listaCitasDia" class="divide-y">
                        {{-- se llena por JS --}}
                    </ul>
                </div>

                {{-- Horarios --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">⏰ Horarios de Atención</h3>
                    <table class="w-full text-base">
                        <thead>
                            <tr class="text-gray-600 border-b">
                                <th class="py-2 text-left">Día</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($negocio->horarios as $h)
                                <tr class="border-b text-gray-700">
                                    <td class="py-2">
                                        {{ \Carbon\Carbon::create()->startOfWeek()->addDays($h->dia_semana - 1)->locale('es')->isoFormat('dddd') }}
                                    </td>
                                    <td>{{ $h->hora_inicio ?? '—' }}</td>
                                    <td>{{ $h->hora_fin ?? '—' }}</td>
                                    <td>
                                        {!! $h->activo ? '<span class="text-green-600 font-semibold">Sí</span>' : '<span class="text-red-600">No</span>' !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Calendario --}}
                @if($negocio->bloqueos->count())
                    <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                        <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">📅 Días Bloqueados</h3>
                        <div id="calendarioBloqueos" class="rounded overflow-hidden"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- 📦 Horarios + NegocioID para JS --}}
@php
    $horariosMap = $negocio->horarios
        ->where('activo', 1)
        ->groupBy('dia_semana')
        ->map(function ($items) {
            return $items->map(function ($h) {
                return [
                    'inicio' => $h->hora_inicio ? substr($h->hora_inicio, 0, 5) : null,
                    'fin'    => $h->hora_fin    ? substr($h->hora_fin, 0, 5) : null,
                ];
            })->values();
        })
        ->toArray();
@endphp

<div id="horariosData" data-map='@json($horariosMap)'></div>
<div id="agendaData" data-negocio-id="{{ $negocio->id }}"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // ================== ⏱️ Horarios desde data-atributo ==================
  // <div id="horariosData" data-map='{"1":[{"inicio":"08:00","fin":"17:00"}], ...}'></div>
  const horariosEl = document.getElementById('horariosData');
  let HORARIOS = {};
  if (horariosEl && typeof horariosEl.dataset.map === 'string') {
    try { HORARIOS = JSON.parse(horariosEl.dataset.map || '{}'); }
    catch { console.warn('horariosData data-map no es JSON válido'); HORARIOS = {}; }
  }

  // Normalización HH:MM (acepta inicio/fin u hora_inicio/hora_fin)
  const fix = v => (v||'').toString().slice(0,5);
  const normalizeRangeArray = arr => (Array.isArray(arr) ? arr : [])
    .map(r => ({ inicio: fix(r.inicio ?? r.hora_inicio), fin: fix(r.fin ?? r.hora_fin) }))
    .filter(r => r.inicio && r.fin);

  // Normaliza el objeto 1..7 -> [{inicio,fin}, ...]
  const NORM = {};
  Object.keys(HORARIOS).forEach(k => { NORM[k] = normalizeRangeArray(HORARIOS[k]); });

  // Horarios del negocio (global)
  window.NEGOCIO_HORARIOS = NORM;

  // ================== 🔎 Datos de agenda (negocio) ==================
  // Asegúrate de tener: <div id="agendaData" data-negocio-id="{{ $negocio->id }}"></div>
  const agendaEl   = document.getElementById('agendaData');
  const NEGOCIO_ID = agendaEl?.dataset?.negocioId || null;

  // ================== 🧩 Inicialización de estructuras globales ==================
  // Soporte para horarios/bloqueos por trabajador (si más adelante los cargas)
  window.TRABAJADOR_HORARIOS = window.TRABAJADOR_HORARIOS || {}; // { [trabajador_id]: { [1..7]: [{inicio,fin}] } }
  window.TRABAJADOR_BLOQUEOS = window.TRABAJADOR_BLOQUEOS || {}; // { [trabajador_id]: ['YYYY-MM-DD', ...] }

  // Mapa de reservas por fecha y trabajador:
  // window.RESERVAS['YYYY-MM-DD'][trabajador_id] = [{inicio,fin}, ...]
  window.RESERVAS = window.RESERVAS || {};

  // ================== 🧠 Helpers compartidos (globales) ==================
  const pad2  = n => String(n).padStart(2, '0');
  const toYMD = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;

  window.t2m = function t2m(hhmm) {
    const [h,m] = (hhmm||'0:0').split(':').map(Number);
    return h*60 + m;
  };
  window.m2t = function m2t(total) {
    const h = Math.floor(total/60), m = total%60;
    const pad = n => String(n).padStart(2,'0');
    return `${pad(h)}:${pad(m)}`;
  };
  window.normalizeIntervals = function normalizeIntervals(raw) {
    return (raw||[])
      .map(r => ({ inicio: fix(r.inicio ?? r.hora_inicio), fin: fix(r.fin ?? r.hora_fin) }))
      .filter(r => r.inicio && r.fin && r.fin > r.inicio);
  };
  window.overlapsAny = function overlapsAny(aStart, aEnd, intervals) {
    const A = t2m(aStart), B = t2m(aEnd);
    for (const it of (intervals||[])) {
      const C = t2m(it.inicio), D = t2m(it.fin);
      if (A < D && C < B) return true; // intersección abierta
    }
    return false;
  };
  window.generateFreeQuarterSlots = function generateFreeQuarterSlots(minHHMM, maxHHMM, reservas) {
    const out = [];
    const min = t2m(minHHMM), max = t2m(maxHHMM);
    for (let t = min; t <= max; t += 15) {
      const s = m2t(t);
      if (!overlapsAny(s, m2t(t+15), reservas)) out.push(s);
    }
    return out;
  };
  window.generateValidEnds = function generateValidEnds(startHHMM, maxHHMM, reservas) {
    const out = [];
    const start = t2m(startHHMM), max = t2m(maxHHMM);
    for (let t = start + 15; t <= max; t += 15) {
      const cand = m2t(t);
      if (!overlapsAny(startHHMM, cand, reservas)) out.push(cand);
    }
    return out;
  };

  // ================== 📆 Citas del día (panel opcional) ==================
  const fechaInput = document.getElementById('fechaCitas');
  const ulCitas    = document.getElementById('listaCitasDia');
  const vacioLabel = document.getElementById('citasDiaVacio');

  async function cargarCitasDelDia(fecha) {
    if (!NEGOCIO_ID) return;
    try {
      const res  = await fetch(`/negocios/${NEGOCIO_ID}/agenda/citas-dia?fecha=${fecha}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const json = await res.json();

      // 🧩 Hidratar RESERVAS por trabajador:
      // Espera items con { trabajador_id, hora_inicio, hora_fin }
      window.RESERVAS[fecha] = window.RESERVAS[fecha] || {};
      // Limpia día previo
      Object.keys(window.RESERVAS[fecha]).forEach(k => delete window.RESERVAS[fecha][k]);

      const items = Array.isArray(json.items) ? json.items : [];
      for (const c of items) {
        const tid = c.trabajador_id ?? '0';
        if (!window.RESERVAS[fecha][tid]) window.RESERVAS[fecha][tid] = [];
        window.RESERVAS[fecha][tid].push({
          inicio: (c.hora_inicio || '').toString().slice(0,5),
          fin:    (c.hora_fin    || '').toString().slice(0,5),
        });
      }

      // --- Panel lateral (visual) ---
      if (ulCitas && vacioLabel) {
        ulCitas.innerHTML = '';
        if (!items.length) {
          vacioLabel.classList.remove('hidden');
        } else {
          vacioLabel.classList.add('hidden');
          for (const c of items) {
            const li = document.createElement('li');
            li.className = 'py-3 flex items-start justify-between';
            li.innerHTML = `
              <div>
                <div class="font-semibold text-gray-800">${c.nombre_cliente ?? 'Cita'}</div>
                <div class="text-sm text-gray-600">
                  ${c.hora_inicio} – ${c.hora_fin}${c.estado ? ` · <span class="uppercase">${c.estado}</span>` : ''}
                </div>
                ${c.notas ? `<div class="text-sm text-gray-500 mt-1">${c.notas}</div>` : ''}
              </div>
            `;
            ulCitas.appendChild(li);
          }
        }
      }

      // --- FullCalendar: NO dibujamos eventos aquí, ya se cargan con cargarCitasMes ---
      // Las citas ya están cargadas en el calendario y no necesitamos duplicarlas
    } catch (e) {
      console.error('Error cargando citas del día:', e);
    }
  }

  // ================== 🗓️ Calendario (bloqueos + integración con citas del día) ==================
  const calendarEl = document.getElementById('calendarioBloqueos');
  let calendar;

  // Función para cargar citas del mes
  async function cargarCitasMes(year, month) {
    if (!NEGOCIO_ID) return;
    try {
      const res = await fetch(`/negocios/${NEGOCIO_ID}/agenda/citas-mes?year=${year}&month=${month}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const json = await res.json();

      if (json.ok && Array.isArray(json.events)) {
        // Remover eventos de citas previas
        if (window.calendar) {
          window.calendar.getEvents()
            .filter(ev => ev.extendedProps?.type === 'cita')
            .forEach(ev => ev.remove());

          // Agregar nuevos eventos de citas
          json.events.forEach(ev => {
            window.calendar.addEvent(ev);
          });
        }
      }
    } catch (e) {
      console.error('Error cargando citas del mes:', e);
    }
  }

  if (calendarEl) {
    calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      height: 500,
      headerToolbar: { left: 'prev,next today', center: 'title', right: '' },

      // Eventos iniciales (bloqueos)
      events: [
        @foreach($negocio->bloqueos as $bloqueo)
        {
          title: 'Bloqueado',
          start: '{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',
          allDay: true,
          color: '#dc2626',
          extendedProps: { blocked: true }
        },
        @endforeach
      ],

      // 🎨 Estilos personalizados para cada día
      dayCellClassNames: function(info) {
        const today = new Date();
        today.setHours(0,0,0,0);
        const cellDate = new Date(info.date);
        cellDate.setHours(0,0,0,0);

        const ymd = toYMD(cellDate);
        const blocked = calendar.getEvents().some(ev => ev.extendedProps?.blocked && toYMD(ev.start) === ymd);

        // Día pasado
        if (cellDate < today) {
          return ['fc-day-past'];
        }
        // Día bloqueado
        if (blocked) {
          return ['fc-day-blocked'];
        }
        return [];
      },

      // 🔄 Cargar citas cuando cambia el mes
      datesSet: function(dateInfo) {
        const year = dateInfo.start.getFullYear();
        const month = dateInfo.start.getMonth() + 1;
        cargarCitasMes(year, month);
      },

      // 👉 Click en un día (espera reservas por trabajador antes de abrir modal)
      dateClick: async function(info) {
        const ymd = toYMD(info.date);
        const today = new Date(); today.setHours(0,0,0,0);
        const clicked = new Date(info.date); clicked.setHours(0,0,0,0);

        // No permitir clicks en días pasados
        if (clicked < today) return;

        // No permitir clicks en días bloqueados
        const blocked = calendar.getEvents().some(ev => ev.extendedProps?.blocked && toYMD(ev.start) === ymd);
        if (blocked) return;

        // Cargar reservas del día para control de horarios (sin afectar eventos visuales)
        if (fechaInput) fechaInput.value = ymd;
        await cargarCitasDelDia(ymd); // ← importante para tener RESERVAS[ymd] listas

        // Abrir modal de agendar
        if (typeof window.openAgendarModal === 'function') {
          window.openAgendarModal(ymd);
        } else {
          // Fallback mínimo si no está la función
          const modal = document.getElementById('modalAgendar');
          const fechaHidden = document.getElementById('agendarFecha');
          const fechaLabel  = document.getElementById('agendarFechaLabel');
          if (fechaHidden) fechaHidden.value = ymd;
          if (fechaLabel)  fechaLabel.value  = `${ymd.split('-')[1]}/${ymd.split('-')[2]}/${ymd.split('-')[0]}`;
          modal?.classList.remove('hidden');
          modal?.classList.add('flex');
        }
      },
    });

    calendar.render();
    window.calendar = calendar;

    // Carga inicial (hoy) si existe el input y el negocio
    if (fechaInput && NEGOCIO_ID) {
      const hoy = fechaInput.value || toYMD(new Date());
      cargarCitasDelDia(hoy);
    }
  } else {
    if (fechaInput && NEGOCIO_ID) {
      cargarCitasDelDia(fechaInput.value || toYMD(new Date()));
    }
  }

  // ================== 🛒 Carrito ==================
  let carrito = JSON.parse(localStorage.getItem('carritoNegocio')) || [];
  const modalCarrito = document.getElementById('modalCarrito');
  const lista = document.getElementById('carritoItems');
  const total = document.getElementById('carritoTotal');
  const count = document.getElementById('carritoCount');
  const inputHidden = document.getElementById('carritoJsonInput');

  function guardarCarrito() { localStorage.setItem('carritoNegocio', JSON.stringify(carrito)); }

  function actualizarCarrito() {
    if (!lista) return;
    lista.innerHTML = '';
    let suma = 0;

    if (carrito.length === 0) {
      lista.innerHTML = `<li class="text-gray-500 text-sm text-center py-4">Tu carrito está vacío.</li>`;
    }

    carrito.forEach((item, index) => {
      const subtotal = (Number(item.precio) || 0) * (Number(item.cantidad) || 0);
      suma += subtotal;

      const li = document.createElement('li');
      li.className = 'py-2 flex justify-between items-center border-b border-gray-100';
      li.innerHTML = `
        <div>
          <span class="font-medium">${item.nombre}</span>
          <span class="ml-2 text-gray-500 text-sm">($${Number(item.precio).toLocaleString()} x ${item.cantidad})</span><br>
          <span class="text-xs text-gray-600">Subtotal: $${subtotal.toLocaleString()}</span>
        </div>
        <button data-index="${index}" class="eliminar-item text-red-500 hover:text-red-700 text-sm">Quitar</button>
      `;
      lista.appendChild(li);
    });

    if (total) total.textContent = '$' + suma.toLocaleString();
    if (count) count.textContent = carrito.length;
    if (inputHidden) inputHidden.value = JSON.stringify(carrito);
    guardarCarrito();
  }

  // ➕ Agregar al carrito
  document.querySelectorAll('.agregar-carrito').forEach(btn => {
    btn.addEventListener('click', function () {
      const nuevoItem = {
        id: this.dataset.id,
        nombre: this.dataset.nombre,
        precio: parseFloat(this.dataset.precio),
        tipo: this.dataset.tipo,
        cantidad: parseInt(this.dataset.cantidad || '1')
      };

      const existente = carrito.find(item => item.id === nuevoItem.id && item.tipo === nuevoItem.tipo);
      if (existente) existente.cantidad += nuevoItem.cantidad;
      else carrito.push(nuevoItem);

      actualizarCarrito();

      const toast = document.createElement('div');
      toast.textContent = `${nuevoItem.nombre} agregado al carrito`;
      toast.className = 'fixed bottom-6 right-6 bg-black/80 text-white text-sm px-4 py-2 rounded shadow-lg z-50 animate-bounce';
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 2000);
    });
  });

  // 🧹 Eliminar del carrito
  lista?.addEventListener('click', function (e) {
    if (e.target.classList.contains('eliminar-item')) {
      const index = parseInt(e.target.dataset.index);
      carrito.splice(index, 1);
      actualizarCarrito();
    }
  });

  // 🪟 Abrir/Cerrar modal carrito
  document.getElementById('carritoButton')?.addEventListener('click', () => {
    modalCarrito?.classList.remove('hidden');
  });
  document.getElementById('cerrarModalCarrito')?.addEventListener('click', () => {
    modalCarrito?.classList.add('hidden');
  });

  // =============== MODAL CHECKOUT (AJAX) ===============
  let checkoutMount = document.getElementById('checkoutModalMount');
  if (!checkoutMount) {
    checkoutMount = document.createElement('div');
    checkoutMount.id = 'checkoutModalMount';
    document.body.appendChild(checkoutMount);
  }

  function openCheckoutModal() {
    const m = document.getElementById('modalCheckout');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); bindCheckoutModalEvents(); }
  }
  function closeCheckoutModal() {
    const m = document.getElementById('modalCheckout');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
  }
  function bindCheckoutModalEvents() {
    const modal = document.getElementById('modalCheckout');
    if (!modal) return;

    modal.querySelectorAll('[data-close-checkout]').forEach(btn => btn.addEventListener('click', closeCheckoutModal));

    const innerForm = modal.querySelector('#checkoutGuardarForm');
    if (!innerForm) return;

    innerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const errBox = modal.querySelector('#checkoutErrors');
      if (errBox) { errBox.classList.add('hidden'); errBox.textContent = ''; }
      modal.querySelectorAll('[data-error-for]').forEach(p => { p.classList.add('hidden'); p.textContent = ''; });

      const fd = new FormData(innerForm);
      try {
        const res = await fetch(innerForm.action, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': fd.get('_token') },
          body: fd
        });
        const data = await res.json();

        if (!res.ok) {
          if (res.status === 422 && data.errors) {
            Object.entries(data.errors).forEach(([name, msgs]) => {
              const p = modal.querySelector(`[data-error-for="${name}"]`);
              if (p) { p.textContent = msgs[0]; p.classList.remove('hidden'); }
            });
            if (data.errors.general && errBox) {
              errBox.textContent = data.errors.general[0]; errBox.classList.remove('hidden');
            }
          } else if (errBox) {
            errBox.textContent = 'Error inesperado al finalizar el pedido.'; errBox.classList.remove('hidden');
          }
          return;
        }

        if (data.ok && data.redirect) {
          localStorage.removeItem('carritoNegocio');
          window.location.href = data.redirect;
        }
      } catch (err) {
        if (errBox) {
          errBox.textContent = 'No se pudo enviar el formulario. Verifica tu conexión.';
          errBox.classList.remove('hidden');
        }
      }
    });
  }

  // ✅ Enviar carrito al checkout (AJAX) y abrir modal de resumen + formulario
  const formCheckout = document.getElementById('formCheckout');
  if (formCheckout) {
    formCheckout.addEventListener('submit', async function (e) {
      e.preventDefault();

      if (carrito.length === 0) {
        alert('Tu carrito está vacío.');
        return;
      }

      if (inputHidden) inputHidden.value = JSON.stringify(carrito);

      const fd = new FormData(formCheckout);
      try {
        const res = await fetch(formCheckout.action, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: fd
        });
        const data = await res.json();

        if (!res.ok || !data.ok) {
          alert((data?.errors?.general && data.errors.general[0]) || 'No se pudo preparar el checkout.');
          return;
        }

        checkoutMount.innerHTML = data.html;
        openCheckoutModal();
        modalCarrito?.classList.add('hidden');
      } catch (err) {
        alert('Error de red preparando el checkout.');
      }
    });
  }

  // 🔄 Cargar carrito al iniciar
  actualizarCarrito();
});
</script>

<script>
  // Horarios del negocio ya los cargas como window.NEGOCIO_HORARIOS en el script de abajo.
  // Estos dos quedan listos por si luego implementas horarios/bloqueos específicos por trabajador:
  window.TRABAJADOR_HORARIOS = window.TRABAJADOR_HORARIOS || {}; // { [trabajador_id]: { [1..7]: [{inicio,fin}] } }
  window.TRABAJADOR_BLOQUEOS = window.TRABAJADOR_BLOQUEOS || {}; // { [trabajador_id]: ['YYYY-MM-DD', ...] }

  // Mapa de reservas del día por trabajador:
  // window.RESERVAS['YYYY-MM-DD'][trabajador_id] = [{inicio,fin}, ...]
  window.RESERVAS = window.RESERVAS || {};
</script>
@endpush
