<aside class="sidebar" style="z-index: 10; width: 240px; background-color: #3b1d6b; color: white; height: 100vh; padding: 1rem; position: fixed; top: 0; left: 0;">

    {{-- Título y correo --}}
    <h2 style="font-size: 1.2rem; margin-bottom: 0.5rem;">
        <i class="fas fa-store"></i> {{ $empresa->neg_nombre_comercial }}
    </h2>
    <p style="font-size: 0.85rem; color: #ccc; margin-bottom: 2rem;">{{ $empresa->neg_email }}</p>

    {{-- Navegación principal --}}
    <nav class="sidebar-nav d-flex flex-column gap-2">
        <a href="{{ route('empresa.agenda', $empresa->id) }}"
           class="nav-link {{ $currentPage === 'agenda' ? 'active' : '' }}">
            <i class="fas fa-calendar-alt me-2"></i> Agenda
        </a>

        <a href="{{ route('empresa.clientes', $empresa->id) }}"
           class="nav-link {{ $currentPage === 'clientes' ? 'active' : '' }}">
            <i class="fas fa-users me-2"></i> Clientes
        </a>

        <a href="{{ route('empresa.configuracion', $empresa->id) }}"
           class="nav-link {{ $currentPage === 'configuracion' ? 'active' : '' }}">
            <i class="fas fa-cog me-2"></i> Configuración
        </a>

            {{-- Grupo: Catálogo --}}
        <div class="submenu-item {{ in_array($currentPage, ['catalogo']) ? 'active' : '' }}">
            <i class="fas fa-box-open me-2"></i> Catálogo

            <div class="submenu ps-3 mt-2">
                <a href="{{ route('catalogo.servicios') }}"
                    class="{{ $currentSubPage === 'servicios' ? 'submenu-item active' : 'submenu-item' }}">
                    <i class="fas fa-cut me-2"></i> Menú de servicios
                </a>

                <div class="submenu-item {{ in_array($currentSubPage, ['productos_crear', 'productos_ver']) ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i> Productos

                    <div class="submenu ps-3 mt-2">
                        <a href="{{ route('producto.crear') }}"
                        class="{{ $currentSubPage === 'productos_crear' ? 'submenu-item active' : 'submenu-item' }}">
                            <i class="fas fa-plus me-2"></i> Crear producto
                        </a>

                        <a href="{{ route('producto.panel') }}"
                        class="{{ $currentSubPage === 'productos_ver' ? 'submenu-item active' : 'submenu-item' }}">
                            <i class="fas fa-eye me-2"></i> Ver productos
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Submenú de configuración --}}
        @if($currentPage === 'configuracion')
        <div class="submenu mt-2">

            {{-- Subgrupo: Configuración del negocio --}}
            <div class="submenu-item {{ in_array($currentSubPage, ['negocio', 'centros', 'procedencia']) ? 'active' : '' }}">
                <i class="fas fa-building me-2"></i> Configuración del negocio

                <div class="submenu ps-3 mt-2">
                    <a href="{{ route('empresa.configuracion.negocio', $empresa->id) }}"
                       class="{{ $currentSubPage === 'negocio' ? 'submenu-item active' : 'submenu-item' }}">
                        <i class="fas fa-info-circle me-2"></i> Datos del negocio
                    </a>

                    <a href="{{ route('empresa.configuracion.centros', $empresa->id) }}"
                       class="{{ $currentSubPage === 'centros' ? 'submenu-item active' : 'submenu-item' }}">
                        <i class="fas fa-store-alt me-2"></i> Centros
                    </a>

                    <a href="{{ route('empresa.configuracion.procedencia', $empresa->id) }}"
                       class="{{ $currentSubPage === 'procedencia' ? 'submenu-item active' : 'submenu-item' }}">
                        <i class="fas fa-map-marked-alt me-2"></i> Procedencia
                    </a>
                </div>
            </div>

            {{-- Otras opciones de configuración --}}
            <a href="{{ route('empresa.configuracion.citas', $empresa->id) }}"
               class="{{ $currentSubPage === 'citas' ? 'submenu-item active' : 'submenu-item' }}">
                <i class="fas fa-calendar-check me-2"></i> Gestión de citas
            </a>
            <a href="{{ route('empresa.configuracion.ventas', $empresa->id) }}"
               class="{{ $currentSubPage === 'ventas' ? 'submenu-item active' : 'submenu-item' }}">
                <i class="fas fa-tags me-2"></i> Ventas
            </a>
            <a href="{{ route('empresa.configuracion.facturacion', $empresa->id) }}"
               class="{{ $currentSubPage === 'facturacion' ? 'submenu-item active' : 'submenu-item' }}">
                <i class="fas fa-file-invoice me-2"></i> Facturación
            </a>
            <a href="{{ route('empresa.configuracion.equipo', $empresa->id) }}"
               class="{{ $currentSubPage === 'equipo' ? 'submenu-item active' : 'submenu-item' }}">
                <i class="fas fa-users-cog me-2"></i> Equipo
            </a>
            <a href="{{ route('empresa.configuracion.formularios', $empresa->id) }}"
               class="{{ $currentSubPage === 'formularios' ? 'submenu-item active' : 'submenu-item' }}">
                <i class="fas fa-clipboard-list me-2"></i> Formularios
            </a>
            <a href="{{ route('empresa.configuracion.pagos', $empresa->id) }}"
               class="{{ $currentSubPage === 'pagos' ? 'submenu-item active' : 'submenu-item' }}">
                <i class="fas fa-credit-card me-2"></i> Pagos
            </a>
        </div>
        @endif
    </nav>

    {{-- Botón salir --}}
    <a href="{{ url('/dashboard') }}" class="btn btn-outline-light w-100 mt-4">
        <i class="fas fa-sign-out-alt me-2"></i> Salir
    </a>
</aside>


{{-- Estilos necesarios --}}
<style>
.sidebar a.nav-link,
.sidebar a.submenu-item,
.quick-btn-sidebar {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.sidebar a.nav-link:hover,
.sidebar a.submenu-item:hover,
.quick-btn-sidebar:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar a.active,
.submenu-item.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: bold;
}

.submenu {
    border-left: 2px solid rgba(255, 255, 255, 0.2);
    margin-left: 1rem;
    padding-left: 0.5rem;
}
</style>
