 
    <link rel="stylesheet" href="{{ asset('css/negocios/equipo-negocios.css') }}">

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
            <h1><strong>¿Cuántos miembros tiene tu equipo?</strong></h1>
            <p>Esto nos ayudará a configurar tu calendario correctamente</p>

            <form action="{{ route('negocio.equipo.store') }}" method="POST">
                @csrf

                @php
                    $opciones = [
                        'Solo yo',
                        '2-5 personas',
                        '6-10 personas',
                        'Más de 11 personas'
                    ];
                @endphp

                @foreach($opciones as $opcion)
                    <label style="display: block; margin: 10px 0;">
                        <input type="radio" name="neg_equipo" value="{{ $opcion }}" required {{ old('neg_equipo') == $opcion ? 'checked' : '' }}>
                        {{ $opcion }}
                    </label>
                @endforeach

                @error('neg_equipo')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <br>
                <button type="submit">Continuar →</button>
            </form>
        </div>
    </div>
</div>

    <script src="{{ asset('js/negocios/equipo-negocios.js') }}"></script>
