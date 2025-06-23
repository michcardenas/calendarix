
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/negocios/categorias-negocio.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 10; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <div class="form-content">
            <h1>Selecciona las categorías que mejor describen tu negocio</h1>

            <form action="{{ route('negocio.categorias.store') }}" method="POST">
                @csrf

                <div class="categoria-grid">
                    @php
                        $categorias = [
                            ['icon' => 'fa-scissors', 'nombre' => 'Peluquería'],
                            ['icon' => 'fa-hand-sparkles', 'nombre' => 'Salón de uñas'],
                            ['icon' => 'fa-eye', 'nombre' => 'Cejas y pestañas'],
                            ['icon' => 'fa-user-alt', 'nombre' => 'Salón de belleza'],
                            ['icon' => 'fa-spa', 'nombre' => 'Spa y sauna'],
                            ['icon' => 'fa-heartbeat', 'nombre' => 'Centro estético'],
                            ['icon' => 'fa-cut', 'nombre' => 'Barbería'],
                            ['icon' => 'fa-dog', 'nombre' => 'Peluquería mascotas'],
                            ['icon' => 'fa-user-nurse', 'nombre' => 'Clínica'],
                            ['icon' => 'fa-biking', 'nombre' => 'Fitness'],
                            ['icon' => 'fa-ellipsis-h', 'nombre' => 'Otros'],
                        ];
                    @endphp

                    @foreach($categorias as $i => $cat)
                        <label class="categoria-card" onclick="this.classList.toggle('checked')">
                            <input type="checkbox" name="neg_categorias[]" value="{{ $cat['nombre'] }}" {{ in_array($cat['nombre'], old('neg_categorias', [])) ? 'checked' : '' }}>
                            <i class="fas {{ $cat['icon'] }}"></i>
                            <span>{{ $cat['nombre'] }}</span>
                        </label>
                    @endforeach
                </div>

                @error('neg_categorias')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <br>
                <button type="submit">Continuar →</button>
            </form>
        </div>
    </div>
</div>