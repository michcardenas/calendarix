@php use Illuminate\Support\Str; @endphp

<style>
    .sidebar-clx {
        width: 260px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        box-shadow: 0 10px 15px -3px rgba(90, 49, 215, 0.15);
        padding: 2rem 0;
        position: relative;
        border-right: 1px solid rgba(90, 49, 215, 0.08);
        min-height: 100vh;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    /* Encabezado */
    .sidebar-clx .sidebar-header {
        padding: 0 1.5rem 1.5rem;
        border-bottom: 1px solid rgba(90, 49, 215, 0.1);
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .sidebar-clx .sidebar-logo-img {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 0.75rem;
        display: block;
        border-radius: 50%;
        object-fit: cover;
    }
    .sidebar-clx .sidebar-biz-name {
        font-size: 1rem;
        font-weight: 700;
        color: #2d2d46;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-bottom: 0.25rem;
    }
    .sidebar-clx .sidebar-biz-name i {
        color: #5a31d7;
        font-size: 0.875rem;
    }
    .sidebar-clx .sidebar-email {
        color: #9c9cb9;
        font-size: 0.75rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Links de navegación */
    .sidebar-clx a {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 1.5rem;
        color: #374151;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        position: relative;
        border-radius: 0;
    }
    .sidebar-clx a:hover {
        background: rgba(90, 49, 215, 0.05);
        color: #5a31d7;
        padding-left: 1.75rem;
    }
    .sidebar-clx a.sidebar-active {
        background: rgba(90, 49, 215, 0.1);
        color: #5a31d7;
        font-weight: 600;
    }
    .sidebar-clx a.sidebar-active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #5a31d7, #7b5ce0);
        border-radius: 0 4px 4px 0;
    }
    .sidebar-clx a i.sidebar-icon {
        width: 1.1rem;
        font-size: 0.875rem;
        color: #9c9cb9;
        text-align: center;
    }
    .sidebar-clx a.sidebar-active i.sidebar-icon,
    .sidebar-clx a:hover i.sidebar-icon {
        color: #5a31d7;
    }

    /* Labels de sección */
    .sidebar-clx .sidebar-section-label {
        color: #9c9cb9;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 600;
        padding: 0 1.5rem;
        margin-top: 1.25rem;
        margin-bottom: 0.35rem;
    }

    /* Sub-items indentados */
    .sidebar-clx .sidebar-sub-group {
        margin-left: 0.75rem;
    }

    /* Botón salir */
    .sidebar-clx .sidebar-exit-wrap {
        margin-top: auto;
        padding: 1rem 1.5rem 0;
        border-top: 1px solid rgba(90, 49, 215, 0.08);
    }
    .sidebar-clx a.sidebar-exit {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid rgba(90, 49, 215, 0.2);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        transition: all 0.2s;
    }
    .sidebar-clx a.sidebar-exit:hover {
        background-color: #5a31d7;
        color: #ffffff;
        border-color: #5a31d7;
    }
    .sidebar-clx a.sidebar-exit:hover i {
        color: #ffffff;
    }
</style>

<aside class="sidebar-clx">

    {{-- Encabezado --}}
    <div class="sidebar-header">
        @if($empresa->neg_imagen)
            @php
                $logoSrc = Str::startsWith($empresa->neg_imagen, ['http://', 'https://'])
                    ? $empresa->neg_imagen
                    : (Str::startsWith($empresa->neg_imagen, '/')
                        ? $empresa->neg_imagen
                        : asset('storage/' . $empresa->neg_imagen));
            @endphp
            <img src="{{ $logoSrc }}" alt="{{ $empresa->neg_nombre_comercial }}" class="sidebar-logo-img">
        @else
            <img src="{{ asset('images/morado.png') }}" alt="Calendarix Logo" class="sidebar-logo-img" style="border-radius: 0;">
        @endif
        <div class="sidebar-biz-name">
            <i class="fas fa-store"></i> {{ $empresa->neg_nombre_comercial }}
        </div>
        <p class="sidebar-email">{{ $empresa->neg_email }}</p>
    </div>

    {{-- Navegación --}}
    <nav>
        <a href="{{ route('empresa.agenda', $empresa->id) }}"
           class="{{ $currentPage === 'agenda' ? 'sidebar-active' : '' }}">
            <i class="fas fa-calendar-alt sidebar-icon"></i> Agenda
        </a>

        <a href="{{ route('empresa.clientes', $empresa->id) }}"
           class="{{ $currentPage === 'clientes' ? 'sidebar-active' : '' }}">
            <i class="fas fa-users sidebar-icon"></i> Clientes
        </a>

        <a href="{{ route('empresa.trabajadores.index', $empresa->id) }}"
           class="{{ $currentPage === 'trabajadores' ? 'sidebar-active' : '' }}">
            <i class="fas fa-user-tie sidebar-icon"></i> Trabajadores
        </a>

        <a href="{{ route('negocios.show', ['slug' => $empresa->slug]) }}"
           target="_blank">
            <i class="fas fa-globe sidebar-icon"></i> Ver perfil público
        </a>

        {{-- Catálogo --}}
        <div class="sidebar-section-label">Catálogo</div>
        <div class="sidebar-sub-group">
            <a href="{{ route('empresa.catalogo.servicios', $empresa->id) }}"
               class="{{ $currentSubPage === 'servicios' ? 'sidebar-active' : '' }}">
                <i class="fas fa-cut sidebar-icon"></i> Servicios
            </a>
            <a href="{{ route('empresa.galeria.index', $empresa->id) }}"
               class="{{ $currentSubPage === 'galeria' ? 'sidebar-active' : '' }}">
                <i class="fas fa-images sidebar-icon"></i> Galería
            </a>
        </div>

        {{-- Negocio --}}
        <div class="sidebar-section-label">Negocio</div>
        <div class="sidebar-sub-group">
            <a href="{{ route('empresa.configuracion.negocio', $empresa->id) }}"
               class="{{ $currentSubPage === 'negocio' ? 'sidebar-active' : '' }}">
                <i class="fas fa-info-circle sidebar-icon"></i> Datos negocio
            </a>
        </div>

        {{-- Opciones --}}
        <div class="sidebar-section-label">Opciones</div>
        <div class="sidebar-sub-group">
            <a href="{{ route('empresa.configuracion.citas', $empresa->id) }}"
               class="{{ $currentSubPage === 'citas' ? 'sidebar-active' : '' }}">
                <i class="fas fa-calendar-check sidebar-icon"></i> Citas
            </a>
            <a href="{{ route('empresa.resenas.index', $empresa->id) }}"
               class="{{ $currentSubPage === 'resenas' ? 'sidebar-active' : '' }}">
                <i class="fas fa-star sidebar-icon"></i> Reseñas
            </a>
        </div>
    </nav>

    {{-- Salir --}}
    <div class="sidebar-exit-wrap">
        <a href="{{ url('/dashboard') }}" class="sidebar-exit">
            <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i> Salir
        </a>
    </div>

</aside>
