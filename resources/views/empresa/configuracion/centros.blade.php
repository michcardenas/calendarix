@extends('layouts.empresa')

@section('title', 'Centros')

@push('styles')
<style>
    .cof-cent-wrapper {
        padding-top: 2rem;
        padding-bottom: 2rem;
        margin-left: 20rem;
    }

    .cof-cent-card {
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .cof-cent-list-item {
        border: none;
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .cof-cent-boton {
        background-color: #6d28d9;
        border: none;
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 0.375rem;
    }

    .cof-cent-boton:hover {
        background-color: #5b21b6;
    }
</style>
@endpush

@section('content')
<div class="cof-cent-wrapper">
    <div class="mb-4">
        <h2 class="fw-bold">Centros</h2>
        <p class="text-muted">Gestiona la información y las ubicaciones de los centros de tu negocio.</p>
    </div>

    <div class="cof-cent-card">
        @if($centros->isEmpty())
            <p class="text-center text-muted">No hay centros registrados aún.</p>
        @else
            <ul class="list-group mb-3">
                @foreach($centros as $centro)
    <li class="cof-cent-list-item" id="centro-principal">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <strong>{{ $centro['nombre'] }}</strong><br>

                <span id="direccion-text-principal">
                    Dirección: {{ $centro['direccion'] }}
                </span>

                <form method="POST"
                      action="{{ route('empresa.configuracion.centros.update', 'principal') }}"
                      class="d-none mt-2"
                      id="form-direccion-principal">
                    @csrf
                    @method('PUT')

                    <div class="d-flex gap-2 align-items-center">
                        <input type="text" name="direccion"
                               value="{{ $centro['direccion'] }}"
                               class="form-control form-control-sm" required>
                        <button type="submit" class="btn btn-sm btn-success">
                            💾 Guardar
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary"
                                onclick="cancelarEdicion('principal')">
                            Cancelar
                        </button>
                    </div>
                </form>

                <small class="text-muted d-block mt-1">Centro principal</small>
            </div>

            <button class="btn btn-sm btn-outline-primary"
                    onclick="editarDireccion('principal')">
                ✏️ Editar
            </button>
        </div>
    </li>
@endforeach

            </ul>
        @endif

        <div class="text-end">
            <button class="cof-cent-boton">
                <i class="fas fa-plus me-1"></i> Añadir Centro
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editarDireccion(id) {
        document.getElementById('direccion-text-' + id).style.display = 'none';
        document.getElementById('form-direccion-' + id).classList.remove('d-none');
    }

    function cancelarEdicion(id) {
        document.getElementById('direccion-text-' + id).style.display = 'inline';
        document.getElementById('form-direccion-' + id).classList.add('d-none');
    }
</script>
@endpush
