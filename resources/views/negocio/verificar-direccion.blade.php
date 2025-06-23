
    <link rel="stylesheet" href="{{ asset('css/negocios/veri-ubi-negocios.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 12; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <div class="form-content">
            <h3>Configuración de cuenta</h3>
            <h1><strong>Verifica la dirección de tu centro</strong></h1>
            <p>Asegúrate de que el pin está en la ubicación correcta.</p>

            {{-- Mapa (más adelante puedes integrar Google Maps) --}}
            <div style="width: 100%; max-width: 600px; height: 300px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                <p style="color: #777;">Aquí iría el mapa</p>
            </div>

            <br>

            <form action="{{ route('negocio.verificacion.store') }}" method="POST">
                @csrf
                <button type="submit">Confirmar ubicación y continuar →</button>
            </form>
        </div>
    </div>
</div>

    <script src="{{ asset('js/negocios/veri-ubi-negocios.js') }}"></script>
