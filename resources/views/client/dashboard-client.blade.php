{{-- CSS específico del dashboard cliente --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/cliente/cliente-dashboard.css') }}">



{{-- Container principal del dashboard --}}
<div id="clx-dash-container" class="clx-container">

    <!-- Fondo animado -->
    <div class="clx-bg-shapes">
        <div class="clx-shape clx-shape-1"></div>
        <div class="clx-shape clx-shape-2"></div>
        <div class="clx-shape clx-shape-3"></div>
        <div class="clx-shape clx-shape-4"></div>
        <div class="clx-shape clx-shape-5"></div>
    </div>

    <!-- Sidebar -->
    <aside class="clx-sidebar">
        <div class="clx-logo">
            <h1>
                <img src="{{ asset('images/calendarix.png') }}" alt="Calendarix Logo" style="height: 64px; vertical-align: middle; margin-right: 8px;">
                Calendarix
            </h1>
        </div>


        <div class="clx-user-info">
            <div class="clx-user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="clx-user-name">{{ auth()->user()->name }}</div>
            <div class="clx-user-email">{{ auth()->user()->email }}</div>
        </div>

        <nav>
            <ul class="clx-nav">
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link active" data-clx-page="dashboard">
                        <i class="fas fa-home clx-nav-icon"></i>
                        Dashboard
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="appointments">
                        <i class="fas fa-calendar-check clx-nav-icon"></i>
                        Mis Citas
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="businesses">
                        <i class="fas fa-store clx-nav-icon"></i>
                        Negocios
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="favorites">
                        <i class="fas fa-heart clx-nav-icon"></i>
                        Favoritos
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="history">
                        <i class="fas fa-history clx-nav-icon"></i>
                        Historial
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="profile">
                        <i class="fas fa-user-cog clx-nav-icon"></i>
                        Mi Perfil
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-toggle="empresa-submenu">
                        <i class="fas fa-briefcase clx-nav-icon"></i>
                        Mi Empresa <i class="fas fa-chevron-down float-end"></i>
                    </a>

                    <ul id="empresa-submenu" class="clx-submenu" style="display: none; padding-left: 1rem;">
                        @forelse ($misEmpresas as $empresa)
                        <li>
                            <a href="{{ route('empresa.dashboard', $empresa->id) }}" class="clx-submenu-link">
                                {{ $empresa->neg_nombre_comercial ?? 'Sin nombre comercial' }}
                            </a>
                        </li>
                        @empty
                        <li><span class="text-sm text-gray-400">Sin empresas aún</span></li>
                        @endforelse
                    </ul>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="notifications">
                        <i class="fas fa-bell clx-nav-icon"></i>
                        Notificaciones
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="clx-main">
        <!-- Header de bienvenida -->
        <header class="clx-header">
            <div class="clx-welcome">
                <div>
                    <h2>¡Bienvenido de vuelta, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                    <p>Tienes <span id="clx-pending-count">3 citas</span> pendientes para esta semana</p>
                </div>
                <div class="clx-quick-actions">
                    <button class="clx-btn clx-btn-primary" id="clx-btn-book">
                        <i class="fas fa-plus"></i>
                        Agendar Cita
                    </button>
                    <button class="clx-btn clx-btn-secondary" id="clx-btn-search">
                        <i class="fas fa-search"></i>
                        Buscar Servicios
                    </button>
                </div>
                <div class="text-center mt-6">
                    <a href="{{ route('negocio.create') }}" class="clx-btn clx-btn-primary" class="w-full inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-md transition">
                        Registra tu negocio
                    </a>
                </div>
            </div>
        </header>

        <!-- Estadísticas -->
        <section class="clx-stats">
            <div class="clx-stat-card">
                <div class="clx-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-appointments">8</div>
                <div class="clx-stat-label">Citas este mes</div>
            </div>
            <div class="clx-stat-card">
                <div class="clx-stat-icon success">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-favorites">5</div>
                <div class="clx-stat-label">Negocios favoritos</div>
            </div>
            <div class="clx-stat-card">
                <div class="clx-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-pending">3</div>
                <div class="clx-stat-label">Citas pendientes</div>
            </div>
        </section>

        <!-- Contenido principal -->
        <section class="clx-content-grid">
            <!-- Próximas citas -->
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Próximas Citas</h3>
                    <button class="clx-btn clx-btn-ghost">Ver todas</button>
                </div>
                <div class="clx-card-body">
                    <div id="clx-appointments-list">
                        <!-- Se llena dinámicamente con JS -->
                    </div>
                </div>
            </div>

            <!-- Negocios recomendados -->
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Recomendados</h3>
                    <button class="clx-btn clx-btn-ghost">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div class="clx-card-body">
                    <div id="clx-recommendations-list">
                        <!-- Se llena dinámicamente con JS -->
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleLink = document.querySelector('[data-clx-toggle="empresa-submenu"]');
        const submenu = document.getElementById('empresa-submenu');

        if (toggleLink && submenu) {
            toggleLink.addEventListener('click', function(e) {
                e.preventDefault();
                submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
            });
        }
    });
</script>


{{-- JavaScript específico del dashboard cliente --}}

<script src="{{ asset('js/cliente/cliente-dashboard.js') }}"></script>