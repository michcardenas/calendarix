{{-- resources/views/empresa/configuracion/citas/modals/_estado.blade.php --}}
<div class="modal fade clx-modal" id="citaEstado-{{ $cita->id }}" tabindex="-1" aria-labelledby="citaEstadoLabel-{{ $cita->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="citaEstadoLabel-{{ $cita->id }}" class="modal-title">Cambiar estado — Cita #{{ $cita->id }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form action="{{ route('empresa.configuracion.citas.estado', [$id, $cita->id]) }}" method="POST">
        @csrf @method('PATCH')
        <div class="modal-body">
          {{-- Resumen compacto --}}
          <div class="small text-muted border rounded p-2 mb-3">
            <div><strong>Fecha:</strong> {{ optional($cita->fecha)->format('Y-m-d') }}</div>
            <div><strong>Hora:</strong> {{ \Illuminate\Support\Str::limit($cita->hora_inicio,5,'') ?? '—' }} – {{ \Illuminate\Support\Str::limit($cita->hora_fin,5,'') ?? '—' }}</div>
            <div><strong>Trabajador:</strong>
              @if(isset($cita->trabajador) && $cita->trabajador)
                {{ $cita->trabajador->nombre }} (ID {{ $cita->trabajador_id }})
              @else
                —
              @endif
            </div>
            <div><strong>Servicio:</strong> {{ optional($cita->servicio)->nombre ?? '—' }}</div>
          </div>

          <div class="mb-3">
            <label for="estado-{{ $cita->id }}" class="form-label">Estado</label>
            <select id="estado-{{ $cita->id }}" name="estado" class="form-select clx-select">
              @foreach(['pendiente','confirmada','cancelada','completada'] as $e)
                <option value="{{ $e }}" @selected($cita->estado === $e)>{{ ucfirst($e) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-clx-outline" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-clx-primary"><i class="bi bi-check2-circle"></i> Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
