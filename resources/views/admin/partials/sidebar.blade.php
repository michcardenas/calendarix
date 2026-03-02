@php $menu = $activeMenu ?? ''; @endphp

<div class="admin-sidebar-header">
    <h3 class="admin-sidebar-title">
        <i class="fas fa-sitemap"></i>
        Panel de Administración
    </h3>
</div>

<nav class="admin-sidebar-nav">

    {{-- Gestión de Contenido --}}
    <div class="admin-nav-section {{ str_starts_with($menu, 'page-editor') ? 'expanded' : '' }}">
        <div class="admin-nav-category">
            <i class="fas fa-edit admin-nav-category-icon"></i>
            <span class="admin-nav-category-title">Gestión de Contenido</span>
            <i class="fas fa-chevron-down admin-nav-toggle"></i>
        </div>
        <ul class="admin-nav-items">
            <li class="admin-nav-item">
                <a href="#" class="admin-nav-link" data-demo="seo">
                    <i class="fas fa-search-plus admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Gestión SEO</span>
                        <small>Meta tags, keywords, sitemap</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.page-editor.index') }}"
                   class="admin-nav-link {{ $menu === 'page-editor' ? 'active' : '' }}">
                    <i class="fas fa-palette admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Editor de Páginas</span>
                        <small>Personalizar contenido</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="#" class="admin-nav-link" data-demo="media">
                    <i class="fas fa-images admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Biblioteca de Medios</span>
                        <small>Imágenes y archivos</small>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    {{-- Gestión de Usuarios --}}
    <div class="admin-nav-section {{ str_starts_with($menu, 'users') ? 'expanded' : '' }}">
        <div class="admin-nav-category">
            <i class="fas fa-users-cog admin-nav-category-icon"></i>
            <span class="admin-nav-category-title">Gestión de Usuarios</span>
            <i class="fas fa-chevron-down admin-nav-toggle"></i>
        </div>
        <ul class="admin-nav-items">
            <li class="admin-nav-item">
                <a href="{{ route('admin.users.index') }}"
                   class="admin-nav-link {{ $menu === 'users' ? 'active' : '' }}"
                   id="usr_nav_index">
                    <i class="fas fa-users admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Gestionar Usuarios</span>
                        <small>Ver y editar usuarios</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.users.create') }}"
                   class="admin-nav-link {{ $menu === 'users.create' ? 'active' : '' }}">
                    <i class="fas fa-user-plus admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Nuevo Usuario</span>
                        <small>Agregar usuario al sistema</small>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    {{-- Planes y Facturación --}}
    <div class="admin-nav-section {{ str_starts_with($menu, 'plans') || $menu === 'suscripciones' ? 'expanded' : '' }}">
        <div class="admin-nav-category">
            <i class="fas fa-layer-group admin-nav-category-icon"></i>
            <span class="admin-nav-category-title">Planes y Facturación</span>
            <i class="fas fa-chevron-down admin-nav-toggle"></i>
        </div>
        <ul class="admin-nav-items">
            <li class="admin-nav-item">
                <a href="{{ route('admin.plans.index') }}"
                   class="admin-nav-link {{ $menu === 'plans' ? 'active' : '' }}"
                   id="plan_nav_index">
                    <i class="fas fa-tags admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Gestionar Planes</span>
                        <small>Crear y editar planes</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.plans.create') }}"
                   class="admin-nav-link {{ $menu === 'plans.create' ? 'active' : '' }}"
                   id="plan_nav_create">
                    <i class="fas fa-plus-circle admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Nuevo Plan</span>
                        <small>Agregar plan de suscripción</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.suscripciones.index') }}"
                   class="admin-nav-link {{ $menu === 'suscripciones' ? 'active' : '' }}">
                    <i class="fas fa-credit-card admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Suscripciones</span>
                        <small>Ver y gestionar pagos</small>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    {{-- Reportes y Análisis --}}
    <div class="admin-nav-section">
        <div class="admin-nav-category">
            <i class="fas fa-chart-bar admin-nav-category-icon"></i>
            <span class="admin-nav-category-title">Reportes y Análisis</span>
            <i class="fas fa-chevron-down admin-nav-toggle"></i>
        </div>
        <ul class="admin-nav-items">
            <li class="admin-nav-item">
                <a href="#" class="admin-nav-link" data-demo="analytics">
                    <i class="fas fa-chart-line admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Análisis Avanzado</span>
                        <small>Métricas detalladas</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="#" class="admin-nav-link" data-demo="reports">
                    <i class="fas fa-file-chart-pie admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Reportes</span>
                        <small>Exportar datos</small>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    {{-- Sistema --}}
    <div class="admin-nav-section">
        <div class="admin-nav-category">
            <i class="fas fa-cogs admin-nav-category-icon"></i>
            <span class="admin-nav-category-title">Sistema</span>
            <i class="fas fa-chevron-down admin-nav-toggle"></i>
        </div>
        <ul class="admin-nav-items">
            <li class="admin-nav-item">
                <a href="#" class="admin-nav-link" data-demo="settings">
                    <i class="fas fa-sliders-h admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Configuración</span>
                        <small>Ajustes generales</small>
                    </div>
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="#" class="admin-nav-link" data-demo="maintenance">
                    <i class="fas fa-tools admin-nav-icon"></i>
                    <div class="admin-nav-content">
                        <span>Mantenimiento</span>
                        <small>Respaldos y logs</small>
                    </div>
                </a>
            </li>
        </ul>
    </div>

</nav>

{{-- Cerrar Sesión --}}
<div class="admin-sidebar-footer">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="admin-logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar Sesión</span>
        </button>
    </form>
</div>
