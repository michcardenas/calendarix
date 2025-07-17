<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Empresa')</title>

    {{-- ✅ Estilos Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
        }

        .layout-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: #3b0764;
            color: #fff;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
    </style>

    {{-- FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet" />

    {{-- Bootstrap CSS (si aún lo usas, aunque con Tailwind ya podrías prescindir) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    @stack('styles')
</head>

<body>
    <div class="layout-container">
        @include('empresa.partials.sidebar')

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    @stack('scripts')

    {{-- 🧠 Script de formateo de precios --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ids = [
                'precioServicio',
                'precio_compra_producto',
                'precio_venta_producto',
                'precio_promocional_producto'
            ];

            ids.forEach(id => {
                const input = document.getElementById(id);
                if (!input) return;

                input.addEventListener('input', function () {
                    const valorNumerico = this.value.replace(/\D/g, '');
                    this.value = valorNumerico ? new Intl.NumberFormat('es-CO').format(valorNumerico) : '';
                });

                const form = input.closest('form');
                if (form && !form.dataset.hasFormatListenerId) {
                    form.addEventListener('submit', function () {
                        ids.forEach(inputId => {
                            const campo = document.getElementById(inputId);
                            if (campo) {
                                campo.value = campo.value.replace(/\./g, '').replace(/\s/g, '');
                            }
                        });
                    });
                    form.dataset.hasFormatListenerId = 'true';
                }
            });

            const clases = [
                'prod_precio_compra',
                'prod_precio_venta',
                'prod_precio_promocional'
            ];

            clases.forEach(clase => {
                const inputs = document.querySelectorAll('.' + clase);

                inputs.forEach(input => {
                    input.addEventListener('input', function () {
                        const valorNumerico = this.value.replace(/\D/g, '');
                        this.value = valorNumerico ? new Intl.NumberFormat('es-CO').format(valorNumerico) : '';
                    });

                    const form = input.closest('form');
                    if (form && !form.dataset.hasFormatListenerClass) {
                        form.addEventListener('submit', function () {
                            clases.forEach(cl => {
                                const campos = form.querySelectorAll('.' + cl);
                                campos.forEach(campo => {
                                    campo.value = campo.value.replace(/\./g, '').replace(/\s/g, '');
                                });
                            });
                        });
                        form.dataset.hasFormatListenerClass = 'true';
                    }
                });
            });
        });
    </script>
</body>
</html>
