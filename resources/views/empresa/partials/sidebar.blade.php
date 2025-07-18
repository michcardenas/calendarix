<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<div class="flex min-h-screen">

    {{-- Sidebar Compacto --}}
    <aside class="w-56 bg-[#3b1d6b] text-white p-4 shadow-md">

        {{-- Encabezado --}}
        <div class="mb-5">
            <h2 class="text-base font-bold flex items-center gap-2 leading-tight">
                <i class="fas fa-store text-sm"></i> {{ $empresa->neg_nombre_comercial }}
            </h2>
            <p class="text-xs text-purple-200 truncate">{{ $empresa->neg_email }}</p>
        </div>

        {{-- Navegación --}}
        <nav class="space-y-1 text-sm">

            <a href="{{ route('empresa.agenda', $empresa->id) }}"
                class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentPage === 'agenda' ? 'bg-white/20 font-semibold' : '' }}">
                <i class="fas fa-calendar-alt w-4 text-sm"></i> Agenda
            </a>

            <a href="{{ route('empresa.clientes.index', $empresa->id) }}"
                class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentPage === 'clientes' ? 'bg-white/20 font-semibold' : '' }}">
                <i class="fas fa-users w-4 text-sm"></i> Clientes
            </a>

            <a href="{{ route('empresa.configuracion', $empresa->id) }}"
                class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentPage === 'configuracion' ? 'bg-white/20 font-semibold' : '' }}">
                <i class="fas fa-cog w-4 text-sm"></i> Configuración
            </a>

            {{-- Subgrupo catálogo --}}
            <div class="mt-3 text-xs text-purple-300 uppercase tracking-wider px-3">Catálogo</div>

            <div class="space-y-1 ml-3 mt-1">
                <a href="{{ route('catalogo.servicios') }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'servicios' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-cut w-4 text-sm"></i> Servicios
                </a>

                <a href="{{ route('producto.crear') }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'productos_crear' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-plus w-4 text-sm"></i> Crear producto
                </a>

                <a href="{{ route('producto.panel') }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'productos_ver' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-eye w-4 text-sm"></i> Ver productos
                </a>
            </div>
            @php use Illuminate\Support\Str; @endphp

            @if($currentPage === 'configuracion')
            <div class="mt-3 text-xs text-purple-300 uppercase tracking-wider px-3">Negocio</div>

            <div class="space-y-1 ml-3 mt-1">
                <a href="{{ route('empresa.configuracion.negocio', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'negocio' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-info-circle w-4 text-sm"></i> Datos negocio
                </a>
                <a href="{{ route('empresa.configuracion.centros', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'centros' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-store-alt w-4 text-sm"></i> Centros
                </a>
                <a href="{{ route('empresa.configuracion.procedencia', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'procedencia' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-map-marked-alt w-4 text-sm"></i> Procedencia
                </a>
                <a href="{{ route('negocios.show', ['id' => $empresa->id, 'slug' => Str::slug($empresa->neg_nombre)]) }}"
                    target="_blank"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition">
                    <i class="fas fa-globe w-4 text-sm"></i> Ver perfil público
                </a>
            </div>


            <div class="mt-3 text-xs text-purple-300 uppercase tracking-wider px-3">Opciones</div>

            <div class="space-y-1 ml-3 mt-1">
                <a href="{{ route('empresa.configuracion.citas', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'citas' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-calendar-check w-4 text-sm"></i> Citas
                </a>
                <a href="{{ route('empresa.configuracion.ventas', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'ventas' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-tags w-4 text-sm"></i> Ventas
                </a>
                <a href="{{ route('empresa.configuracion.facturacion', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'facturacion' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-file-invoice w-4 text-sm"></i> Facturación
                </a>
                <a href="{{ route('empresa.configuracion.equipo', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'equipo' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-users-cog w-4 text-sm"></i> Equipo
                </a>
                <a href="{{ route('empresa.configuracion.formularios', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'formularios' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-clipboard-list w-4 text-sm"></i> Formularios
                </a>
                <a href="{{ route('empresa.configuracion.pagos', $empresa->id) }}"
                    class="flex items-center gap-2 px-3 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'pagos' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-credit-card w-4 text-sm"></i> Pagos
                </a>
            </div>
            @endif

        </nav>

        {{-- Salir --}}
        <div class="mt-6 pt-3 border-t border-white/10">
            <a href="{{ url('/dashboard') }}"
                class="flex items-center justify-center w-full px-3 py-2 border border-white text-white text-sm rounded hover:bg-white hover:text-[#3b1d6b] transition">
                <i class="fas fa-sign-out-alt mr-2"></i> Salir
            </a>
        </div>

    </aside>

</div>

</body>

</html>