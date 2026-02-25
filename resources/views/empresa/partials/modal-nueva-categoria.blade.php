<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('empresa.catalogo.categorias.guardar', ['id' => $negocio->id]) }}" class="modal-content" style="background-color: #f6f5f7;">
            @csrf
            <div class="modal-header" style="background-color: #5a31d7;">
                <h5 class="modal-title text-white font-bold">Añadir categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-[#374151]">
                <label class="text-sm font-medium">Nombre de la categoría</label>
                <input type="text" name="nueva_categoria" class="form-control border-[#a38ee9]" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #5a31d7;" onmouseover="this.style.backgroundColor='#4a22b8'" onmouseout="this.style.backgroundColor='#5a31d7'">Añadir</button>
            </div>
        </form>
    </div>
</div>
