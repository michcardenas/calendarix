    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/empresa/dashboard-empresa.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 15; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="layout">
    <aside class="sidebar">
        <h2><i class="fas fa-store"></i> Mi Negocio</h2>
        <a href="#"><i class="fas fa-calendar-alt"></i> Agenda</a>
        <a href="#"><i class="fas fa-users"></i> Clientes</a>
        <a href="#"><i class="fas fa-cog"></i> Configuración</a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </aside>

    <main class="content">
        <div class="header">Dashboard de Empresa</div>

        <div class="stats">
            <div class="card">
                <h4>Total de Citas</h4>
                <p>12</p>
            </div>
            <div class="card">
                <h4>Servicios Activos</h4>
                <p>5</p>
            </div>
            <div class="card">
                <h4>Miembros del equipo</h4>
                <p>3</p>
            </div>
        </div>

        <div class="card">
            <h4>Próximas citas</h4>
            <p>Aquí podrías mostrar la lista de próximas reservas o acciones importantes.</p>
        </div>
    </main>
</div>

<script src="{{ asset('js/empresa/dashboard-empresa.js') }}"></script>
