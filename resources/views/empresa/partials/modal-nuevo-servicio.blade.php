<div class="modal fade" id="modalNuevoServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('servicios.guardar') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title text-indigo-600 font-bold">Añadir nuevo servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Nombre del servicio</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Precio (COP)</label>
                    <input type="text" name="precio" class="form-control" required inputmode="numeric">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Duración estimada</label>
                    <input type="text" name="duracion" class="form-control" placeholder="Ej: 30 minutos">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Selecciona una categoría</label>
                    <select id="categoria_select" class="form-control" onchange="toggleInputCategoriaServicio(this)">
                        <option value="">-- Escoge una existente --</option>
                        @foreach($categoriasUsuario as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="otra">Otra (escribir nueva)</option>
                    </select>

                    <input type="text" id="categoria_manual" class="form-control mt-2 hidden" placeholder="Escribe nueva categoría">
                </div>

                {{-- Campo oculto real que será enviado --}}
                <input type="hidden" name="categoria" id="categoria_final">
            </div>

            <div class="modal-footer">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Guardar servicio</button>
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