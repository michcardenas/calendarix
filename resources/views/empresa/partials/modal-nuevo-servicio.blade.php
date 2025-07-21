<div class="modal fade" id="modalNuevoServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('servicios.guardar') }}" class="modal-content" style="background-color: #fef8e8;">
            @csrf
            <div class="modal-header" style="background-color: #455392;">
                <h5 class="modal-title text-white font-bold">Añadir nuevo servicio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4 text-[#444478]">
                <div>
                    <label class="text-sm font-medium">Nombre del servicio</label>
                    <input type="text" name="nombre" class="form-control border-[#7e79c9]" required>
                </div>

                <div>
                    <label class="text-sm font-medium">Precio (COP)</label>
                    <input type="text" name="precio" class="form-control border-[#7e79c9]" required inputmode="numeric">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Duración estimada</label>
                    <input type="text" name="duracion" class="form-control border-[#7e79c9]" placeholder="Ej: 30 minutos">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Descripción</label>
                    <textarea name="descripcion" class="form-control border-[#7e79c9]" rows="2"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Selecciona una categoría</label>
                    <select id="categoria_select" class="form-control border-[#7e79c9]" onchange="toggleInputCategoriaServicio(this)">
                        <option value="">-- Escoge una existente --</option>
                        @foreach($categoriasUsuario as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="otra">Otra (escribir nueva)</option>
                    </select>

                    <input type="text" id="categoria_manual" class="form-control mt-2 hidden border-[#7e79c9]" placeholder="Escribe nueva categoría">
                </div>

                <input type="hidden" name="categoria" id="categoria_final">
            </div>

            <div class="modal-footer">
                <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #7e79c9;">Guardar servicio</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleInputCategoriaServicio(select) {
    const manualInput = document.getElementById('categoria_manual');
    const hiddenField = document.getElementById('categoria_final');

    if (select.value === 'otra') {
        manualInput.classList.remove('hidden');
        hiddenField.value = '';
        manualInput.addEventListener('input', () => {
            hiddenField.value = manualInput.value;
        });
    } else {
        manualInput.classList.add('hidden');
        hiddenField.value = select.value;
    }
}
</script>