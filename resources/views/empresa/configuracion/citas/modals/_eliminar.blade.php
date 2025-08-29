<div class="modal fade clx-modal" id="citaEliminar-{{ $cita->id }}" tabindex="-1" aria-labelledby="citaEliminarLabel-{{ $cita->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white" style="background:var(--clx-danger)!important;">
        <h5 id="citaEliminarLabel-{{ $cita->id }}" class="modal-title">Eliminar Cita #{{ $cita->id }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form action="{{ route('empresa.configuracion.citas.destroy', [$id, $cita->id]) }}" method="POST">
        @csrf @method('DELETE')
        <div class="modal-body">
          <p class="mb-0">¿Seguro que deseas eliminar esta cita? Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-clx-outline" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-outline-light" style="background:var(--clx-danger);border-color:var(--clx-danger);">
            <i class="bi bi-trash"></i> Eliminar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
