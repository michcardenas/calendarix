<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Empresa</title>

    {{-- Vite: Tailwind + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<aside class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-[#3b1d6b] to-[#2a1250] text-white z-10 p-5 shadow-2xl rounded-r-3xl">

    {{-- Encabezado --}}
    <div class="mb-6 border-b border-white/10 pb-4">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-store"></i> {{ $empresa->neg_nombre_comercial }}
        </h2>
        <p class="text-sm text-purple-200 mt-1">{{ $empresa->neg_email }}</p>
    </div>

    {{-- Navegación --}}
    <nav class="flex flex-col gap-2 text-sm">
        <a href="{{ route('empresa.agenda', $empresa->id) }}"
           class="flex items-center px-4 py-2 rounded-lg transition hover:bg-white/10 {{ $currentPage === 'agenda' ? 'bg-white/20 font-semibold' : '' }}">
            <i class="fas fa-calendar-alt mr-2"></i> Agenda
        </a>

        <a href="{{ route('empresa.clientes', $empresa->id) }}"
           class="flex items-center px-4 py-2 rounded-lg transition hover:bg-white/10 {{ $currentPage === 'clientes' ? 'bg-white/20 font-semibold' : '' }}">
            <i class="fas fa-users mr-2"></i> Clientes
        </a>

        <a href="{{ route('empresa.configuracion', $empresa->id) }}"
           class="flex items-center px-4 py-2 rounded-lg transition hover:bg-white/10 {{ $currentPage === 'configuracion' ? 'bg-white/20 font-semibold' : '' }}">
            <i class="fas fa-cog mr-2"></i> Configuración
        </a>

        {{-- Separador --}}
        <div class="mt-4 mb-2 text-xs text-purple-300 uppercase tracking-wide">Catálogo</div>

        {{-- Catálogo --}}
        <div class="{{ in_array($currentPage, ['catalogo']) ? 'bg-white/10 rounded-lg' : '' }}">
            <div class="flex items-center px-4 py-2 font-medium">
                <i class="fas fa-box-open mr-2"></i> Catálogo
            </div>

            <div class="ml-6 mt-2 space-y-1 border-l border-white/10 pl-3">
                <a href="{{ route('catalogo.servicios') }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'servicios' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-cut mr-2"></i> Menú de servicios
                </a>

                <div class="{{ in_array($currentSubPage, ['productos_crear', 'productos_ver']) ? 'bg-white/10 rounded-lg' : '' }}">
                    <div class="flex items-center px-3 py-1.5">
                        <i class="fas fa-box mr-2"></i> Productos
                    </div>
                    <div class="ml-5 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('producto.crear') }}"
                           class="flex items-center px-2 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'productos_crear' ? 'bg-white/20 font-semibold' : '' }}">
                            <i class="fas fa-plus mr-2"></i> Crear producto
                        </a>
                        <a href="{{ route('producto.panel') }}"
                           class="flex items-center px-2 py-1 rounded hover:bg-white/10 transition {{ $currentSubPage === 'productos_ver' ? 'bg-white/20 font-semibold' : '' }}">
                            <i class="fas fa-eye mr-2"></i> Ver productos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Configuración avanzada --}}
        @if($currentPage === 'configuracion')
        <div class="mt-5 border-t border-white/10 pt-4 space-y-1 text-sm">
            <div class="text-xs text-purple-300 uppercase tracking-wide">Negocio</div>

            <div class="ml-2 space-y-1 border-l border-white/10 pl-3">
                <a href="{{ route('empresa.configuracion.negocio', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'negocio' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-info-circle mr-2"></i> Datos del negocio
                </a>
                <a href="{{ route('empresa.configuracion.centros', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'centros' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-store-alt mr-2"></i> Centros
                </a>
                <a href="{{ route('empresa.configuracion.procedencia', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'procedencia' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-map-marked-alt mr-2"></i> Procedencia
                </a>
            </div>

            <div class="text-xs text-purple-300 uppercase tracking-wide mt-3">Opciones</div>

            <div class="ml-2 space-y-1 border-l border-white/10 pl-3">
                <a href="{{ route('empresa.configuracion.citas', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'citas' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-calendar-check mr-2"></i> Gestión de citas
                </a>
                <a href="{{ route('empresa.configuracion.ventas', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'ventas' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-tags mr-2"></i> Ventas
                </a>
                <a href="{{ route('empresa.configuracion.facturacion', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'facturacion' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-file-invoice mr-2"></i> Facturación
                </a>
                <a href="{{ route('empresa.configuracion.equipo', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'equipo' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-users-cog mr-2"></i> Equipo
                </a>
                <a href="{{ route('empresa.configuracion.formularios', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'formularios' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-clipboard-list mr-2"></i> Formularios
                </a>
                <a href="{{ route('empresa.configuracion.pagos', $empresa->id) }}"
                   class="flex items-center px-3 py-1.5 rounded hover:bg-white/10 transition {{ $currentSubPage === 'pagos' ? 'bg-white/20 font-semibold' : '' }}">
                    <i class="fas fa-credit-card mr-2"></i> Pagos
                </a>
            </div>
        </div>
        @endif
    </nav>

    {{-- Botón salir --}}
    <div class="mt-8 border-t border-white/10 pt-4">
        <a href="{{ url('/dashboard') }}"
           class="w-full block text-center px-4 py-2 rounded-lg bg-white text-[#3b1d6b] hover:bg-purple-100 font-semibold transition">
            <i class="fas fa-sign-out-alt mr-2"></i> Salir
        </a>
        <p class="text-xs text-purple-300 mt-4 text-center">Compilado con <span class="text-white font-bold">Vite</span></p>
    </div>
</aside>

</body>
</html>
