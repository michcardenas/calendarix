<link rel="stylesheet" href="{{ asset('css/negocios/datos-negocio.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 8; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <div class="form-content">
            <h3>Configuración de cuenta</h3>
            <h1><strong>¿Cómo se llama tu negocio?</strong></h1>
            <p>Este es el nombre comercial que verán tus clientes. Más adelante podrás añadir la razón social.</p>

            <form action="{{ route('negocio.nombre.store') }}" method="POST" id="neg_form_nombre">
                @csrf

                <label for="neg_nombre_comercial">Nombre del negocio:</label><br>
                <input type="text" name="neg_nombre_comercial" id="neg_nombre_comercial" required value="{{ old('neg_nombre_comercial') }}"><br><br>
                @error('neg_nombre_comercial')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label for="neg_sitio_web">Sitio web (opcional):</label><br>
                <input type="url" name="neg_sitio_web" id="neg_sitio_web" placeholder="www.tusitio.com" value="{{ old('neg_sitio_web') }}"><br><br>
                @error('neg_sitio_web')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <button type="submit">Continuar →</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/negocios/datos-negocio.js') }}"></script>