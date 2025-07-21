<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('catalogo.categorias.guardar') }}" class="modal-content" style="background-color: #f6f5f7;">
            @csrf
            <div class="modal-header" style="background-color: #455392;">
                <h5 class="modal-title text-white font-bold">Añadir categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-[#444478]">
                <label class="text-sm font-medium">Nombre de la categoría</label>
                <input type="text" name="nueva_categoria" class="form-control border-[#7e79c9]" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #7e79c9;">Añadir</button>
            </div>
        </form>
    </div>
</div>
