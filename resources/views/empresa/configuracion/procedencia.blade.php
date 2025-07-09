@extends('layouts.empresa')

@section('title', 'Procedencia de los clientes')

@push('styles')
<style>
    .fof-proc-wrapper {
        padding-top: 2rem;
        padding-bottom: 2rem;
        margin-left: 20rem;
    }

    .fof-proc-card {
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        background-color: #fff;
    }

    .fof-proc-boton {
        background-color: #6d28d9;
        border: none;
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 0.375rem;
    }

    .fof-proc-boton:hover {
        background-color: #5b21b6;
    }
</style>
@endpush

@section('content')
<div class="fof-proc-wrapper">
    <div class="mb-4">
        <h2 class="fw-bold">Procedencia de los clientes</h2>
        <p class="text-muted">Gestiona cómo tus clientes descubren tu negocio.</p>
    </div>

    <div class="fof-proc-card">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('empresa.configuracion.procedencia.update') }}">
            @csrf

            <div class="mb-3">
                <label for="neg_instagram" class="form-label">Instagram</label>
                <input type="text" name="neg_instagram" id="neg_instagram" class="form-control"
                       value="{{ $empresa->neg_instagram }}">
            </div>

            <div class="mb-3">
                <label for="neg_facebook" class="form-label">Facebook</label>
                <input type="text" name="neg_facebook" id="neg_facebook" class="form-control"
                       value="{{ $empresa->neg_facebook }}">
            </div>

            <div class="mb-3">
                <label for="neg_sitio_web" class="form-label">Sitio Web</label>
                <input type="text" name="neg_sitio_web" id="neg_sitio_web" class="form-control"
                       value="{{ $empresa->neg_sitio_web }}">
            </div>

            <div class="text-end">
                <button type="submit" class="fof-proc-boton">
                    <i class="fas fa-save me-1"></i> Guardar Procedencia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editarFofProc(id) {
        document.getElementById('fof-proc-text-' + id).style.display = 'none';
        document.getElementById('fof-proc-form-' + id).classList.remove('d-none');
    }

    function cancelarFofProc(id) {
        document.getElementById('fof-proc-text-' + id).style.display = 'inline';
        document.getElementById('fof-proc-form-' + id).classList.add('d-none');
    }
</script>
@endpush
