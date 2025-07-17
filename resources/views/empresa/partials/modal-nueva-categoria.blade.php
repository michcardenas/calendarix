<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('catalogo.categorias.guardar') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title text-indigo-600 font-bold">Añadir categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="text-sm font-medium text-gray-700">Nombre de la categoría</label>
                <input type="text" name="nueva_categoria" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Añadir</button>
            </div>
        </form>
    </div>
</div>
