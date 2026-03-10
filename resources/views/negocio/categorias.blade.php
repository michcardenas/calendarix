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
            <h1>Selecciona las categorias que mejor describen tu negocio</h1>
            <p class="form-subtitle">Elige una o varias categorias principales y luego las subcategorias que apliquen</p>

            <form action="{{ route('negocio.categorias.store') }}" method="POST">
                @csrf

                @php
                    $grupos = [
                        [
                            'nombre' => 'Belleza',
                            'icon' => 'fa-spa',
                            'color' => '#e91e63',
                            'subs' => [
                                ['icon' => 'fa-scissors', 'nombre' => 'Peluqueria'],
                                ['icon' => 'fa-cut', 'nombre' => 'Barberia'],
                                ['icon' => 'fa-hand-sparkles', 'nombre' => 'Uñas'],
                                ['icon' => 'fa-star', 'nombre' => 'Depilacion'],
                                ['icon' => 'fa-paint-brush', 'nombre' => 'Maquillaje'],
                                ['icon' => 'fa-sun', 'nombre' => 'Cama Solar'],
                                ['icon' => 'fa-pen-nib', 'nombre' => 'Tatuaje'],
                                ['icon' => 'fa-dog', 'nombre' => 'Peluqueria canina'],
                                ['icon' => 'fa-heart', 'nombre' => 'Bienestar'],
                                ['icon' => 'fa-user-nurse', 'nombre' => 'Clinicas esteticas'],
                                ['icon' => 'fa-spa', 'nombre' => 'Spa'],
                                ['icon' => 'fa-hands', 'nombre' => 'Masajes'],
                            ]
                        ],
                        [
                            'nombre' => 'Cuidados',
                            'icon' => 'fa-heartbeat',
                            'color' => '#00bcd4',
                            'subs' => [
                                ['icon' => 'fa-leaf', 'nombre' => 'Acupuntura'],
                                ['icon' => 'fa-bone', 'nombre' => 'Quiropractico'],
                                ['icon' => 'fa-apple-alt', 'nombre' => 'Nutricionista'],
                                ['icon' => 'fa-comments', 'nombre' => 'Coaching'],
                                ['icon' => 'fa-walking', 'nombre' => 'Fisioterapia'],
                                ['icon' => 'fa-brain', 'nombre' => 'Psicologia'],
                                ['icon' => 'fa-tooth', 'nombre' => 'Odontologia'],
                                ['icon' => 'fa-running', 'nombre' => 'Kinesiologia'],
                            ]
                        ],
                        [
                            'nombre' => 'Fitness',
                            'icon' => 'fa-dumbbell',
                            'color' => '#ff9800',
                            'subs' => [
                                ['icon' => 'fa-pray', 'nombre' => 'Yoga'],
                                ['icon' => 'fa-dumbbell', 'nombre' => 'Gimnasio'],
                                ['icon' => 'fa-user-shield', 'nombre' => 'Entrenador personal'],
                                ['icon' => 'fa-child', 'nombre' => 'Pilates'],
                                ['icon' => 'fa-biking', 'nombre' => 'Ciclismo'],
                                ['icon' => 'fa-music', 'nombre' => 'Baile'],
                            ]
                        ],
                        [
                            'nombre' => 'Deportes',
                            'icon' => 'fa-trophy',
                            'color' => '#4caf50',
                            'subs' => [
                                ['icon' => 'fa-table-tennis', 'nombre' => 'Cancha de padel'],
                                ['icon' => 'fa-futbol', 'nombre' => 'Cancha de futbol 5'],
                                ['icon' => 'fa-baseball-ball', 'nombre' => 'Cancha de tenis'],
                                ['icon' => 'fa-table-tennis', 'nombre' => 'Cancha de pickleball'],
                            ]
                        ],
                    ];

                    $oldCats = old('neg_categorias', []);
                @endphp

                @foreach($grupos as $gi => $grupo)
                    <div class="cat-grupo" data-grupo="{{ $gi }}">
                        <div class="cat-grupo-header" data-toggle-grupo="{{ $gi }}">
                            <div class="cat-grupo-icon" style="background: {{ $grupo['color'] }};">
                                <i class="fas {{ $grupo['icon'] }}"></i>
                            </div>
                            <div class="cat-grupo-info">
                                <h2>{{ $grupo['nombre'] }}</h2>
                                <span class="cat-grupo-count" id="count-{{ $gi }}">0 seleccionadas</span>
                            </div>
                            <i class="fas fa-chevron-down cat-grupo-chevron" id="chevron-{{ $gi }}"></i>
                        </div>

                        <div class="cat-grupo-body" id="body-{{ $gi }}" style="display: none;">
                            <div class="sub-grid">
                                @foreach($grupo['subs'] as $sub)
                                    @php $isChecked = in_array($sub['nombre'], $oldCats); @endphp
                                    <label class="sub-card {{ $isChecked ? 'checked' : '' }}">
                                        <input
                                            type="checkbox"
                                            name="neg_categorias[]"
                                            value="{{ $sub['nombre'] }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                            data-grupo="{{ $gi }}"
                                        >
                                        <i class="fas {{ $sub['icon'] }}"></i>
                                        <span>{{ $sub['nombre'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Opción "Otro" --}}
                <div class="cat-otro">
                    <label class="cat-otro-check {{ old('neg_categoria_otro') ? 'checked' : '' }}">
                        <input type="checkbox" id="check_otro" {{ old('neg_categoria_otro') ? 'checked' : '' }}>
                        <i class="fas fa-ellipsis-h"></i>
                        <span>Otro</span>
                    </label>
                    <input
                        type="text"
                        name="neg_categoria_otro"
                        id="input_otro"
                        placeholder="Especifica tu categoria"
                        value="{{ old('neg_categoria_otro') }}"
                        style="display: {{ old('neg_categoria_otro') ? 'block' : 'none' }};"
                        class="cat-otro-input"
                    >
                </div>

                @error('neg_categorias')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <div class="buttons-row">
                    <a href="{{ route('negocio.datos') }}" class="btn-volver">← Volver</a>
                    <button type="submit">Continuar →</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Toggle de grupos
    document.querySelectorAll('[data-toggle-grupo]').forEach(function (header) {
        header.addEventListener('click', function () {
            var gi = this.dataset.toggleGrupo;
            var body = document.getElementById('body-' + gi);
            var chevron = document.getElementById('chevron-' + gi);
            var isOpen = body.style.display !== 'none';
            body.style.display = isOpen ? 'none' : 'block';
            chevron.classList.toggle('rotated', !isOpen);
        });
    });

    // Checkboxes
    document.querySelectorAll('.sub-card input[type="checkbox"]').forEach(function (cb) {
        var card = cb.closest('.sub-card');

        cb.addEventListener('change', function () {
            card.classList.toggle('checked', cb.checked);
            updateCount(cb.dataset.grupo);
        });
    });

    function updateCount(gi) {
        var total = document.querySelectorAll('.sub-card input[data-grupo="' + gi + '"]:checked').length;
        var el = document.getElementById('count-' + gi);
        if (el) el.textContent = total + ' seleccionada' + (total !== 1 ? 's' : '');
    }

    // Otro
    var checkOtro = document.getElementById('check_otro');
    var inputOtro = document.getElementById('input_otro');
    var labelOtro = checkOtro ? checkOtro.closest('.cat-otro-check') : null;
    if (checkOtro && inputOtro) {
        checkOtro.addEventListener('change', function () {
            inputOtro.style.display = checkOtro.checked ? 'block' : 'none';
            if (labelOtro) labelOtro.classList.toggle('checked', checkOtro.checked);
            if (!checkOtro.checked) inputOtro.value = '';
            if (checkOtro.checked) inputOtro.focus();
        });
    }

    // Init: abrir grupos con seleccion previa y actualizar conteos
    @foreach($grupos as $gi => $grupo)
        updateCount('{{ $gi }}');
        @if(collect($grupo['subs'])->pluck('nombre')->intersect($oldCats)->count())
            document.getElementById('body-{{ $gi }}').style.display = 'block';
            document.getElementById('chevron-{{ $gi }}').classList.add('rotated');
        @endif
    @endforeach
});
</script>
