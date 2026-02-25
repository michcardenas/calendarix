<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Negocio')</title>

    {{-- ✅ Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ✅ FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet" />

    {{-- ✅ Estilos extra --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Fix: modales altos — card scrollable cuando excede el viewport --}}
    <style>
        .fixed.inset-0.z-50 > div {
            max-height: 90vh !important;
            overflow-y: auto !important;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased" style="font-family: 'IBM Plex Sans', sans-serif;">
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- ✅ FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    @stack('scripts')

</body>
</html>
