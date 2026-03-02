<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perfil del Negocio') — Calendarix</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        html, body { font-family: 'IBM Plex Sans', sans-serif; background-color: #f9fafb; color: #111827; }
        img { display: block; max-width: 100%; }
        .fixed.inset-0.z-50 > div { max-height: 90vh !important; overflow-y: auto !important; }

        /* Navbar */
        .clx-navbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(90,49,215,0.08);
            box-shadow: 0 1px 8px rgba(90,49,215,0.06);
        }
        .clx-navbar-inner {
            max-width: 1152px;
            margin: 0 auto;
            padding: 0 1rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .clx-navbar-logo { height: 28px; width: auto; }
        .clx-navbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            font-size: 0.82rem;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .clx-navbar-btn-primary {
            background: #5a31d7;
            color: #fff;
            box-shadow: 0 2px 8px rgba(90,49,215,0.25);
        }
        .clx-navbar-btn-primary:hover {
            background: #7b5ce0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(90,49,215,0.35);
        }
        .clx-navbar-btn-outline {
            border: 1.5px solid #5a31d7;
            color: #5a31d7;
            background: transparent;
        }
        .clx-navbar-btn-outline:hover {
            background: #5a31d7;
            color: #fff;
        }

        /* Fade in animation */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Footer */
        .clx-footer {
            text-align: center;
            padding: 2rem 1rem;
            font-size: 0.72rem;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            background: #fff;
        }
        .clx-footer a {
            color: #5a31d7;
            text-decoration: none;
            font-weight: 700;
        }
        .clx-footer a:hover { text-decoration: underline; }
    </style>

    @stack('styles')
</head>
<body class="antialiased">

    {{-- NAVBAR --}}
    <nav class="clx-navbar">
        <div class="clx-navbar-inner">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/calendarix-horizontal.png') }}"
                     alt="Calendarix"
                     class="clx-navbar-logo"
                     onerror="this.outerHTML='<span style=\'font-size:1.1rem;font-weight:800;color:#5a31d7;letter-spacing:-0.02em;\'>Calendarix</span>'">
            </a>
            <div style="display:flex;align-items:center;gap:8px;">
                @auth
                    <a href="{{ route('dashboard') }}" class="clx-navbar-btn clx-navbar-btn-outline">
                        <i class="fas fa-th-large" style="font-size:0.72rem;"></i> Mi Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="clx-navbar-btn clx-navbar-btn-outline">
                        Iniciar sesion
                    </a>
                    <a href="{{ route('register') }}" class="clx-navbar-btn clx-navbar-btn-primary">
                        Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    {{-- Footer --}}
    <footer class="clx-footer">
        Hecho con <i class="fas fa-heart" style="color:#ef4444;font-size:0.6rem;"></i> en
        <a href="{{ url('/') }}">Calendarix</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    {{-- Fade-in observer --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-up').forEach(function(el) { observer.observe(el); });
    });
    </script>

    @stack('scripts')
</body>
</html>
