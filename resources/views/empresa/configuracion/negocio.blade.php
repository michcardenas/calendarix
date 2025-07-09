@extends('layouts.empresa')

@section('title', 'Configuración del Negocio')

@section('content')
<style>
    .config-neg-container {
        padding: 2rem;
        margin-left: 265px;
        background-color: #ffffff;
        border-radius: 10px;
    }
    .config-neg-header h2 {
        font-weight: bold;
        color: #3b0764;
    }
    .config-neg-header p {
        color: #555;
    }
    .card {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 600;
    }
    .form-control,
    .form-select {
        width: 100%;
        padding: 0.5rem;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    .btn {
        padding: 0.4rem 1rem;
        margin-right: 0.5rem;
    }
</style>

<div class="config-neg-container">
    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="config-neg-header mb-4">
        <h2>Datos del negocio</h2>
        <p>Configura el nombre de tu negocio, país, idioma y enlaces externos.</p>
    </div>

    <div class="row" style="display: flex; gap: 2rem; flex-wrap: wrap;">
        {{-- Información del negocio --}}
        <div style="flex: 1; min-width: 300px;">
            <div class="card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Información del negocio</h5>
                    <button class="btn btn-sm btn-primary" id="btn-edit-neg-info">Editar</button>
                </div>
                <ul class="list-unstyled mb-0" id="neg-info-display">
                    <li><strong>Nombre:</strong> {{ $empresa->neg_nombre_comercial }}</li>
                    <li><strong>País:</strong> {{ $empresa->neg_pais ?? 'Colombia' }}</li>
                    <li><strong>Moneda:</strong> COP</li>
                    <li><strong>Idioma predeterminado:</strong> Español</li>
                </ul>

                <form action="{{ route('negocio.guardar') }}" method="POST" id="form-edit-neg-info" class="d-none mt-3">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                    <div class="mb-2">
                        <label for="confneg_nombre" class="form-label">Nombre del negocio</label>
                        <input type="text" name="confneg_nombre" id="confneg_nombre" class="form-control" value="{{ $empresa->neg_nombre_comercial }}">
                    </div>
                    <div class="mb-2">
                        <label for="confneg_pais" class="form-label">País</label>
                        <select name="confneg_pais" id="confneg_pais" class="form-select">
                            <option selected>{{ $empresa->neg_pais ?? 'Colombia' }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="btn-cancel-neg-info">Cancelar</button>
                </form>
            </div>
        </div>

        {{-- Enlaces externos --}}
        <div style="flex: 1; min-width: 300px;">
            <div class="card mb-4">
                <h5>Enlaces externos</h5>
                <form action="{{ route('negocio.guardar') }}" method="POST" id="form-enlaces-negocio">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                    <div class="mb-2">
                        <label for="confneg_facebook" class="form-label">Facebook</label>
                        <input type="url" name="confneg_facebook" id="confneg_facebook" class="form-control"
                            placeholder="https://facebook.com/tuempresa" value="{{ $empresa->neg_facebook }}">
                    </div>
                    <div class="mb-2">
                        <label for="confneg_instagram" class="form-label">Instagram</label>
                        <input type="url" name="confneg_instagram" id="confneg_instagram" class="form-control"
                            placeholder="https://instagram.com/tuempresa" value="{{ $empresa->neg_instagram }}">
                    </div>
                    <div class="mb-2">
                        <label for="confneg_web" class="form-label">Sitio Web</label>
                        <input type="url" name="confneg_web" id="confneg_web" class="form-control"
                            placeholder="https://tuempresa.com" value="{{ $empresa->neg_sitio_web }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Enlaces</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btn-edit-neg-info').addEventListener('click', function () {
        document.getElementById('neg-info-display').classList.add('d-none');
        document.getElementById('form-edit-neg-info').classList.remove('d-none');
    });

    document.getElementById('btn-cancel-neg-info').addEventListener('click', function () {
        document.getElementById('neg-info-display').classList.remove('d-none');
        document.getElementById('form-edit-neg-info').classList.add('d-none');
    });
</script>
@endsection
