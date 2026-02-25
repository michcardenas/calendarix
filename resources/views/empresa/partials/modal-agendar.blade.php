{{-- resources/views/empresa/partials/modal-agendar.blade.php --}}
{{-- Wizard de booking en 5 pasos: Servicio → Profesional → Fecha → Hora → Confirmar --}}

@php
    $servicios    = $servicios ?? \App\Models\Empresa\ServicioEmpresa::where('negocio_id', $negocio->id)->orderBy('nombre')->get();
    $trabajadores = $trabajadores ?? \App\Models\Trabajador::where('negocio_id', $negocio->id)->orderBy('nombre')->get();
@endphp

<div id="modalAgendar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl relative overflow-hidden" style="max-height: 90vh; display: flex; flex-direction: column;">

    {{-- Botón cerrar --}}
    <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 z-10" id="cerrarModalAgendar">
      <i class="fas fa-times text-lg"></i>
    </button>

    {{-- Barra de progreso --}}
    <div class="flex border-b bg-gray-50 shrink-0">
      <div class="wizard-indicator flex-1 text-center py-3 text-xs font-medium cursor-pointer transition-colors" data-step="1">
        <span class="wizard-dot inline-block w-6 h-6 rounded-full text-white text-xs leading-6 mb-0.5" style="background-color: #5a31d7;">1</span>
        <span class="block text-gray-500">Servicio</span>
      </div>
      <div class="wizard-indicator flex-1 text-center py-3 text-xs font-medium cursor-pointer transition-colors" data-step="2">
        <span class="wizard-dot inline-block w-6 h-6 rounded-full bg-gray-300 text-white text-xs leading-6 mb-0.5">2</span>
        <span class="block text-gray-500">Profesional</span>
      </div>
      <div class="wizard-indicator flex-1 text-center py-3 text-xs font-medium cursor-pointer transition-colors" data-step="3">
        <span class="wizard-dot inline-block w-6 h-6 rounded-full bg-gray-300 text-white text-xs leading-6 mb-0.5">3</span>
        <span class="block text-gray-500">Fecha</span>
      </div>
      <div class="wizard-indicator flex-1 text-center py-3 text-xs font-medium cursor-pointer transition-colors" data-step="4">
        <span class="wizard-dot inline-block w-6 h-6 rounded-full bg-gray-300 text-white text-xs leading-6 mb-0.5">4</span>
        <span class="block text-gray-500">Hora</span>
      </div>
      <div class="wizard-indicator flex-1 text-center py-3 text-xs font-medium cursor-pointer transition-colors" data-step="5">
        <span class="wizard-dot inline-block w-6 h-6 rounded-full bg-gray-300 text-white text-xs leading-6 mb-0.5">5</span>
        <span class="block text-gray-500">Confirmar</span>
      </div>
    </div>

    {{-- Error box global --}}
    <div id="wizardErrors" class="hidden bg-red-50 text-red-700 px-4 py-2 text-sm mx-4 mt-3 rounded-lg shrink-0"></div>

    {{-- Contenido scrollable --}}
    <div class="flex-1 overflow-y-auto">
      <form id="formAgendar" method="POST" action="{{ route('agenda.store', $negocio->id) }}">
        @csrf
        <input type="hidden" name="fecha" id="agendarFecha">
        <input type="hidden" name="servicio_id" id="hiddenServicioId">
        <input type="hidden" name="trabajador_id" id="hiddenTrabajadorId">
        <input type="hidden" name="hora_inicio" id="hiddenHoraInicio">
        <input type="hidden" name="hora_fin" id="hiddenHoraFin">

        {{-- ======================== PASO 1: SERVICIO ======================== --}}
        <div id="wizardStep1" class="wizard-panel p-5">
          <h3 class="text-lg font-bold text-gray-800 mb-1">Elige un servicio</h3>
          <p class="text-xs text-gray-400 mb-4">Selecciona el servicio que deseas reservar.</p>
          <div class="space-y-2 max-h-[55vh] overflow-y-auto pr-1">
            @foreach($servicios as $s)
              <div class="service-card border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-[#5a31d7] transition-all"
                   data-id="{{ $s->id }}"
                   data-nombre="{{ $s->nombre }}"
                   data-precio="{{ $s->precio }}"
                   data-duracion="{{ $s->duracion }}"
                   data-descripcion="{{ $s->descripcion }}"
                   data-categoria="{{ $s->categoria }}">
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">{{ $s->nombre }}</h4>
                    <div class="flex items-center gap-2 mt-0.5">
                      @if($s->categoria)
                        <span class="text-xs px-2 py-0.5 rounded-full" style="background-color: rgba(90,49,215,0.1); color: #5a31d7;">{{ $s->categoria }}</span>
                      @endif
                      @if($s->duracion)
                        <span class="text-xs text-gray-400"><i class="far fa-clock mr-0.5"></i>{{ $s->duracion }}</span>
                      @endif
                    </div>
                    @if($s->descripcion)
                      <p class="text-xs text-gray-400 mt-1">{{ Str::limit($s->descripcion, 80) }}</p>
                    @endif
                  </div>
                  <span class="text-lg font-bold shrink-0 ml-3" style="color: #5a31d7;">${{ number_format($s->precio, 0, ',', '.') }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- ======================== PASO 2: PROFESIONAL ======================== --}}
        <div id="wizardStep2" class="wizard-panel p-5 hidden">
          <h3 class="text-lg font-bold text-gray-800 mb-1">Elige un profesional</h3>
          <p class="text-xs text-gray-400 mb-4">Selecciona con quién deseas tu cita.</p>
          <div class="grid grid-cols-2 gap-3">
            @foreach($trabajadores as $t)
              <div class="worker-card border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-[#5a31d7] transition-all text-center"
                   data-id="{{ $t->id }}"
                   data-nombre="{{ $t->nombre }}">
                @if($t->foto)
                  <img src="{{ asset('storage/' . $t->foto) }}" alt="{{ $t->nombre }}"
                       class="w-16 h-16 rounded-full object-cover mx-auto mb-2 shadow-sm">
                @else
                  <div class="w-16 h-16 rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold shadow-sm" style="background-color: #5a31d7;">
                    {{ strtoupper(substr($t->nombre, 0, 2)) }}
                  </div>
                @endif
                <p class="font-medium text-sm text-gray-800">{{ $t->nombre }}</p>
                @if($t->especialidades)
                  <p class="text-xs mt-0.5" style="color: #5a31d7;">{{ $t->especialidades }}</p>
                @endif
              </div>
            @endforeach
          </div>
        </div>

        {{-- ======================== PASO 3: FECHA ======================== --}}
        <div id="wizardStep3" class="wizard-panel p-5 hidden">
          <h3 class="text-lg font-bold text-gray-800 mb-1">Elige una fecha</h3>
          <p class="text-xs text-gray-400 mb-4">Los días disponibles están resaltados.</p>
          <div id="miniCalendario"></div>
        </div>

        {{-- ======================== PASO 4: HORA ======================== --}}
        <div id="wizardStep4" class="wizard-panel p-5 hidden">
          <h3 class="text-lg font-bold text-gray-800 mb-1">Elige un horario</h3>
          <p class="text-xs text-gray-400 mb-2" id="step4DateLabel"></p>
          <p class="text-xs text-gray-400 mb-4" id="step4RangeLabel"></p>
          <div id="timeSlotGrid" class="grid grid-cols-4 gap-2 max-h-[45vh] overflow-y-auto pr-1">
            {{-- Se llena por JS --}}
          </div>
          <div id="selectedTimeDisplay" class="mt-3 text-center hidden">
            <span class="inline-block px-4 py-2 rounded-lg text-sm font-medium" style="background-color: rgba(90,49,215,0.1); color: #5a31d7;">
              <i class="far fa-clock mr-1"></i><span id="selectedTimeText"></span>
            </span>
          </div>
        </div>

        {{-- ======================== PASO 5: CONFIRMAR ======================== --}}
        <div id="wizardStep5" class="wizard-panel p-5 hidden">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Confirma tu reserva</h3>

          <div class="rounded-xl p-4 space-y-3 text-sm mb-4" style="background-color: rgba(90,49,215,0.05);">
            <div class="flex justify-between">
              <span class="text-gray-500">Servicio</span>
              <span class="font-medium text-gray-800" id="confirmServicio"></span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Profesional</span>
              <span class="font-medium text-gray-800" id="confirmTrabajador"></span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Fecha</span>
              <span class="font-medium text-gray-800" id="confirmFecha"></span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Horario</span>
              <span class="font-medium text-gray-800" id="confirmHorario"></span>
            </div>
            <div class="flex justify-between border-t pt-2" style="border-color: rgba(90,49,215,0.15);">
              <span class="text-gray-500">Precio</span>
              <span class="font-bold text-lg" style="color: #5a31d7;" id="confirmPrecio"></span>
            </div>
          </div>

          <div class="space-y-3 mb-4">
            <h4 class="text-sm font-semibold text-gray-700">Tus datos de contacto</h4>
            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Nombre completo <span class="text-red-400">*</span></label>
              <input type="text" name="nombre_cliente" id="agendarNombreCliente" required
                     class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color: #5a31d7;"
                     placeholder="Ingresa tu nombre completo"
                     value="{{ auth()->user()?->name ?? '' }}">
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Teléfono <span class="text-red-400">*</span></label>
              <input type="tel" name="telefono_cliente" id="agendarTelefonoCliente" required
                     class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color: #5a31d7;"
                     placeholder="Ej: 300 123 4567">
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Correo electrónico <span class="text-red-400">*</span></label>
              <input type="email" name="email_cliente" id="agendarEmailCliente" required
                     class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color: #5a31d7;"
                     placeholder="correo@ejemplo.com"
                     value="{{ auth()->user()?->email ?? '' }}">
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Notas (opcional)</label>
              <textarea name="notas" id="agendarNotas" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                        placeholder="Alguna indicación especial..."></textarea>
            </div>
          </div>

          <button type="submit" id="btnConfirmarCita"
                  class="w-full py-3 text-white font-semibold rounded-xl shadow-lg transition-all text-base"
                  style="background-color: #5a31d7;">
            <i class="fas fa-check-circle mr-2"></i> Confirmar Cita
          </button>
        </div>
      </form>
    </div>

    {{-- Navegación --}}
    <div class="flex justify-between items-center p-4 border-t bg-gray-50 shrink-0" id="wizardNav">
      <button type="button" id="wizardPrev" class="text-sm text-gray-500 hover:text-gray-700 transition hidden">
        <i class="fas fa-arrow-left mr-1"></i> Atrás
      </button>
      <div class="flex-1"></div>
    </div>

  </div>
</div>

<script>
(function () {
  // ================== ESTADO DEL WIZARD ==================
  const state = {
    currentStep: 1,
    servicioId: null,
    servicioNombre: '',
    servicioPrecio: 0,
    servicioDuracionMin: 30,
    trabajadorId: null,
    trabajadorNombre: '',
    fecha: null,
    horaInicio: null,
    horaFin: null,
  };

  // ================== REFS ==================
  const modal     = document.getElementById('modalAgendar');
  const btnClose  = document.getElementById('cerrarModalAgendar');
  const btnPrev   = document.getElementById('wizardPrev');
  const form      = document.getElementById('formAgendar');
  const errBox    = document.getElementById('wizardErrors');
  const panels    = document.querySelectorAll('.wizard-panel');
  const indicators = document.querySelectorAll('.wizard-indicator');

  // Hidden inputs
  const hFecha      = document.getElementById('agendarFecha');
  const hServicio   = document.getElementById('hiddenServicioId');
  const hTrabajador = document.getElementById('hiddenTrabajadorId');
  const hInicio     = document.getElementById('hiddenHoraInicio');
  const hFin        = document.getElementById('hiddenHoraFin');

  // ================== HELPERS ==================
  const jsDayToEmpresa = n => (n === 0 ? 7 : n);
  const pad2 = n => String(n).padStart(2, '0');
  const toYMD = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
  const formatDateES = ymd => {
    const [y,m,d] = ymd.split('-');
    return `${d}/${m}/${y}`;
  };

  function parseDuracionToMinutes(str) {
    if (!str) return 30;
    const lower = str.toLowerCase().trim();
    const match = lower.match(/(\d+)\s*(min|hora|hr|h)/);
    if (!match) {
      const numOnly = lower.match(/^(\d+)$/);
      if (numOnly) return parseInt(numOnly[1]);
      return 30;
    }
    const num = parseInt(match[1]);
    return match[2].startsWith('h') ? num * 60 : num;
  }

  function showError(msg) {
    if (errBox) { errBox.textContent = msg; errBox.classList.remove('hidden'); }
  }
  function hideError() {
    if (errBox) { errBox.textContent = ''; errBox.classList.add('hidden'); }
  }

  // ================== NAVEGACIÓN ==================
  function goToStep(n) {
    if (n < 1 || n > 5) return;
    state.currentStep = n;
    hideError();

    panels.forEach(p => p.classList.add('hidden'));
    const target = document.getElementById('wizardStep' + n);
    if (target) target.classList.remove('hidden');

    // Indicadores
    indicators.forEach(ind => {
      const s = parseInt(ind.dataset.step);
      const dot = ind.querySelector('.wizard-dot');
      if (s <= n) {
        dot.style.backgroundColor = '#5a31d7';
      } else {
        dot.style.backgroundColor = '#d1d5db';
      }
    });

    // Botón atrás
    if (n === 1) btnPrev.classList.add('hidden');
    else btnPrev.classList.remove('hidden');

    // Acciones especiales por paso
    if (n === 3) initMiniCalendar();
    if (n === 4) buildTimeSlotGrid();
    if (n === 5) populateConfirmation();
  }

  // Abrir modal
  window.openAgendarModal = function(options) {
    options = options || {};

    // Reset state
    state.servicioId = null; state.servicioNombre = ''; state.servicioPrecio = 0; state.servicioDuracionMin = 30;
    state.trabajadorId = null; state.trabajadorNombre = '';
    state.fecha = null; state.horaInicio = null; state.horaFin = null;

    // Reset visual selections
    document.querySelectorAll('.service-card').forEach(c => {
      c.classList.remove('border-[#5a31d7]'); c.style.borderColor = '#e5e7eb'; c.style.backgroundColor = '';
    });
    document.querySelectorAll('.worker-card').forEach(c => {
      c.classList.remove('border-[#5a31d7]'); c.style.borderColor = '#e5e7eb'; c.style.backgroundColor = '';
    });
    document.querySelectorAll('#timeSlotGrid button').forEach(b => {
      b.style.backgroundColor = ''; b.style.color = '';
    });
    document.getElementById('selectedTimeDisplay')?.classList.add('hidden');

    hideError();

    // Pre-selecciones
    let startStep = 1;

    if (options.serviceId) {
      const card = document.querySelector(`.service-card[data-id="${options.serviceId}"]`);
      if (card) {
        selectServiceCard(card);
        startStep = 2;
      }
    }

    if (options.date) {
      state.fecha = options.date;
    }

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    goToStep(startStep);
  };

  // Cerrar modal
  function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    // Destruir mini calendario si existe
    if (window._miniCal) { window._miniCal.destroy(); window._miniCal = null; }
  }
  btnClose?.addEventListener('click', closeModal);
  modal?.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
  });

  // Botón atrás
  btnPrev?.addEventListener('click', () => goToStep(state.currentStep - 1));

  // ================== PASO 1: SERVICIO ==================
  function selectServiceCard(card) {
    document.querySelectorAll('.service-card').forEach(c => {
      c.style.borderColor = '#e5e7eb'; c.style.backgroundColor = '';
    });
    card.style.borderColor = '#5a31d7';
    card.style.backgroundColor = 'rgba(90,49,215,0.04)';

    state.servicioId = card.dataset.id;
    state.servicioNombre = card.dataset.nombre;
    state.servicioPrecio = parseFloat(card.dataset.precio) || 0;
    state.servicioDuracionMin = parseDuracionToMinutes(card.dataset.duracion);
  }

  document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('click', () => {
      selectServiceCard(card);
      setTimeout(() => goToStep(2), 200);
    });
  });

  // ================== PASO 2: PROFESIONAL ==================
  document.querySelectorAll('.worker-card').forEach(card => {
    card.addEventListener('click', () => {
      document.querySelectorAll('.worker-card').forEach(c => {
        c.style.borderColor = '#e5e7eb'; c.style.backgroundColor = '';
      });
      card.style.borderColor = '#5a31d7';
      card.style.backgroundColor = 'rgba(90,49,215,0.04)';

      state.trabajadorId = card.dataset.id;
      state.trabajadorNombre = card.dataset.nombre;

      setTimeout(() => goToStep(3), 200);
    });
  });

  // ================== PASO 3: MINI CALENDARIO ==================
  let miniCalInitialized = false;

  function initMiniCalendar() {
    const el = document.getElementById('miniCalendario');
    if (!el) return;

    // Destruir si ya existía
    if (window._miniCal) { window._miniCal.destroy(); window._miniCal = null; }

    const bloqueos = [
      @foreach($negocio->bloqueos as $bloqueo)
        '{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',
      @endforeach
    ];

    const cal = new FullCalendar.Calendar(el, {
      initialView: 'dayGridMonth',
      locale: 'es',
      height: 'auto',
      headerToolbar: { left: 'prev,next', center: 'title', right: '' },

      dayCellClassNames: function(info) {
        const today = new Date(); today.setHours(0,0,0,0);
        const cellDate = new Date(info.date); cellDate.setHours(0,0,0,0);
        const ymd = toYMD(cellDate);
        if (cellDate < today) return ['fc-day-past'];
        if (bloqueos.includes(ymd)) return ['fc-day-blocked'];
        return [];
      },

      dateClick: async function(info) {
        const ymd = toYMD(info.date);
        const today = new Date(); today.setHours(0,0,0,0);
        const clicked = new Date(info.date); clicked.setHours(0,0,0,0);
        if (clicked < today) return;
        if (bloqueos.includes(ymd)) return;

        // Verificar que el día tiene horarios
        const day = jsDayToEmpresa(info.date.getDay());
        const ranges = window.NEGOCIO_HORARIOS[day] || [];
        if (!ranges.length) {
          showError('Este día el negocio no atiende.');
          return;
        }

        state.fecha = ymd;

        // Cargar reservas del día
        if (typeof window.cargarCitasDelDia === 'function') {
          await window.cargarCitasDelDia(ymd);
        }

        goToStep(4);
      }
    });

    cal.render();
    window._miniCal = cal;

    // Si hay fecha pre-seleccionada, navegar a ese mes
    if (state.fecha) {
      const d = new Date(state.fecha + 'T00:00:00');
      cal.gotoDate(d);
    }
  }

  // ================== PASO 4: GRID DE HORARIOS ==================
  function buildTimeSlotGrid() {
    const grid = document.getElementById('timeSlotGrid');
    const dateLabel = document.getElementById('step4DateLabel');
    const rangeLabel = document.getElementById('step4RangeLabel');
    const displayEl = document.getElementById('selectedTimeDisplay');
    const displayText = document.getElementById('selectedTimeText');

    if (!grid || !state.fecha) return;
    grid.innerHTML = '';
    displayEl?.classList.add('hidden');

    dateLabel.textContent = `Fecha: ${formatDateES(state.fecha)}`;

    const dt = new Date(state.fecha + 'T00:00:00');
    const day = jsDayToEmpresa(dt.getDay());
    const ranges = window.NEGOCIO_HORARIOS[day] || [];

    if (!ranges.length) {
      showError('No hay horarios disponibles para este día.');
      return;
    }

    rangeLabel.textContent = ranges.map(r => `${r.inicio} – ${r.fin}`).join(' | ');

    // Reservas del trabajador seleccionado
    const dayReservas = window.RESERVAS[state.fecha] || {};
    const workerReservas = dayReservas[state.trabajadorId] || [];

    const duracion = state.servicioDuracionMin || 30;
    let hasSlots = false;

    ranges.forEach(range => {
      const minT = window.t2m(range.inicio);
      const maxT = window.t2m(range.fin);

      for (let t = minT; t + duracion <= maxT; t += 15) {
        const startHH = window.m2t(t);
        const endHH = window.m2t(t + duracion);

        // Verificar que todo el bloque está libre
        if (window.overlapsAny(startHH, endHH, workerReservas)) continue;

        hasSlots = true;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'py-2 px-1 text-sm font-medium rounded-lg border-2 border-gray-200 hover:border-[#5a31d7] transition-all';
        btn.textContent = startHH;
        btn.dataset.start = startHH;
        btn.dataset.end = endHH;

        btn.addEventListener('click', function() {
          // Deseleccionar todos
          grid.querySelectorAll('button').forEach(b => {
            b.style.backgroundColor = ''; b.style.color = ''; b.style.borderColor = '#e5e7eb';
          });
          // Seleccionar este
          this.style.backgroundColor = '#5a31d7';
          this.style.color = '#ffffff';
          this.style.borderColor = '#5a31d7';

          state.horaInicio = this.dataset.start;
          state.horaFin = this.dataset.end;

          displayText.textContent = `${state.horaInicio} – ${state.horaFin} (${duracion} min)`;
          displayEl.classList.remove('hidden');

          setTimeout(() => goToStep(5), 300);
        });

        grid.appendChild(btn);
      }
    });

    if (!hasSlots) {
      grid.innerHTML = '<p class="col-span-4 text-center text-gray-400 py-6">No hay horarios disponibles para este profesional en esta fecha.</p>';
    }
  }

  // ================== PASO 5: CONFIRMACIÓN ==================
  function populateConfirmation() {
    document.getElementById('confirmServicio').textContent = state.servicioNombre;
    document.getElementById('confirmTrabajador').textContent = state.trabajadorNombre;
    document.getElementById('confirmFecha').textContent = state.fecha ? formatDateES(state.fecha) : '';
    document.getElementById('confirmHorario').textContent = state.horaInicio && state.horaFin
      ? `${state.horaInicio} – ${state.horaFin} (${state.servicioDuracionMin} min)` : '';
    document.getElementById('confirmPrecio').textContent = '$' + Number(state.servicioPrecio).toLocaleString('es-CO');

    // Llenar hidden inputs
    hServicio.value = state.servicioId || '';
    hTrabajador.value = state.trabajadorId || '';
    hFecha.value = state.fecha || '';
    hInicio.value = state.horaInicio || '';
    hFin.value = state.horaFin || '';
  }

  // ================== SUBMIT ==================
  form?.addEventListener('submit', async function(e) {
    e.preventDefault();
    hideError();

    // Validaciones
    if (!state.servicioId) { showError('Selecciona un servicio.'); goToStep(1); return; }
    if (!state.trabajadorId) { showError('Selecciona un profesional.'); goToStep(2); return; }
    if (!state.fecha) { showError('Selecciona una fecha.'); goToStep(3); return; }
    if (!state.horaInicio || !state.horaFin) { showError('Selecciona un horario.'); goToStep(4); return; }

    const nombreInput = document.getElementById('agendarNombreCliente');
    if (!nombreInput || !nombreInput.value.trim()) {
      showError('Ingresa tu nombre.'); return;
    }
    const telInput = document.getElementById('agendarTelefonoCliente');
    if (!telInput || !telInput.value.trim()) {
      showError('Ingresa tu teléfono.'); return;
    }
    const emailInput = document.getElementById('agendarEmailCliente');
    if (!emailInput || !emailInput.value.trim()) {
      showError('Ingresa tu correo electrónico.'); return;
    }

    // Llenar hiddens de nuevo por seguridad
    populateConfirmation();

    const btnSubmit = document.getElementById('btnConfirmarCita');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';

    try {
      const fd = new FormData(form);
      const res = await fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': fd.get('_token') },
        body: fd
      });
      const data = await res.json();

      if (!res.ok) {
        if (res.status === 422 && data.errors) {
          const msgs = Object.values(data.errors).flat();
          showError(msgs.join(' '));
        } else {
          showError(data.message || 'No se pudo agendar la cita.');
        }
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirmar Cita';
        return;
      }

      // Éxito: agregar evento al calendario principal
      if (data.ok && data.event && window.calendar && typeof window.calendar.addEvent === 'function') {
        window.calendar.addEvent({
          title: data.event.title || 'Cita',
          start: data.event.start,
          end: data.event.end,
          color: data.event.color || '#5a31d7',
          extendedProps: data.event.extendedProps || {}
        });
      }

      closeModal();

      // Toast de éxito
      const toast = document.createElement('div');
      toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ¡Cita agendada exitosamente!';
      toast.className = 'fixed bottom-6 right-6 text-white text-sm px-5 py-3 rounded-xl shadow-lg z-50 font-medium';
      toast.style.backgroundColor = '#5a31d7';
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);

    } catch (err) {
      showError('Error de conexión. Intenta de nuevo.');
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirmar Cita';
    }
  });

  // ================== INDICADORES CLICKEABLES ==================
  indicators.forEach(ind => {
    ind.addEventListener('click', () => {
      const targetStep = parseInt(ind.dataset.step);
      // Solo permitir ir a pasos ya completados
      if (targetStep < state.currentStep) {
        goToStep(targetStep);
      }
    });
  });

})();
</script>
