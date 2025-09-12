{{-- resources/views/empresa/configuracion/citas/modals/_detalle.blade.php --}}
@php
  $hIni = $cita->hora_inicio ? \Illuminate\Support\Str::limit($cita->hora_inicio, 5, '') : null;
  $hFin = $cita->hora_fin ? \Illuminate\Support\Str::limit($cita->hora_fin, 5, '') : null;
  try {
      $durMin = ($hIni && $hFin)
        ? \Carbon\Carbon::createFromTimeString($hIni.':00')->diffInMinutes(\Carbon\Carbon::createFromTimeString($hFin.':00'))
        : null;
  } catch (\Throwable $e) { $durMin = null; }
@endphp

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
          <div class="col-md-3">
            <small class="text-muted d-block">Fecha</small>
            <div class="fw-semibold">{{ optional($cita->fecha)->format('Y-m-d') }}</div>
          </div>
          <div class="col-md-3">
            <small class="text-muted d-block">Hora inicio</small>
            <div>{{ $hIni ?? '—' }}</div>
          </div>
          <div class="col-md-3">
            <small class="text-muted d-block">Hora fin</small>
            <div>{{ $hFin ?? '—' }}</div>
          </div>
          <div class="col-md-3">
            <small class="text-muted d-block">Duración</small>
            <div>{{ $durMin ? $durMin.' min' : '—' }}</div>
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">Cliente</small>
            <div class="fw-semibold">{{ $cita->nombre_cliente ?? '—' }}</div>
            <small class="text-muted d-block">Usuario (ID): {{ $cita->user_id ?? '—' }}</small>
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

          <div class="col-md-6">
            <small class="text-muted d-block">Trabajador</small>
            @if(isset($cita->trabajador) && $cita->trabajador)
              <div class="fw-semibold">{{ $cita->trabajador->nombre }}</div>
              <small class="text-muted">ID: {{ $cita->trabajador_id }}</small>
            @else
              <div class="text-muted">—</div>
            @endif
          </div>

          <div class="col-md-6">
            <small class="text-muted d-block">Servicio</small>
            <div class="fw-semibold">{{ optional($cita->servicio)->nombre ?? '—' }}</div>
            @if(!is_null($cita->precio_cerrado))
              <small class="text-muted d-block">Precio cerrado: ${{ number_format($cita->precio_cerrado/100, 0, ',', '.') }}</small>
            @endif
          </div>

          <div class="col-12">
            <small class="text-muted d-block">Notas</small>
            <div style="white-space: pre-wrap">{{ $cita->notas ?? '—' }}</div>
          </div>

          @if($cita->created_at || $cita->updated_at)
          <div class="col-12">
            <small class="text-muted d-block">Trazabilidad</small>
            <div class="text-muted small">
              Creada: {{ optional($cita->created_at)->format('Y-m-d H:i') ?? '—' }} ·
              Actualizada: {{ optional($cita->updated_at)->format('Y-m-d H:i') ?? '—' }}
            </div>
          </div>
          @endif
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
