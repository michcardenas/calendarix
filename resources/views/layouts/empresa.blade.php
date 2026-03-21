<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Empresa')</title>
    <link rel="icon" href="{{ asset('images/morado.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ✅ Estilos Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

        /* === Mobile Header === */
        .emp-mobile-header {
            display: none;
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(90,49,215,0.08);
            padding: 10px 16px;
            align-items: center;
            justify-content: space-between;
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
            background: none;
            border: 1px solid rgba(90,49,215,0.12);
            border-radius: 10px;
            cursor: pointer;
            color: #5a31d7;
            font-size: 1.15rem;
            transition: all 0.2s;
            -webkit-tap-highlight-color: transparent;
        }
        .emp-hamburger:hover,
        .emp-hamburger:active {
            background: rgba(90,49,215,0.06);
            border-color: rgba(90,49,215,0.25);
        }

        /* === Sidebar overlay (mobile) === */
        .emp-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 90;
            background: rgba(0,0,0,0.4);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .emp-sidebar-overlay.emp-menu-open {
            display: block;
            opacity: 1;
        }

        /* === Responsive === */
        @media (max-width: 768px) {
            .emp-mobile-header {
                display: flex;
            }
            .sidebar-clx {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 100;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
                overflow-y: auto;
            }
            .sidebar-clx.emp-menu-open {
                transform: translateX(0);
            }
            .content-area {
                padding: 16px;
            }
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
    {{-- Mobile Header --}}
    <div class="emp-mobile-header">
        <div class="emp-mobile-header__brand">
            <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
            <span>Calendarix</span>
        </div>
        <button type="button" class="emp-hamburger" id="empHamburger">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    {{-- Sidebar overlay --}}
    <div class="emp-sidebar-overlay" id="empSidebarOverlay"></div>

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

    {{-- Hamburger sidebar toggle JS --}}
    <script>
    (function() {
        var hamburger = document.getElementById('empHamburger');
        var overlay = document.getElementById('empSidebarOverlay');
        var sidebar = document.querySelector('.sidebar-clx');

        function openMenu() {
            if (sidebar) sidebar.classList.add('emp-menu-open');
            if (overlay) overlay.classList.add('emp-menu-open');
        }
        function closeMenu() {
            if (sidebar) sidebar.classList.remove('emp-menu-open');
            if (overlay) overlay.classList.remove('emp-menu-open');
        }

        if (hamburger) hamburger.addEventListener('click', openMenu);
        if (overlay) overlay.addEventListener('click', closeMenu);

        // Close on link click
        if (sidebar) {
            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    setTimeout(closeMenu, 100);
                });
            });
        }
    })();
    </script>

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