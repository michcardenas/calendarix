<div class="modal fade clx-modal" id="citaDetalle-{{ $cita->id }}" tabindex="-1" aria-labelledby="citaDetalleLabel-{{ $cita->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="citaDetalleLabel-{{ $cita->id }}" class="modal-title">
          Detalle de Cita #{{ $cita->id }} — Negocio #{{ $id }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4">
            <small class="text-muted d-block">Fecha</small>
            <div class="fw-semibold">{{ optional($cita->fecha)->format('Y-m-d') }}</div>
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Hora inicio</small>
            <div>{{ $cita->hora_inicio ? \Illuminate\Support\Str::limit($cita->hora_inicio, 5, '') : '—' }}</div>
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Hora fin</small>
            <div>{{ $cita->hora_fin ? \Illuminate\Support\Str::limit($cita->hora_fin, 5, '') : '—' }}</div>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">Cliente</small>
            <div class="fw-semibold">{{ $cita->nombre_cliente ?? '—' }}</div>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">Usuario asignado (ID)</small>
            <div>{{ $cita->user_id ?? '—' }}</div>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">Estado</small>
            @php
              $badgeClass = [
                'pendiente'=>'badge-pendiente','confirmada'=>'badge-confirmada',
                'cancelada'=>'badge-cancelada','completada'=>'badge-completada'
              ][$cita->estado] ?? 'badge-pendiente';
            @endphp
            <span class="badge badge-state {{ $badgeClass }}">{{ ucfirst($cita->estado) }}</span>
          </div>

          <div class="col-12">
            <small class="text-muted d-block">Notas</small>
            <div style="white-space: pre-wrap">{{ $cita->notas ?? '—' }}</div>
          </div>
        </div>
      </div>

      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-clx-outline" data-bs-dismiss="modal">Cerrar</button>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-clx-primary"
                  data-bs-target="#citaEstado-{{ $cita->id }}" data-bs-toggle="modal" data-bs-dismiss="modal">
            <i class="bi bi-arrow-repeat"></i> Cambiar estado
          </button>
          <button type="button" class="btn btn-outline-light"
                  style="background:var(--clx-danger);border-color:var(--clx-danger);"
                  data-bs-target="#citaEliminar-{{ $cita->id }}" data-bs-toggle="modal" data-bs-dismiss="modal">
            <i class="bi bi-trash"></i> Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
