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

{{-- Hamburger button (mobile only) --}}
<button class="admin-hamburger" id="adminHamburger" aria-label="Abrir menú">
    <span></span>
    <span></span>
    <span></span>
</button>

{{-- Overlay (mobile only) --}}
<div class="admin-sidebar-overlay" id="adminOverlay"></div>

{{-- SIDEBAR (fuera del grid para que fixed funcione al 100% en mobile) --}}
<aside class="admin-sidebar" id="adminSidebar">
    @include('admin.partials.sidebar')
</aside>

<div class="admin-dashboard">
    <div class="admin-main-layout">

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
