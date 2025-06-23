
    <link rel="stylesheet" href="{{ asset('css/negocios/ubicacion-negocios.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 10; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <div class="form-content">
            <h3>Configuración de cuenta</h3>
            <h1><strong>Indica la dirección de tu centro</strong></h1>
            <p>Añade la ubicación de tu negocio para que los clientes puedan encontrarte fácilmente.</p>

            <form action="{{ route('negocio.ubicacion.store') }}" method="POST">
                @csrf

                <label for="neg_direccion">¿Cuál es la ubicación de tu negocio?</label><br>
                <input type="text" name="neg_direccion" id="neg_direccion" placeholder="Ej: Carrera 12 #34-56, Bogotá" value="{{ old('neg_direccion') }}"><br><br>
                @error('neg_direccion')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label>
                    <input type="checkbox" name="neg_virtual" value="1" {{ old('neg_virtual') ? 'checked' : '' }}>
                    Mi negocio no tiene una dirección física (solo ofrezco servicios por teléfono y online)
                </label><br><br>
                @error('neg_virtual')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">Continuar →</button>
            </form>
        </div>
    </div>
</div>

    <script src="{{ asset('js/negocios/ubicacion-negocios.js') }}"></script>
