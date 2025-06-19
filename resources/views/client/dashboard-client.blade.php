
@auth
    @role('cliente')

        {{-- CSS específico del dashboard admin --}}
        @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
        @endpush

        {{-- Container principal del dashboard --}}
        <div id="admin-dash-container" class="admin-dash-layout">

            {{-- Header del dashboard --}}
            <header id="admin-dash-header" class="admin-dash-header">
                <div class="admin-dash-header-content">
                    <h1 id="admin-dash-title" class="admin-dash-main-title">
                        <i class="fas fa-tachometer-alt admin-dash-title-icon"></i>
                        Dashboard cliente 
                    </h1>

                    <div id="admin-dash-breadcrumb" class="admin-dash-breadcrumb">
                        <span class="admin-dash-breadcrumb-item">
                            <i class="fas fa-home"></i> Inicio
                        </span>
                        <i class="fas fa-chevron-right admin-dash-breadcrumb-separator"></i>
                        <span class="admin-dash-breadcrumb-item active">Dashboard</span>
                    </div>
                </div>

                <div id="admin-dash-header-actions" class="admin-dash-header-actions">
                    <div id="admin-dash-admin-profile" class="admin-dash-admin-profile">
                        <div id="admin-dash-admin-avatar" class="admin-dash-admin-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div id="admin-dash-admin-info" class="admin-dash-admin-info">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>Administrador</p>
                        </div>
                        <i class="fas fa-chevron-down admin-dash-profile-arrow"></i>
                    </div>
                </div>
            </header>

            {{-- Contenido principal --}}
            <div id="admin-dash-content" class="admin-dash-main-content">


            </div>
        </div>

        {{-- JavaScript específico del dashboard admin --}}
        @push('scripts')
        <script src="{{ asset('js/admin-dashboard.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
        @endpush

    @else
        {{-- Usuario autenticado, pero no es administrador --}}
        <div class="admin-dash-access-denied">
            <div class="admin-dash-access-denied-content">
                <i class="fas fa-exclamation-triangle admin-dash-access-denied-icon"></i>
                <h2>Acceso Denegado</h2>
                <p>No tienes permisos de administrador para acceder a esta página.</p>
                <a href="{{ route('dashboard') }}" class="admin-dash-back-button">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    @endrole
@else
    {{-- Usuario no autenticado --}}
    <div class="admin-dash-access-denied">
        <div class="admin-dash-access-denied-content">
            <i class="fas fa-lock admin-dash-access-denied-icon"></i>
            <h2>Acceso Restringido</h2>
            <p>Necesitas iniciar sesión como administrador.</p>
            <a href="{{ route('login') }}" class="admin-dash-back-button">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
        </div>
    </div>
@endauth
