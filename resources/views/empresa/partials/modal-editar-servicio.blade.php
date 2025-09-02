<div class="modal fade" id="modalEditarServicio{{ $servicio->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST"
          action="{{ route('empresa.catalogo.servicios.actualizar', ['id' => $empresa->id, 'servicio' => $servicio->id]) }}"
          class="modal-content" style="background-color: #f6f5f7;">
      @csrf @method('PUT')

      <div class="modal-header" style="background-color: #4a5eaa;">
        <h5 class="modal-title text-white font-bold">Editar servicio</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4 text-[#444478]">
        <div>
          <label class="text-sm font-medium">Nombre</label>
          <input type="text" name="nombre" value="{{ $servicio->nombre }}"
                 class="form-control border-[#7e79c9]" required>
        </div>

        <div>
          <label class="text-sm font-medium">Precio (COP)</label>
          <input type="number" name="precio" value="{{ $servicio->precio }}"
                 class="form-control border-[#7e79c9]" required>
        </div>

        <div class="md:col-span-2">
          <label class="text-sm font-medium">Duración estimada</label>
          <input type="text" name="duracion" value="{{ $servicio->duracion ?? '' }}"
                 class="form-control border-[#7e79c9]" placeholder="Ej: 30 minutos">
        </div>

        <div class="md:col-span-2">
          <label class="text-sm font-medium">Descripción</label>
          <textarea name="descripcion" class="form-control border-[#7e79c9]" rows="2">{{ $servicio->descripcion }}</textarea>
        </div>

        {{-- Categoría: igual UX que en “crear” --}}
        @php
          $categorias = $categoriasUsuario ?? [];
          $catActual = trim((string)$servicio->categoria);
          $catEnLista = in_array($catActual, $categorias, true);
        @endphp

        <div class="md:col-span-2">
          <label class="text-sm font-medium">Selecciona una categoría</label>
          <select id="categoria_select_{{ $servicio->id }}" class="form-control border-[#7e79c9]">
            <option value="">-- Escoge una existente --</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat }}" {{ $cat === $catActual ? 'selected' : '' }}>
                {{ $cat }}
              </option>
            @endforeach
            <option value="otra" {{ $catEnLista ? '' : 'selected' }}>Otra (escribir nueva)</option>
          </select>

          <input type="text"
                 id="categoria_manual_{{ $servicio->id }}"
                 class="form-control mt-2 border-[#7e79c9] {{ $catEnLista ? 'd-none hidden' : '' }}"
                 placeholder="Escribe nueva categoría"
                 value="{{ $catEnLista ? '' : $catActual }}">

          <input type="hidden" name="categoria" id="categoria_final_{{ $servicio->id }}"
                 value="{{ $catEnLista ? $catActual : $catActual }}">
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="px-4 py-2 rounded text-white"
                style="background-color: #4a5eaa;"
                onmouseover="this.style.backgroundColor='#3a457a'"
                onmouseout="this.style.backgroundColor='#4a5eaa'">
          Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Script scoped por servicio (ids únicos por $servicio->id) --}}
<script>
(function() {
  const sid   = "{{ $servicio->id }}";
  const sel   = document.getElementById('categoria_select_' + sid);
  const input = document.getElementById('categoria_manual_' + sid);
  const out   = document.getElementById('categoria_final_' + sid);

  function syncCategoria() {
    if (sel.value === 'otra') {
      input.classList.remove('d-none','hidden');
      out.value = input.value.trim();
    } else {
      input.classList.add('d-none','hidden');
      out.value = sel.value || '';
    }
  }

  sel?.addEventListener('change', syncCategoria);
  input?.addEventListener('input', () => {
    if (sel.value === 'otra') out.value = input.value.trim();
  });

  // Si el modal se abre dinámicamente, asegúrate de re-sincronizar
  const modalEl = document.getElementById('modalEditarServicio' + sid);
  if (modalEl) {
    modalEl.addEventListener('shown.bs.modal', syncCategoria);
  }
  // Sync inicial
  syncCategoria();
})();
</script>
