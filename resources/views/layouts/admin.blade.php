@auth
    @role('Administrador')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Calendarix</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

<div class="admin-dashboard">
    <div class="admin-main-layout">

        {{-- SIDEBAR --}}
        <aside class="admin-sidebar">
            @include('admin.partials.sidebar')
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-container">
            @yield('admin-content')
        </div>

    </div>
</div>

@stack('scripts-before')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@stack('scripts')

</body>
</html>
    @endrole
@endauth
