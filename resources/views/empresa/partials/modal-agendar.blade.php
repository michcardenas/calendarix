{{-- resources/views/partials/modal-agendar.blade.php --}}
<div id="modalAgendar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
    <button type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700" id="cerrarModalAgendar">✕</button>
    <h2 class="text-xl font-bold text-[#4a5eaa] mb-4">📅 Agendar cita</h2>

    <div id="agendarErrors" class="hidden rounded bg-red-50 text-red-700 px-4 py-3 text-sm mb-3"></div>

    @php
      // Si ya pasas $servicios desde el controlador, comenta/borra estas líneas
      $servicios = $servicios ?? \App\Models\Empresa\ServicioEmpresa::where('negocio_id', $negocio->id)
                    ->orderBy('nombre')
                    ->get(['id','nombre','precio','categoria','descripcion']);
    @endphp

    <form id="formAgendar" method="POST" action="{{ route('agenda.store', $negocio->id) }}" class="space-y-4">
      @csrf
      <input type="hidden" name="fecha" id="agendarFecha"> {{-- YYYY-MM-DD --}}

      @guest
      <div>
        <label class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="nombre_cliente" id="agendarNombreCliente"
               class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
               placeholder="Tu nombre" required>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="nombre_cliente"></p>
      </div>
      @endguest

      {{-- Selección de servicio --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Servicio</label>
        <select name="servicio_id" id="agendarServicio" required
                class="mt-1 w-full border rounded-lg px-3 py-2">
          <option value="">Selecciona un servicio…</option>
          @foreach ($servicios as $s)
            <option value="{{ $s->id }}"
                    data-precio="{{ number_format((float)$s->precio, 2, '.', '') }}"
                    data-nombre="{{ $s->nombre }}"
                    data-categoria="{{ $s->categoria }}"
                    data-descripcion="{{ Str::limit($s->descripcion, 120) }}">
              {{ $s->nombre }}
              @if(!is_null($s->precio)) — ${{ number_format($s->precio, 0, ',', '.') }} @endif
            </option>
          @endforeach
        </select>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="servicio_id"></p>

        {{-- Resumen compacto del servicio seleccionado --}}
        <div id="servicioResumen" class="mt-2 text-xs text-gray-600 hidden">
          <span class="font-semibold" id="servicioNombre"></span>
          <span id="servicioCategoria" class="ml-1"></span>
          <span class="block">Precio: <span id="servicioPrecio" class="font-medium"></span></span>
          <span id="servicioDescripcion" class="block"></span>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Fecha</label>
        <input type="text" id="agendarFechaLabel" class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
      </div>

      {{-- Selector de rango (si el día tiene más de uno) --}}
      <div id="wrapRango" class="hidden">
        <label class="block text-sm font-medium text-gray-700">Rango horario</label>
        <select id="agendarRango" class="mt-1 w-full border rounded-lg px-3 py-2"></select>
        <p class="text-xs text-gray-500 mt-1" id="hintRango"></p>
      </div>

      {{-- INICIO / FIN en intervalos de 15 min --}}
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
          <select name="hora_inicio" id="agendarHoraInicio" required class="mt-1 w-full border rounded-lg px-3 py-2"></select>
          <p class="text-red-600 text-xs mt-1 hidden" data-error-for="hora_inicio"></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Hora fin</label>
          <select name="hora_fin" id="agendarHoraFin" required class="mt-1 w-full border rounded-lg px-3 py-2"></select>
          <p class="text-red-600 text-xs mt-1 hidden" data-error-for="hora_fin"></p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Notas</label>
        <textarea name="notas" id="agendarNotas" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="Opcional"></textarea>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="notas"></p>
      </div>

      <button type="submit" class="w-full bg-[#6274c9] hover:bg-[#4e5bb0] text-white font-medium py-2 rounded-lg shadow">
        ✅ Guardar cita
      </button>
    </form>
  </div>
</div>

<script>
(function () {
  const modal   = document.getElementById('modalAgendar');
  const btnX    = document.getElementById('cerrarModalAgendar');
  const form    = document.getElementById('formAgendar');
  const errBox  = document.getElementById('agendarErrors');

  const fechaHidden = document.getElementById('agendarFecha');     // Y-m-d (envía)
  const fechaLabel  = document.getElementById('agendarFechaLabel'); // m/d/Y (muestra)
  const rangoWrap   = document.getElementById('wrapRango');
  const rangoSel    = document.getElementById('agendarRango');
  const hintRango   = document.getElementById('hintRango');
  const hiSel       = document.getElementById('agendarHoraInicio');
  const hfSel       = document.getElementById('agendarHoraFin');

  // NUEVO: refs de servicio
  const servicioSel        = document.getElementById('agendarServicio');
  const servicioResumenBox = document.getElementById('servicioResumen');
  const servicioNombre     = document.getElementById('servicioNombre');
  const servicioCategoria  = document.getElementById('servicioCategoria');
  const servicioPrecio     = document.getElementById('servicioPrecio');
  const servicioDescripcion= document.getElementById('servicioDescripcion');

  // Utils
  const pad2 = n => String(n).padStart(2,'0');
  const toYMD = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
  const toMDY = ymd => { const [y,m,d]=ymd.split('-'); return `${m}/${d}/${y}`; };
  const jsDayToEmpresa = n => (n === 0 ? 7 : n); // 0=Dom → 7

  // Normaliza cualquier forma del backend a {inicio:'HH:MM', fin:'HH:MM'}
  function normalizeRanges(raw) {
    return (raw || []).map(r => {
      const ini = (r.inicio ?? r.hora_inicio ?? '').toString().slice(0,5);
      const fin = (r.fin    ?? r.hora_fin    ?? '').toString().slice(0,5);
      return { inicio: ini, fin: fin };
    }).filter(r => r.inicio && r.fin);
  }

  // ====== SERVICIO: resumen dinámico
  function renderServicioResumen() {
    const opt = servicioSel?.selectedOptions?.[0];
    if (!opt || !opt.value) {
      servicioResumenBox?.classList.add('hidden');
      servicioNombre.textContent = '';
      servicioCategoria.textContent = '';
      servicioPrecio.textContent = '';
      servicioDescripcion.textContent = '';
      return;
    }
    const nombre = opt.dataset.nombre || opt.textContent.trim();
    const categoria = opt.dataset.categoria || '';
    const precio = opt.dataset.precio ? Number(opt.dataset.precio) : null;
    const descripcion = opt.dataset.descripcion || '';

    servicioNombre.textContent = nombre;
    servicioCategoria.textContent = categoria ? `· ${categoria}` : '';
    servicioPrecio.textContent = (precio !== null && !Number.isNaN(precio))
      ? `$${precio.toLocaleString()}`
      : '—';

    servicioDescripcion.textContent = descripcion || '';
    servicioResumenBox?.classList.remove('hidden');
  }

  servicioSel?.addEventListener('change', renderServicioResumen);

  // ✅ FUNCIÓN PURA (NO DOM): devuelve los rangos para una fecha
  function computeRangesForDate(dateObj) {
    const day = jsDayToEmpresa(dateObj.getDay());
    const map = window.NEGOCIO_HORARIOS || {};
    return normalizeRanges(map[day] || []);
  }

  // Construye los <select> de inicio/fin en cuartos de hora dentro del rango, excluyendo reservas del día
  function buildTimeSelects(range, ymd) {
    const reservas = (window.RESERVAS && window.RESERVAS[ymd]) ? window.RESERVAS[ymd] : [];

    const fill = (selectEl, values) => {
      const prev = selectEl.value;
      selectEl.innerHTML = '';
      const ph = document.createElement('option');
      ph.value = ''; ph.textContent = 'Selecciona...';
      selectEl.appendChild(ph);
      values.forEach(v => {
        const opt = document.createElement('option');
        opt.value = v; opt.textContent = v;
        selectEl.appendChild(opt);
      });
      if (prev && values.includes(prev)) selectEl.value = prev; else selectEl.value = '';
    };

    // INICIO: puntos libres de 15' (usa helpers globales del script principal)
    const freeStarts = (window.generateFreeQuarterSlots || (()=>[]))(range.inicio, range.fin, reservas);
    fill(hiSel, freeStarts);

    // FIN: depende del inicio y debe dejar [inicio, fin) libre
    fill(hfSel, []);
    hiSel.onchange = () => {
      const start = hiSel.value;
      if (!start) { fill(hfSel, []); return; }
      const validEnds = (window.generateValidEnds || (()=>[]))(start, range.fin, reservas);
      fill(hfSel, validEnds);
    };
  }

  // ✅ FUNCIÓN QUE SÍ PINTA
  function renderRangesForDate(dateObj, ymd) {
    const ranges = computeRangesForDate(dateObj);

    if (!ranges.length) {
      rangoWrap.classList.add('hidden');
      hiSel.innerHTML = ''; hfSel.innerHTML = '';
      hintRango.textContent = '';
      throw new Error('no-attention-day');
    }

    if (ranges.length > 1) {
      rangoWrap.classList.remove('hidden');
      rangoSel.innerHTML = '';
      ranges.forEach((r, i) => {
        const opt = document.createElement('option');
        opt.value = i;
        opt.textContent = `${r.inicio} - ${r.fin}`;
        rangoSel.appendChild(opt);
      });
      hintRango.textContent = `Atención de ${ranges[0].inicio} a ${ranges[0].fin}`;
      buildTimeSelects(ranges[0], ymd);
      rangoSel.onchange = () => {
        const idx = parseInt(rangoSel.value, 10);
        const selected = ranges[idx];
        hintRango.textContent = `Atención de ${selected.inicio} a ${selected.fin}`;
        buildTimeSelects(selected, ymd);
      };
    } else {
      rangoWrap.classList.add('hidden');
      hintRango.textContent = `Atención de ${ranges[0].inicio} a ${ranges[0].fin}`;
      buildTimeSelects(ranges[0], ymd);
    }

    return ranges;
  }

  // Abre el modal y ajusta horarios del día
  window.openAgendarModal = function (dateInput) {
    // Normalizar fecha a Y-m-d
    let ymd = '';
    if (dateInput instanceof Date) {
      ymd = toYMD(dateInput);
    } else if (typeof dateInput === 'string') {
      if (/^\d{4}-\d{2}-\d{2}$/.test(dateInput)) ymd = dateInput;
      else if (/^\d{2}\/\d{2}\/\d{4}$/.test(dateInput)) {
        const [mm, dd, yy] = dateInput.split('/'); ymd = `${yy}-${mm}-${dd}`;
      }
    }
    if (!ymd) return;

    // Setear campos de fecha
    fechaHidden.value = ymd;
    fechaLabel.value  = toMDY(ymd);

    // Reset UI
    if (errBox) { errBox.classList.add('hidden'); errBox.textContent = ''; }
    document.querySelectorAll('#modalAgendar [data-error-for]').forEach(p => { p.classList.add('hidden'); p.textContent = ''; });
    hiSel.innerHTML = ''; hfSel.innerHTML = '';
    // reset resumen servicio (mantengo selección pero refresco el resumen)
    renderServicioResumen();

    // Horarios del día
    const dt = new Date(ymd + 'T00:00:00');
    try {
      renderRangesForDate(dt, ymd);
    } catch(e) {
      if (e.message === 'no-attention-day') {
        if (errBox) { errBox.textContent = 'No se atiende en la fecha seleccionada.'; errBox.classList.remove('hidden'); }
      }
    }

    // Mostrar modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  };

  // Cerrar modal
  btnX?.addEventListener('click', () => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  });

  // Envío AJAX con validaciones
  form?.addEventListener('submit', async function (e) {
    e.preventDefault();

    // Garantizar Y-m-d en hidden si alguien manipuló el label
    if (fechaLabel.value && !/^\d{4}-\d{2}-\d{2}$/.test(fechaHidden.value)) {
      const [mm, dd, yy] = fechaLabel.value.split('/');
      fechaHidden.value = `${yy}-${mm}-${dd}`;
    }

    const hi = hiSel.value;
    const hf = hfSel.value;

    // Validación de servicio (cliente)
    if (!servicioSel?.value) {
      const p = document.querySelector('#modalAgendar [data-error-for="servicio_id"]');
      if (p) { p.textContent = 'Debes seleccionar un servicio.'; p.classList.remove('hidden'); }
      if (errBox) { errBox.textContent = 'Debes seleccionar un servicio.'; errBox.classList.remove('hidden'); }
      return;
    }

    // Validaciones cliente de horas
    if (!hi || !hf) {
      if (errBox) { errBox.textContent = 'Debes indicar hora de inicio y fin.'; errBox.classList.remove('hidden'); }
      return;
    }
    if (hf <= hi) {
      if (errBox) { errBox.textContent = 'La hora de fin debe ser mayor a la de inicio.'; errBox.classList.remove('hidden'); }
      const pFin = document.querySelector('#modalAgendar [data-error-for="hora_fin"]');
      if (pFin) { pFin.textContent = 'Debe ser mayor a la hora de inicio.'; pFin.classList.remove('hidden'); }
      return;
    }

    // Cálculo puro de rangos (NO re-render)
    const dt = new Date(fechaHidden.value + 'T00:00:00');
    const ranges = computeRangesForDate(dt);
    if (!ranges.length) {
      if (errBox) { errBox.textContent = 'No se atiende en esa fecha.'; errBox.classList.remove('hidden'); }
      return;
    }
    const chosen = (ranges.length > 1 && rangoSel) ? ranges[parseInt(rangoSel.value || '0', 10)] : ranges[0];
    if (hi < chosen.inicio || hf > chosen.fin) {
      if (errBox) { errBox.textContent = `El horario debe estar entre ${chosen.inicio} y ${chosen.fin}.`; errBox.classList.remove('hidden'); }
      return;
    }

    // Anti-solape con reservas del día
    const reservas = (window.RESERVAS && window.RESERVAS[fechaHidden.value]) ? window.RESERVAS[fechaHidden.value] : [];
    if ((window.overlapsAny || (()=>false))(hi, hf, reservas)) {
      if (errBox) {
        errBox.textContent = 'Ese horario ya está reservado. Elige otro intervalo.';
        errBox.classList.remove('hidden');
      }
      return;
    }

    // Limpiar errores previos
    if (errBox) { errBox.classList.add('hidden'); errBox.textContent = ''; }
    document.querySelectorAll('#modalAgendar [data-error-for]').forEach(p => { p.classList.add('hidden'); p.textContent = ''; });

    // Enviar
    const fd = new FormData(form);
    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': fd.get('_token') },
        body: fd
      });
      const data = await res.json();

      if (!res.ok) {
        if (res.status === 422 && data.errors) {
          Object.entries(data.errors).forEach(([name, msgs]) => {
            const p = document.querySelector(`#modalAgendar [data-error-for="${name}"]`);
            if (p) { p.textContent = msgs[0]; p.classList.remove('hidden'); }
          });
          if (data.errors.general && errBox) { errBox.textContent = data.errors.general[0]; errBox.classList.remove('hidden'); }
        } else if (errBox) {
          errBox.textContent = (data?.errors?.general?.[0]) || 'No se pudo agendar la cita.';
          errBox.classList.remove('hidden');
        }
        return;
      }

      // Pintar en calendario si existe window.calendar
      if (data.ok && data.event && window.calendar && typeof window.calendar.addEvent === 'function') {
        window.calendar.addEvent({
          title: data.event.title || 'Cita',
          start: data.event.start,
          end:   data.event.end,
          color: data.event.color || '#4a5eaa'
        });
      }

      // Cerrar modal + toast
      modal.classList.add('hidden'); modal.classList.remove('flex');
      const toast = document.createElement('div');
      toast.textContent = '¡Cita agendada!';
      toast.className = 'fixed bottom-6 right-6 bg-black/80 text-white text-sm px-4 py-2 rounded shadow-lg z-50';
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 1800);

    } catch (e2) {
      if (errBox) { errBox.textContent = 'Error de red al agendar.'; errBox.classList.remove('hidden'); }
    }
  });
})();
</script>
