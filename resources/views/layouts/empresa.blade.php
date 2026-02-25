<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Empresa')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ✅ Estilos Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #f9f9f9;
        }

        .layout-container {
            display: flex;
            min-height: 100vh;
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

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    {{-- Fix: Tailwind overrides para que Bootstrap no rompa botones/modales --}}
    <style>
        .content-area .bg-\[\#5a31d7\] {
            background-color: #5a31d7 !important;
            color: #ffffff !important;
        }
        .content-area .bg-\[\#5a31d7\]:hover,
        .content-area .hover\:bg-\[\#7b5ce0\]:hover {
            background-color: #7b5ce0 !important;
        }
        .content-area .hover\:bg-\[\#4a22b8\]:hover {
            background-color: #4a22b8 !important;
        }
        .content-area .bg-gray-200 {
            background-color: #e5e7eb !important;
        }
        .content-area .bg-gray-400 {
            background-color: #9ca3af !important;
        }
        .content-area .bg-green-500 {
            background-color: #22c55e !important;
            color: #ffffff !important;
        }
        .content-area .bg-green-500:hover,
        .content-area .hover\:bg-green-600:hover {
            background-color: #16a34a !important;
        }
        .content-area .bg-red-500 {
            background-color: #ef4444 !important;
            color: #ffffff !important;
        }
        .content-area .text-white {
            color: #ffffff !important;
        }
        .content-area .text-gray-700 {
            color: #374151 !important;
        }
        .content-area .text-\[\#5a31d7\] {
            color: #5a31d7 !important;
        }
        .content-area .text-\[\#3B4269\] {
            color: #3B4269 !important;
        }
        .content-area .border-\[\#5a31d7\] {
            border-color: #5a31d7 !important;
        }
        .content-area .hover\:bg-\[\#4a22b8\]:hover {
            color: #ffffff !important;
        }
        .content-area .bg-white\/90 {
            background-color: rgba(255,255,255,0.9) !important;
        }
        .content-area .text-red-600 {
            color: #dc2626 !important;
        }
        .content-area a {
            text-decoration: none !important;
        }
        .content-area input.focus\:ring-2:focus,
        .content-area textarea.focus\:ring-2:focus,
        .content-area select.focus\:ring-2:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(90, 49, 215, 0.3) !important;
            border-color: #5a31d7 !important;
        }
        .fixed.inset-0.bg-black\/50 {
            background-color: rgba(0,0,0,0.5) !important;
        }
        /* Fix: modales altos — card scrollable cuando excede el viewport */
        .fixed.inset-0.z-50 > div {
            max-height: 90vh !important;
            overflow-y: auto !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="layout-container">
        @include('empresa.partials.sidebar', [
        'empresa' => $empresa ,
        'currentPage' => $currentPage ?? null,
        'currentSubPage' => $currentSubPage ?? null
        ])

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
        document.addEventListener('DOMContentLoaded', function() {
            const ids = [
                'precioServicio'
            ];

            ids.forEach(id => {
                const input = document.getElementById(id);
                if (!input) return;

                input.addEventListener('input', function() {
                    const valorNumerico = this.value.replace(/\D/g, '');
                    this.value = valorNumerico ? new Intl.NumberFormat('es-CO').format(valorNumerico) : '';
                });

                const form = input.closest('form');
                if (form && !form.dataset.hasFormatListenerId) {
                    form.addEventListener('submit', function() {
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

        });
    </script>
</body>

</html>