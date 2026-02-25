<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perfil del Negocio') — Calendarix</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { font-family: 'IBM Plex Sans', sans-serif; background-color: #f9fafb; color: #111827; }

        /* Reset: evitar que Tailwind base rompa el cover */
        img { display: block; max-width: 100%; }

        /* Fix modales altos */
        .fixed.inset-0.z-50 > div { max-height: 90vh !important; overflow-y: auto !important; }
    </style>

    @stack('styles')
</head>
<body class="antialiased">

    {{-- NAVBAR PÚBLICO --}}
   @auth
<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/calendarix-horizontal.png') }}"
                 alt="Calendarix"
                 class="h-7 w-auto"
                 onerror="this.style.display='none'">
        </a>
        <a href="{{ route('dashboard') }}"
           class="text-sm font-medium px-4 py-1.5 rounded-lg border transition"
           style="color: #5a31d7; border-color: #5a31d7;">
            Mi Panel
        </a>
    </div>
</nav>
@endauth

    {{-- CONTENIDO SIN PADDING — el perfil maneja su propio espaciado --}}
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    @stack('scripts')
</body>
</html>