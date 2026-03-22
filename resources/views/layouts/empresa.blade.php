<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Empresa')</title>
    <link rel="icon" href="{{ asset('images/morado.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ✅ Estilos Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet" />

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        /* ── Reset base ───────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #f9f9f9;
            overflow-x: hidden;
        }

        /* ── Layout principal ─────────────────────────────────── */
        .layout-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
        }

        /* ── Área de contenido ────────────────────────────────── */
        .content-area {
            flex: 1;
            padding: 0;
            background-color: #ffffff;
            min-height: 100vh;
            min-width: 0;        /* ← crítico en flex para evitar overflow */
            width: 0;            /* ← fuerza al flex a calcular bien el ancho */
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ── Mobile Header ────────────────────────────────────── */
        .emp-mobile-header {
            display: none;
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(90, 49, 215, 0.08);
            padding: 10px 16px;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .emp-mobile-header__brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .emp-mobile-header__brand img {
            height: 26px;
            width: auto;
        }
        .emp-mobile-header__brand span {
            font-size: 1rem;
            font-weight: 700;
            color: #5a31d7;
        }
        .emp-mobile-header__biz {
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
        }

        .emp-hamburger {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            background: none;
            border: 1px solid rgba(90, 49, 215, 0.12);
            border-radius: 10px;
            cursor: pointer;
            color: #5a31d7;
            font-size: 1.15rem;
            transition: all 0.2s;
            -webkit-tap-highlight-color: transparent;
        }
        .emp-hamburger:hover,
        .emp-hamburger:active {
            background: rgba(90, 49, 215, 0.06);
            border-color: rgba(90, 49, 215, 0.25);
        }

        /* ── Sidebar overlay ──────────────────────────────────── */
        .emp-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 90;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .emp-sidebar-overlay.emp-menu-open {
            display: block;
            opacity: 1;
        }

        /* ── Fix Tailwind + Bootstrap ─────────────────────────── */
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
            color: #ffffff !important;
        }
        .content-area .bg-gray-200  { background-color: #e5e7eb !important; }
        .content-area .bg-gray-400  { background-color: #9ca3af !important; }
        .content-area .bg-green-500 { background-color: #22c55e !important; color: #ffffff !important; }
        .content-area .bg-green-500:hover,
        .content-area .hover\:bg-green-600:hover { background-color: #16a34a !important; }
        .content-area .bg-red-500   { background-color: #ef4444 !important; color: #ffffff !important; }
        .content-area .text-white   { color: #ffffff !important; }
        .content-area .text-gray-700 { color: #374151 !important; }
        .content-area .text-\[\#5a31d7\] { color: #5a31d7 !important; }
        .content-area .text-\[\#3B4269\] { color: #3B4269 !important; }
        .content-area .border-\[\#5a31d7\] { border-color: #5a31d7 !important; }
        .content-area .bg-white\/90 { background-color: rgba(255,255,255,0.9) !important; }
        .content-area .text-red-600 { color: #dc2626 !important; }
        .content-area a { text-decoration: none !important; }
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
        /* Fix modales altos */
        .fixed.inset-0.z-50 > div {
            max-height: 90vh !important;
            overflow-y: auto !important;
        }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 768px) {

            /* Muestra el header móvil */
            .emp-mobile-header {
                display: flex;
            }

            /* Sidebar: fuera del flujo del documento */
            .sidebar-clx {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                width: 260px !important;
                z-index: 100 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
            }
            .sidebar-clx.emp-menu-open {
                transform: translateX(0) !important;
            }

            /* Content area ocupa TODO el ancho */
            .content-area {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                padding: 0 !important;
                flex: 1 1 100% !important;
            }

            /* El layout no tiene flex horizontal en móvil */
            .layout-container {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .emp-mobile-header {
                padding: 8px 12px;
            }
            .emp-mobile-header__brand span {
                font-size: 0.9rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- Mobile Header --}}
    <div class="emp-mobile-header">
        <div class="emp-mobile-header__brand">
            <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
            <span>Calendarix</span>
        </div>
        <button type="button" class="emp-hamburger" id="empHamburger" aria-label="Abrir menú">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    {{-- Sidebar overlay --}}
    <div class="emp-sidebar-overlay" id="empSidebarOverlay"></div>

    <div class="layout-container">
        @include('empresa.partials.sidebar', [
            'empresa'        => $empresa,
            'currentPage'    => $currentPage    ?? null,
            'currentSubPage' => $currentSubPage ?? null,
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

    {{-- Sidebar toggle --}}
    <script>
    (function () {
        var hamburger = document.getElementById('empHamburger');
        var overlay   = document.getElementById('empSidebarOverlay');
        var sidebar   = document.querySelector('.sidebar-clx');

        function openMenu() {
            if (sidebar) sidebar.classList.add('emp-menu-open');
            if (overlay) overlay.classList.add('emp-menu-open');
            document.body.style.overflow = 'hidden'; // evita scroll del fondo
        }
        function closeMenu() {
            if (sidebar) sidebar.classList.remove('emp-menu-open');
            if (overlay) overlay.classList.remove('emp-menu-open');
            document.body.style.overflow = '';
        }

        if (hamburger) hamburger.addEventListener('click', openMenu);
        if (overlay)   overlay.addEventListener('click', closeMenu);

        // Cierra al navegar
        if (sidebar) {
            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    setTimeout(closeMenu, 100);
                });
            });
        }

        // Cierra con ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    })();
    </script>

    {{-- Formateo de precios --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var ids = ['precioServicio'];

        ids.forEach(function (id) {
            var input = document.getElementById(id);
            if (!input) return;

            input.addEventListener('input', function () {
                var valorNumerico = this.value.replace(/\D/g, '');
                this.value = valorNumerico
                    ? new Intl.NumberFormat('es-CO').format(valorNumerico)
                    : '';
            });

            var form = input.closest('form');
            if (form && !form.dataset.hasFormatListenerId) {
                form.addEventListener('submit', function () {
                    ids.forEach(function (inputId) {
                        var campo = document.getElementById(inputId);
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