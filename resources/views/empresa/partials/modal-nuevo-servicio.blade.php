<div class="modal fade" id="modalNuevoServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('empresa.catalogo.servicios.guardar', $empresa->id) }}" enctype="multipart/form-data" class="modal-content border-0 rounded-2xl overflow-hidden shadow-lg">
            @csrf

            {{-- Header --}}
            <div class="modal-header border-0 px-6 py-4" style="background: linear-gradient(135deg, #5a31d7, #7b5ce0);">
                <div>
                    <h5 class="modal-title text-white font-bold text-lg">Nuevo servicio</h5>
                    <p class="text-white/70 text-xs mt-0.5">Completa los datos del servicio que ofreces.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body px-6 py-5 space-y-5" style="background-color: #f9f8fc;">

                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Nombre del servicio</label>
                    <input type="text" name="nombre" required placeholder="Ej: Corte de cabello"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Precio --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Precio (UYU)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-sm font-semibold text-[#5a31d7]">$</span>
                            <input type="text" id="precio_display" required inputmode="numeric" placeholder="0"
                                class="w-full rounded-r-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition">
                            <input type="hidden" name="precio" id="precio_valor">
                        </div>
                    </div>

                    {{-- Duracion --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Duracion (minutos)</label>
                        <div class="relative">
                            <input type="number" name="duracion" min="5" step="5" inputmode="numeric" placeholder="30"
                                class="w-full rounded-lg border border-gray-200 px-4 py-2.5 pr-16 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">min</span>
                        </div>
                    </div>
                </div>

                {{-- Imagen --}}
                <div>
                    <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Imagen <span class="font-normal text-gray-400">(opcional)</span></label>
                    <label id="imagen-dropzone" class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:border-[#5a31d7] hover:bg-[#5a31d7]/5 transition-all overflow-hidden">
                        <div id="imagen-placeholder" class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1"></i>
                            <span class="text-xs text-gray-400">Haz clic para subir una imagen</span>
                            <span class="text-xs text-gray-300 mt-0.5">JPG, PNG (max 2MB)</span>
                        </div>
                        <img id="imagen-preview" class="hidden absolute inset-0 w-full h-full object-contain p-1 rounded-lg" alt="Preview">
                        <input type="file" name="imagen" accept="image/*" class="hidden" onchange="previewImagen(this)">
                    </label>
                </div>

                {{-- Descripcion --}}
                <div>
                    <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Descripcion <span class="font-normal text-gray-400">(opcional)</span></label>
                    <textarea name="descripcion" rows="2" placeholder="Breve descripcion del servicio..."
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition resize-none"></textarea>
                </div>

                {{-- Categoria --}}
                <div>
                    <label class="block text-sm font-semibold text-[#3B4269] mb-1.5">Categoria</label>

                    @if(count($categoriasUsuario) > 0)
                    <div class="flex flex-wrap gap-2 mb-3" id="categorias-chips">
                        @foreach($categoriasUsuario as $cat)
                            <button type="button"
                                class="cat-chip px-3 py-1.5 rounded-full text-xs font-medium border border-gray-200 bg-white text-[#3B4269] hover:border-[#5a31d7] hover:text-[#5a31d7] transition-all cursor-pointer"
                                onclick="seleccionarCategoria(this, '{{ $cat }}')">
                                {{ $cat }}
                            </button>
                        @endforeach
                        <button type="button"
                            class="cat-chip px-3 py-1.5 rounded-full text-xs font-medium border border-dashed border-gray-300 bg-white text-gray-400 hover:border-[#5a31d7] hover:text-[#5a31d7] transition-all cursor-pointer"
                            onclick="mostrarNuevaCategoria()">
                            <i class="fas fa-plus mr-1"></i> Nueva
                        </button>
                    </div>
                    @endif

                    <input type="text" id="categoria_manual"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-[#3B4269] focus:ring-[#5a31d7] focus:border-[#5a31d7] transition {{ count($categoriasUsuario) > 0 ? 'hidden' : '' }}"
                        placeholder="Escribe el nombre de la categoria"
                        oninput="document.getElementById('categoria_final').value = this.value">

                    <input type="hidden" name="categoria" id="categoria_final">
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
                    <i class="fas fa-save mr-1"></i> Guardar servicio
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function seleccionarCategoria(btn, valor) {
    // Deseleccionar todos
    document.querySelectorAll('.cat-chip').forEach(c => {
        c.classList.remove('bg-[#5a31d7]', 'text-white', 'border-[#5a31d7]');
        c.classList.add('bg-white', 'text-[#3B4269]', 'border-gray-200');
    });

    // Seleccionar este
    btn.classList.remove('bg-white', 'text-[#3B4269]', 'border-gray-200');
    btn.classList.add('bg-[#5a31d7]', 'text-white', 'border-[#5a31d7]');

    document.getElementById('categoria_final').value = valor;
    document.getElementById('categoria_manual').classList.add('hidden');
    document.getElementById('categoria_manual').value = '';
}

function mostrarNuevaCategoria() {
    document.querySelectorAll('.cat-chip').forEach(c => {
        c.classList.remove('bg-[#5a31d7]', 'text-white', 'border-[#5a31d7]');
        c.classList.add('bg-white', 'text-[#3B4269]', 'border-gray-200');
    });

    const manual = document.getElementById('categoria_manual');
    manual.classList.remove('hidden');
    manual.focus();
    document.getElementById('categoria_final').value = '';
}

function previewImagen(input) {
    const preview = document.getElementById('imagen-preview');
    const placeholder = document.getElementById('imagen-placeholder');
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

// Separador de miles automático
document.addEventListener('DOMContentLoaded', function() {
    const display = document.getElementById('precio_display');
    const hidden = document.getElementById('precio_valor');

    display.addEventListener('input', function() {
        let raw = this.value.replace(/\D/g, '');
        hidden.value = raw;
        this.value = raw ? Number(raw).toLocaleString('es-UY') : '';
    });
});
</script>
