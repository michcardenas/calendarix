<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Negocio')</title>

    {{-- ✅ Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ✅ FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet" />

    {{-- ✅ Estilos extra --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- ✅ FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    @stack('scripts')

</body>
</html>
