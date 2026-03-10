<div class="modal fade" id="modalEditarServicio{{ $servicio->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <form method="POST"
          action="{{ route('empresa.catalogo.servicios.actualizar', ['id' => $empresa->id, 'servicio' => $servicio->id]) }}"
          enctype="multipart/form-data"
          class="modal-content border-0 rounded-2xl overflow-hidden shadow-lg">
      @csrf @method('PUT')

      {{-- Header --}}
      <div class="modal-header border-0 px-6 py-4" style="background: linear-gradient(135deg, #5a31d7, #7b5ce0);">
        <div>
          <h5 class="modal-title text-white font-bold text-lg">Editar servicio</h5>
          <p class="text-white/70 text-xs mt-0.5">Modifica los datos del servicio.</p>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      {{-- Body --}}
      <div class="modal-body px-6 py-5 space-y-5" style="background-color: #f9f8fc;">

        {{-- Nombre --}}
        <div>
          <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Nombre del servicio</label>
          <input type="text" name="nombre" value="{{ $servicio->nombre }}" required
              class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Precio --}}
          <div>
            <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Precio (UYU)</label>
            <div class="flex">
              <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-sm font-semibold text-[#5a31d7]">$</span>
              <input type="text" id="precio_display_edit_{{ $servicio->id }}" required inputmode="numeric"
                  value="{{ number_format($servicio->precio, 0, ',', '.') }}"
                  class="w-full rounded-r-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition">
              <input type="hidden" name="precio" id="precio_valor_edit_{{ $servicio->id }}" value="{{ (int)$servicio->precio }}">
            </div>
          </div>

          {{-- Duracion --}}
          <div>
            <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Duracion (minutos)</label>
            <div class="relative">
              <input type="number" name="duracion" min="5" step="5" inputmode="numeric" placeholder="30"
                  value="{{ $servicio->duracion ?? '' }}"
                  class="w-full rounded-lg border border-gray-200 px-4 py-2.5 pr-16 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition">
              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">min</span>
            </div>
          </div>
        </div>

        {{-- Imagen --}}
        <div>
          <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Imagen <span class="font-normal text-gray-400">(opcional)</span></label>
          <label id="imagen-dropzone-edit-{{ $servicio->id }}" class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:border-[#5a31d7] hover:bg-[#5a31d7]/5 transition-all overflow-hidden">
            <div id="imagen-placeholder-edit-{{ $servicio->id }}" class="flex flex-col items-center {{ $servicio->imagen ? 'hidden' : '' }}">
              <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1"></i>
              <span class="text-xs text-gray-400">Haz clic para subir una imagen</span>
              <span class="text-xs text-gray-300 mt-0.5">JPG, PNG (max 2MB)</span>
            </div>
            <img id="imagen-preview-edit-{{ $servicio->id }}"
                class="{{ $servicio->imagen ? '' : 'hidden' }} absolute inset-0 w-full h-full object-contain p-1 rounded-lg"
                src="{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : '' }}" alt="Preview">
            <input type="file" name="imagen" accept="image/*" class="hidden"
                onchange="previewImagenEdit(this, '{{ $servicio->id }}')">
          </label>
        </div>

        {{-- Descripcion --}}
        <div>
          <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Descripcion <span class="font-normal text-gray-400">(opcional)</span></label>
          <textarea name="descripcion" rows="2" placeholder="Breve descripcion del servicio..."
              class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition resize-none">{{ $servicio->descripcion }}</textarea>
        </div>

        {{-- Categoria --}}
        @php
          $categorias = $categoriasUsuario ?? [];
          $catActual = trim((string)$servicio->categoria);
          $catEnLista = in_array($catActual, $categorias, true);
        @endphp
        <div>
          <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Categoria</label>

          @if(count($categorias) > 0)
          <div class="flex flex-wrap gap-2 mb-3" id="categorias-chips-edit-{{ $servicio->id }}">
            @foreach($categorias as $cat)
              <button type="button"
                  class="cat-chip-edit-{{ $servicio->id }} px-3 py-1.5 rounded-full text-xs font-medium border transition-all cursor-pointer
                    {{ $cat === $catActual ? 'bg-[#5a31d7] text-white border-[#5a31d7]' : 'border-gray-200 bg-white text-[#3B4269] hover:border-[#5a31d7] hover:text-[#5a31d7]' }}"
                  onclick="seleccionarCategoriaEdit(this, '{{ $cat }}', '{{ $servicio->id }}')">
                {{ $cat }}
              </button>
            @endforeach
            <button type="button"
                class="cat-chip-edit-{{ $servicio->id }} px-3 py-1.5 rounded-full text-xs font-medium border border-dashed border-gray-300 bg-white text-gray-400 hover:border-[#5a31d7] hover:text-[#5a31d7] transition-all cursor-pointer"
                onclick="mostrarNuevaCategoriaEdit('{{ $servicio->id }}')">
              <i class="fas fa-plus mr-1"></i> Nueva
            </button>
          </div>
          @endif

          <input type="text" id="categoria_manual_edit_{{ $servicio->id }}"
              class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition {{ $catEnLista || count($categorias) > 0 ? 'hidden' : '' }}"
              placeholder="Escribe el nombre de la categoria"
              value="{{ $catEnLista ? '' : $catActual }}"
              oninput="document.getElementById('categoria_final_edit_{{ $servicio->id }}').value = this.value">

          <input type="hidden" name="categoria" id="categoria_final_edit_{{ $servicio->id }}" value="{{ $catActual }}">
        </div>
      </div>

      {{-- Footer --}}
      <div class="modal-footer border-0 px-6 py-4 bg-white flex gap-3">
        <button type="button" data-bs-dismiss="modal"
            class="px-5 py-2.5 rounded-xl text-sm font-medium text-[#3B4269] bg-gray-100 hover:bg-gray-200 transition">
          Cancelar
        </button>
        <button type="submit"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#5a31d7] hover:bg-[#7b5ce0] hover:shadow-lg hover:shadow-[#5a31d7]/20 transition-all">
          <i class="fas fa-save mr-1"></i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

<script>
(function() {
  const sid = "{{ $servicio->id }}";

  // Precio con separador de miles
  const display = document.getElementById('precio_display_edit_' + sid);
  const hidden = document.getElementById('precio_valor_edit_' + sid);
  display?.addEventListener('input', function() {
    let raw = this.value.replace(/\D/g, '');
    hidden.value = raw;
    this.value = raw ? Number(raw).toLocaleString('es-UY') : '';
  });
})();

function previewImagenEdit(input, sid) {
  const preview = document.getElementById('imagen-preview-edit-' + sid);
  const placeholder = document.getElementById('imagen-placeholder-edit-' + sid);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.classList.remove('hidden');
      placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function seleccionarCategoriaEdit(btn, valor, sid) {
  document.querySelectorAll('.cat-chip-edit-' + sid).forEach(c => {
    c.classList.remove('bg-[#5a31d7]', 'text-white', 'border-[#5a31d7]');
    c.classList.add('bg-white', 'text-[#3B4269]', 'border-gray-200');
  });
  btn.classList.remove('bg-white', 'text-[#3B4269]', 'border-gray-200');
  btn.classList.add('bg-[#5a31d7]', 'text-white', 'border-[#5a31d7]');
  document.getElementById('categoria_final_edit_' + sid).value = valor;
  document.getElementById('categoria_manual_edit_' + sid).classList.add('hidden');
  document.getElementById('categoria_manual_edit_' + sid).value = '';
}

function mostrarNuevaCategoriaEdit(sid) {
  document.querySelectorAll('.cat-chip-edit-' + sid).forEach(c => {
    c.classList.remove('bg-[#5a31d7]', 'text-white', 'border-[#5a31d7]');
    c.classList.add('bg-white', 'text-[#3B4269]', 'border-gray-200');
  });
  const manual = document.getElementById('categoria_manual_edit_' + sid);
  manual.classList.remove('hidden');
  manual.focus();
  document.getElementById('categoria_final_edit_' + sid).value = '';
}
</script>
