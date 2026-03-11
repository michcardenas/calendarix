{{-- resources/views/empresa/configuracion/citas/modals/_reprogramar.blade.php --}}
@php
  $hIniR = $cita->hora_inicio ? \Illuminate\Support\Str::limit($cita->hora_inicio, 5, '') : '';
  $hFinR = $cita->hora_fin ? \Illuminate\Support\Str::limit($cita->hora_fin, 5, '') : '';
  $durMinR = null;
  try {
      $durMinR = ($hIniR && $hFinR)
        ? \Carbon\Carbon::createFromTimeString($hIniR.':00')->diffInMinutes(\Carbon\Carbon::createFromTimeString($hFinR.':00'))
        : null;
  } catch (\Throwable $e) {}
@endphp

<div class="modal fade clx-modal" id="citaReprogramar-{{ $cita->id }}" tabindex="-1" aria-labelledby="citaReprogramarLabel-{{ $cita->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="citaReprogramarLabel-{{ $cita->id }}" class="modal-title">Reprogramar Cita #{{ $cita->id }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form action="{{ route('empresa.configuracion.citas.reprogramar', [$id, $cita->id]) }}" method="POST">
        @csrf @method('PATCH')
        <div class="modal-body">
          {{-- Resumen actual --}}
          <div class="small text-muted border rounded p-2 mb-3">
            <div><strong>Cliente:</strong> {{ $cita->nombre_cliente ?? '—' }}</div>
            <div><strong>Fecha actual:</strong> {{ optional($cita->fecha)->format('Y-m-d') }}</div>
            <div><strong>Hora actual:</strong> {{ $hIniR }} – {{ $hFinR }}@if($durMinR) ({{ $durMinR }} min)@endif</div>
            <div><strong>Trabajador:</strong>
              @if(isset($cita->trabajador) && $cita->trabajador)
                {{ $cita->trabajador->nombre }}
              @else
                —
              @endif
            </div>
            <div><strong>Servicio:</strong> {{ optional($cita->servicio)->nombre ?? '—' }}</div>
          </div>

          {{-- Nueva fecha --}}
          <div class="mb-3">
            <label for="reprog-fecha-{{ $cita->id }}" class="form-label fw-semibold">Nueva fecha</label>
            <input type="date" id="reprog-fecha-{{ $cita->id }}" name="fecha"
                   class="form-control clx-select"
                   value="{{ optional($cita->fecha)->format('Y-m-d') }}"
                   min="{{ now()->format('Y-m-d') }}" required>
          </div>

          {{-- Nueva hora inicio --}}
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label for="reprog-inicio-{{ $cita->id }}" class="form-label fw-semibold">Hora inicio</label>
              <input type="time" id="reprog-inicio-{{ $cita->id }}" name="hora_inicio"
                     class="form-control clx-select"
                     value="{{ $hIniR }}" required>
            </div>
            <div class="col-6">
              <label for="reprog-fin-{{ $cita->id }}" class="form-label fw-semibold">Hora fin</label>
              <input type="time" id="reprog-fin-{{ $cita->id }}" name="hora_fin"
                     class="form-control clx-select"
                     value="{{ $hFinR }}" required>
            </div>
          </div>

          @if($durMinR)
          <small class="text-muted d-block mb-2">
            <i class="fas fa-info-circle"></i>
            Duracion del servicio: {{ $durMinR }} minutos. Al cambiar la hora de inicio, ajusta la hora de fin.
          </small>
          @endif
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-clx-outline" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-clx-primary"><i class="bi bi-calendar-event"></i> Reprogramar</button>
        </div>
      </form>
    </div>
  </div>
</div>
