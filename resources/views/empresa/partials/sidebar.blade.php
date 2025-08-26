<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

@php use Illuminate\Support\Str; @endphp

<body>
<div class="flex min-h-screen">

    <aside class="w-56 bg-[#4a5eaa] text-[#f6f5f7] p-4 shadow-md">

        {{-- Encabezado --}}
        <div class="mb-5 text-center">
            <img src="{{ asset('images/calendarix.png') }}" alt="Calendarix Logo" class="w-16 h-16 mx-auto mb-2">
            <h2 class="text-base font-bold flex items-center justify-center gap-2 leading-tight">
                <i class="fas fa-store text-sm"></i> {{ $empresa->neg_nombre_comercial }}
            </h2>
            <p class="text-xs text-[#f6f5f7] truncate">{{ $empresa->neg_email }}</p>
        </div>

        {{-- Navegación --}}
        <nav class="space-y-1 text-sm">
            <a href="{{ route('empresa.agenda', $empresa->id) }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-[#6C88C4]/20 transition {{ $currentPage === 'agenda' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                <i class="fas fa-calendar-alt w-4 text-sm"></i> Agenda
            </a>

            <a href="{{ route('empresa.clientes', $empresa->id) }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-[#6C88C4]/20 transition {{ $currentPage === 'clientes' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                <i class="fas fa-users w-4 text-sm"></i> Clientes
            </a>

            {{-- 🔧 NUEVO: Trabajadores --}}
            <a href="{{ route('empresa.trabajadores.index', $empresa->id) }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-[#6C88C4]/20 transition {{ $currentPage === 'trabajadores' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                <i class="fas fa-user-tie w-4 text-sm"></i> Trabajadores
            </a>

            <a href="{{ route('negocios.show', ['id' => $empresa->id, 'slug' => Str::slug($empresa->neg_nombre)]) }}"
               target="_blank"
               class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition">
                <i class="fas fa-globe w-4 text-sm"></i> Ver perfil público
            </a>

            {{-- Subgrupo catálogo --}}
            <div class="mt-3 text-xs text-[#3B4269] uppercase tracking-wider px-3">Catálogo</div>

            <div class="space-y-1 ml-3 mt-1">
                <a href="{{ route('empresa.catalogo.servicios', $empresa->id) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'servicios' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-cut w-4 text-sm"></i> Servicios
                </a>
                <a href="{{ route('producto.crear', ['id' => $empresa->id]) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'productos_crear' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-plus w-4 text-sm"></i> Crear producto
                </a>
                <a href="{{ route('producto.panel', ['id' => $empresa->id]) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'productos_ver' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-eye w-4 text-sm"></i> Ver productos
                </a>
                <a href="{{ route('checkout.index', ['id' => $empresa->id]) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'checkout' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-shopping-cart w-4 text-sm"></i> Checkout
                </a>
                <a href="{{ route('checkout.pedidos', ['id' => $empresa->id]) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'pedidos' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-clipboard-list w-4 text-sm"></i> Pedidos
                </a>
            </div>

            <div class="mt-3 text-xs text-[#3B4269] uppercase tracking-wider px-3">Negocio</div>

            <div class="space-y-1 ml-3 mt-1">
                <a href="{{ route('empresa.configuracion.negocio', $empresa->id) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'negocio' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-info-circle w-4 text-sm"></i> Datos negocio
                </a>
                <a href="{{ route('empresa.configuracion.centros', $empresa->id) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'centros' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-store-alt w-4 text-sm"></i> Centros
                </a>
                <a href="{{ route('empresa.configuracion.procedencia', $empresa->id) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'procedencia' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-map-marked-alt w-4 text-sm"></i> Procedencia
                </a>
            </div>

            <div class="mt-3 text-xs text-[#3B4269] uppercase tracking-wider px-3">Opciones</div>

            <div class="space-y-1 ml-3 mt-1">
                <a href="{{ route('empresa.configuracion.citas', $empresa->id) }}"
                   class="flex items-center gap-2 px-3 py-1 rounded hover:bg-[#6C88C4]/20 transition {{ $currentSubPage === 'citas' ? 'bg-[#6C88C4]/30 font-semibold' : '' }}">
                    <i class="fas fa-calendar-check w-4 text-sm"></i> Citas
                </a>
            </div>
        </nav>

        {{-- Salir --}}
        <div class="mt-6 pt-3 border-t border-[#f6f5f7]/10">
            <a href="{{ url('/dashboard') }}"
               class="flex items-center justify-center w-full px-3 py-2 border border-[#f6f5f7] text-[#f6f5f7] text-sm rounded hover:bg-[#f6f5f7] hover:text-[#4a5eaa] transition">
                <i class="fas fa-sign-out-alt mr-2"></i> Salir
            </a>
        </div>

    </aside>

</div>
</body>
</html>
