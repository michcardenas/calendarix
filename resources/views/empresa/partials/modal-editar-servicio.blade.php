<div class="modal fade" id="modalEditarServicio{{ $servicio->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('servicios.actualizar', $servicio->id) }}" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title text-indigo-600 font-bold">Editar servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" value="{{ $servicio->nombre }}" class="form-control" required>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Precio</label>
                    <input type="number" name="precio" value="{{ $servicio->precio }}" class="form-control" required>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2">{{ $servicio->descripcion }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Categoría</label>
                    <input type="text" name="categoria" value="{{ $servicio->categoria }}" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Actualizar</button>
            </div>
        </form>
    </div>
</div>
