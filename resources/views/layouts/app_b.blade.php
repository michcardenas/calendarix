<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Calendarix - Reserva cualquier servicio cerca de ti')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <style>
        /* ============================================
           CALENDARIX - LAYOUT BASE STYLES
           ============================================ */

        :root {
            /* Colores principales — Brandbook Calendarix */
            --primary-600: #4a22b8;
            --primary-500: #5a31d7;
            --primary-400: #7b5ce0;
            --primary-300: #a38ee9;
            --primary-200: #c7bdf2;
            --primary-100: #ebe7fa;
            --primary-50: #f3f0ff;

            /* Marca */
            --secondary: #32ccbc;
            --accent: #ffa8d7;
            --lilac: #df8be8;
            --turq-light: #90f7ec;
            
            /* Neutrales */
            --gray-900: #111827;
            --gray-800: #1f2937;
            --gray-700: #374151;
            --gray-600: #4b5563;
            --gray-500: #6b7280;
            --gray-400: #9ca3af;
            --gray-300: #d1d5db;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --gray-50: #f9fafb;
            --white: #ffffff;
            
            /* Estados */
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --info: #3b82f6;
            
            /* Efectos */
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            
            /* Transiciones */
            --transition-fast: 150ms ease;
            --transition-base: 200ms ease;
            --transition-slow: 300ms ease;
            
            /* Header height */
            --header-height: 70px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--gray-800);
            line-height: 1.6;
            background: var(--white);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ============================================
           HEADER
           ============================================ */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            transition: box-shadow var(--transition-base);
        }

        .app-header.scrolled {
            box-shadow: var(--shadow-md);
        }

        .app-header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-height);
        }

        .app-logo {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
            color: var(--gray-900);
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .app-logo img {
            height: 42px;
            width: auto;
        }

        .app-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .app-nav-link {
            color: var(--gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all var(--transition-fast);
        }

        .app-nav-link:hover {
            color: var(--gray-900);
            background: var(--gray-100);
        }

        .app-nav-btn {
            background: var(--primary-500);
            color: var(--white);
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all var(--transition-fast);
            margin-left: 0.5rem;
        }

        .app-nav-btn:hover {
            background: var(--primary-600);
            transform: translateY(-1px);
        }

        .app-nav-btn-outline {
            background: transparent;
            color: var(--primary-600);
            border: 2px solid var(--primary-500);
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .app-nav-btn-outline:hover {
            background: var(--primary-100);
        }

        /* Mobile menu button */
        .app-mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-700);
            cursor: pointer;
            padding: 0.5rem;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .app-main {
            flex: 1;
            padding-top: var(--header-height);
        }

        /* ============================================
           FOOTER
           ============================================ */
        .app-footer {
            background: var(--gray-900);
            color: var(--gray-400);
            padding: 3rem 1.5rem 1.5rem;
            margin-top: auto;
        }

        .app-footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .app-footer-grid {
            display: grid;
            grid-template-columns: 2fr repeat(3, 1fr);
            gap: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--gray-800);
        }

        .app-footer-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--white);
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .app-footer-brand img {
            height: 36px;
        }

        .app-footer-description {
            font-size: 0.9375rem;
            line-height: 1.7;
            margin-bottom: 1.25rem;
        }

        .app-footer-social {
            display: flex;
            gap: 1rem;
        }

        .app-footer-social a {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-800);
            color: var(--gray-400);
            border-radius: 8px;
            transition: all var(--transition-fast);
            text-decoration: none;
        }

        .app-footer-social a:hover {
            background: var(--secondary);
            color: var(--white);
        }

        .app-footer-title {
            color: var(--white);
            font-weight: 600;
            font-size: 0.9375rem;
            margin-bottom: 1rem;
        }

        .app-footer-links {
            list-style: none;
        }

        .app-footer-links li {
            margin-bottom: 0.625rem;
        }

        .app-footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color var(--transition-fast);
        }

        .app-footer-links a:hover {
            color: var(--secondary);
        }

        .app-footer-bottom {
            padding-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
        }

        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all var(--transition-fast);
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--primary-500);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray-800);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        .btn-lg {
            padding: 0.875rem 2rem;
            font-size: 1rem;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1024px) {
            .app-footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .app-header-container {
                position: relative;
            }

            .app-mobile-menu-btn {
                display: block;
            }

            .app-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--white);
                flex-direction: column;
                padding: 1rem;
                border-top: 1px solid var(--gray-200);
                box-shadow: var(--shadow-lg);
                gap: 0.5rem;
            }

            .app-nav.active {
                display: flex;
            }

            .app-nav-link,
            .app-nav-btn,
            .app-nav-btn-outline {
                width: 100%;
                text-align: center;
                padding: 0.75rem 1rem;
            }

            .app-nav-btn {
                margin-left: 0;
                margin-top: 0.5rem;
            }

            .app-footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .app-footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>

    @yield('styles')
    @stack('head')
</head>

<body>
    <!-- HEADER -->
    <header class="app-header" id="app-header">
        <div class="app-header-container">
            <a href="{{ url('/') }}" class="app-logo">
                <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
                <span>Calendarix</span>
            </a>

            <button class="app-mobile-menu-btn" onclick="toggleAppMenu()" aria-label="Menú">
                <i class="fas fa-bars" id="menu-icon"></i>
            </button>

            <nav class="app-nav" id="app-nav">
                <a href="#" class="app-nav-btn-outline">Para Negocios</a>
                <a href="#" class="app-nav-link">Ofertas del día</a>
                <a href="#" class="app-nav-link">Profesionales</a>
                @guest
                    <a href="{{ route('login') }}" class="app-nav-link">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="app-nav-btn">
                        Registrar mi Negocio
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}" class="app-nav-link">Dashboard</a>
                    <a href="#" class="app-nav-link">
                        {{ Auth::user()->name }}
                    </a>
                @endguest
            </nav>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="app-main">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="app-footer">
        <div class="app-footer-container">
            <div class="app-footer-grid">
                <div>
                    <div class="app-footer-brand">
                        <img src="{{ asset('images/azul.png') }}" alt="Calendarix">
                        <span>Calendarix</span>
                    </div>
                    <p class="app-footer-description">
                        La plataforma para reservar cualquier servicio cerca de ti.
                        Conectamos clientes con los mejores profesionales y negocios.
                    </p>
                    <div class="app-footer-social">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="app-footer-title">Para Clientes</h4>
                    <ul class="app-footer-links">
                        <li><a href="#">Buscar servicios</a></li>
                        <li><a href="#">Ofertas del día</a></li>
                        <li><a href="#">Cómo funciona</a></li>
                        <li><a href="#">Centro de ayuda</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="app-footer-title">Para Negocios</h4>
                    <ul class="app-footer-links">
                        <li><a href="#">Registrar negocio</a></li>
                        <li><a href="#">Planes y precios</a></li>
                        <li><a href="#">Recursos</a></li>
                        <li><a href="#">Soporte</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="app-footer-title">Compañía</h4>
                    <ul class="app-footer-links">
                        <li><a href="#">Sobre nosotros</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Términos de uso</a></li>
                        <li><a href="#">Privacidad</a></li>
                    </ul>
                </div>
            </div>

            <div class="app-footer-bottom">
                <p>&copy; {{ date('Y') }} Calendarix. Todos los derechos reservados.</p>
                <p>Hecho con ❤️ en Colombia</p>
            </div>
        </div>
    </footer>

    <!-- Base Scripts -->
    <script>
        // Header scroll effect
        const appHeader = document.getElementById('app-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                appHeader.classList.add('scrolled');
            } else {
                appHeader.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        function toggleAppMenu() {
            const nav = document.getElementById('app-nav');
            const icon = document.getElementById('menu-icon');
            nav.classList.toggle('active');
            
            if (nav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    </script>

    @stack('scripts')
</body>

</html>