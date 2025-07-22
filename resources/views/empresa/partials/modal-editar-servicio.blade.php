<div class="modal fade" id="modalEditarServicio{{ $servicio->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('servicios.actualizar', $servicio->id) }}" class="modal-content" style="background-color: #f6f5f7;">
            @csrf @method('PUT')

            <div class="modal-header" style="background-color: #4a5eaa;">
                <h5 class="modal-title text-white font-bold">Editar servicio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4 text-[#444478]">
                <div>
                    <label class="text-sm font-medium">Nombre</label>
                    <input type="text" name="nombre" value="{{ $servicio->nombre }}" class="form-control border-[#7e79c9]" required>
                </div>

                <div>
                    <label class="text-sm font-medium">Precio</label>
                    <input type="number" name="precio" value="{{ $servicio->precio }}" class="form-control border-[#7e79c9]" required>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Descripción</label>
                    <textarea name="descripcion" class="form-control border-[#7e79c9]" rows="2">{{ $servicio->descripcion }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-medium">Categoría</label>
                    <input type="text" name="categoria" value="{{ $servicio->categoria }}" class="form-control border-[#7e79c9]" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #4a5eaa;" onmouseover="this.style.backgroundColor='#3a457a'" onmouseout="this.style.backgroundColor='#4a5eaa'">Actualizar</button>
            </div>
        </form>
    </div>
</div>
