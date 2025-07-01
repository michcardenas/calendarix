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

                    @foreach($categorias as $cat)
                        @php
                            $isOtros = $cat['nombre'] === 'Otros';
                            $isChecked = in_array($cat['nombre'], old('neg_categorias', []));
                        @endphp

                        <label class="categoria-card {{ $isChecked ? 'checked' : '' }}">
                            <input
                                type="checkbox"
                                name="neg_categorias[]"
                                value="{{ $cat['nombre'] }}"
                                {{ $isChecked ? 'checked' : '' }}
                                data-otros="{{ $isOtros ? '1' : '0' }}"
                            >
                            <i class="fas {{ $cat['icon'] }}"></i>
                            <span>{{ $cat['nombre'] }}</span>

                            @if($isOtros)
                                <input
                                    type="text"
                                    name="neg_categoria_otro"
                                    id="input_otro"
                                    placeholder="Especifica tu categoría"
                                    value="{{ old('neg_categoria_otro') }}"
                                    style="display: {{ old('neg_categoria_otro') ? 'block' : 'none' }};
                                           margin-top: 10px;
                                           padding: 8px;
                                           border-radius: 8px;
                                           border: 1px solid #ccc;
                                           width: 90%;"
                                >
                            @endif
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.categoria-card input[type="checkbox"]').forEach(function (checkbox) {
            const card = checkbox.closest('.categoria-card');
            const isOtros = checkbox.dataset.otros === "1";
            const inputOtro = card.querySelector('#input_otro');

            // Activar visual si ya estaba seleccionado
            if (checkbox.checked) {
                card.classList.add('checked');
                if (isOtros && inputOtro) inputOtro.style.display = 'block';
            }

            // Cambiar estado visual al hacer clic
            checkbox.addEventListener('change', function () {
                card.classList.toggle('checked', checkbox.checked);
                if (isOtros && inputOtro) {
                    inputOtro.style.display = checkbox.checked ? 'block' : 'none';
                    if (!checkbox.checked) inputOtro.value = ""; // Opcional: limpiar si se desmarca
                }
            });
        });
    });
</script>
