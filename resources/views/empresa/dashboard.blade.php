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
        <h2><i class="fas fa-store"></i> {{ $empresa->neg_nombre_comercial }}</h2>
        <p class="subtext" style="font-size: 0.9rem; color: #ccc; margin-bottom: 1rem; padding-left: 10px;">
            {{ $empresa->neg_email }}
        </p>
        <a href="#"><i class="fas fa-calendar-alt"></i> Agenda</a>
        <a href="#"><i class="fas fa-users"></i> Clientes</a>
        <a href="#"><i class="fas fa-cog"></i> Configuración</a>
       
    </aside>
 <a href="{{ url('dashboard') }}"> Salir</a>
    <main class="content">
        <div class="header">Dashboard de {{ $empresa->neg_nombre_comercial }}</div>

        <div class="alert alert-info" style="background-color: #e6f2ff; border-left: 5px solid #00304b; padding: 1rem; margin-bottom: 2rem; border-radius: 8px;">
            <strong>🎨 Personaliza tu página:</strong> Diseña cómo se verá tu perfil público arrastrando y organizando tus secciones.
            <a href="{{ route('empresa.editor', ['id' => $empresa->id]) }}" class="btn btn-sm btn-primary" style="margin-left: 1rem;">Diseñar mi vista</a>
        </div>

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
