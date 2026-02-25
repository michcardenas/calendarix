@php use Illuminate\Support\Str; @endphp

<style>
    .sidebar-clx,
    .sidebar-clx a,
    .sidebar-clx a:hover,
    .sidebar-clx a:focus,
    .sidebar-clx a:visited,
    .sidebar-clx i,
    .sidebar-clx p,
    .sidebar-clx h2,
    .sidebar-clx nav {
        color: #ffffff !important;
        text-decoration: none !important;
    }
    .sidebar-clx a {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }
    .sidebar-clx a:hover {
        background-color: rgba(255,255,255,0.15) !important;
    }
    .sidebar-clx a.sidebar-active {
        background-color: rgba(255,255,255,0.22) !important;
        font-weight: 600 !important;
    }
    .sidebar-clx .sidebar-section-label {
        color: rgba(255,255,255,0.50) !important;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 600;
        padding: 0 0.75rem;
        margin-top: 1rem;
        margin-bottom: 0.25rem;
    }
    .sidebar-clx .sidebar-email {
        color: rgba(255,255,255,0.70) !important;
        font-size: 0.75rem;
    }
    .sidebar-clx .sidebar-exit {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .sidebar-clx .sidebar-exit:hover {
        background-color: #ffffff !important;
        color: #5a31d7 !important;
    }
    .sidebar-clx .sidebar-exit:hover i {
        color: #5a31d7 !important;
    }
</style>

<aside class="sidebar-clx" style="width: 14rem; min-height: 100vh; background-color: #5a31d7; padding: 1rem; flex-shrink: 0; box-shadow: 2px 0 6px rgba(0,0,0,0.1);">

    {{-- Encabezado --}}
    <div style="margin-bottom: 1.25rem; text-align: center;">
        <img src="{{ asset('images/azul.png') }}" alt="Calendarix Logo" style="width: 4rem; height: 4rem; margin: 0 auto 0.5rem;">
        <h2 style="font-size: 1rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <i class="fas fa-store" style="font-size: 0.875rem;"></i> {{ $empresa->neg_nombre_comercial }}
        </h2>
        <p class="sidebar-email" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $empresa->neg_email }}</p>
    </div>

    {{-- Navegación --}}
    <nav>
        <a href="{{ route('empresa.agenda', $empresa->id) }}"
           class="{{ $currentPage === 'agenda' ? 'sidebar-active' : '' }}">
            <i class="fas fa-calendar-alt" style="width: 1rem; font-size: 0.875rem;"></i> Agenda
        </a>

        <a href="{{ route('empresa.clientes', $empresa->id) }}"
           class="{{ $currentPage === 'clientes' ? 'sidebar-active' : '' }}">
            <i class="fas fa-users" style="width: 1rem; font-size: 0.875rem;"></i> Clientes
        </a>

        <a href="{{ route('empresa.trabajadores.index', $empresa->id) }}"
           class="{{ $currentPage === 'trabajadores' ? 'sidebar-active' : '' }}">
            <i class="fas fa-user-tie" style="width: 1rem; font-size: 0.875rem;"></i> Trabajadores
        </a>

        <a href="{{ route('negocios.show', ['slug' => $empresa->slug]) }}"
           target="_blank">
            <i class="fas fa-globe" style="width: 1rem; font-size: 0.875rem;"></i> Ver perfil público
        </a>

        {{-- Subgrupo catálogo --}}
        <div class="sidebar-section-label">Catálogo</div>

        <div style="margin-left: 0.75rem;">
            <a href="{{ route('empresa.catalogo.servicios', $empresa->id) }}"
               class="{{ $currentSubPage === 'servicios' ? 'sidebar-active' : '' }}">
                <i class="fas fa-cut" style="width: 1rem; font-size: 0.875rem;"></i> Servicios
            </a>
            <a href="{{ route('empresa.galeria.index', $empresa->id) }}"
               class="{{ $currentSubPage === 'galeria' ? 'sidebar-active' : '' }}">
                <i class="fas fa-images" style="width: 1rem; font-size: 0.875rem;"></i> Galería
            </a>
        </div>

        <div class="sidebar-section-label">Negocio</div>

        <div style="margin-left: 0.75rem;">
            <a href="{{ route('empresa.configuracion.negocio', $empresa->id) }}"
               class="{{ $currentSubPage === 'negocio' ? 'sidebar-active' : '' }}">
                <i class="fas fa-info-circle" style="width: 1rem; font-size: 0.875rem;"></i> Datos negocio
            </a>
            <a href="{{ route('empresa.configuracion.centros', $empresa->id) }}"
               class="{{ $currentSubPage === 'centros' ? 'sidebar-active' : '' }}">
                <i class="fas fa-store-alt" style="width: 1rem; font-size: 0.875rem;"></i> Centros
            </a>
            <a href="{{ route('empresa.configuracion.procedencia', $empresa->id) }}"
               class="{{ $currentSubPage === 'procedencia' ? 'sidebar-active' : '' }}">
                <i class="fas fa-map-marked-alt" style="width: 1rem; font-size: 0.875rem;"></i> Procedencia
            </a>
        </div>

        <div class="sidebar-section-label">Opciones</div>

        <div style="margin-left: 0.75rem;">
            <a href="{{ route('empresa.configuracion.citas', $empresa->id) }}"
               class="{{ $currentSubPage === 'citas' ? 'sidebar-active' : '' }}">
                <i class="fas fa-calendar-check" style="width: 1rem; font-size: 0.875rem;"></i> Citas
            </a>
            <a href="{{ route('empresa.resenas.index', $empresa->id) }}"
               class="{{ $currentSubPage === 'resenas' ? 'sidebar-active' : '' }}">
                <i class="fas fa-star" style="width: 1rem; font-size: 0.875rem;"></i> Reseñas
            </a>
        </div>
    </nav>

    {{-- Salir --}}
    <div style="margin-top: 1.5rem; padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.2);">
        <a href="{{ url('/dashboard') }}" class="sidebar-exit">
            <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i> Salir
        </a>
    </div>

</aside>
