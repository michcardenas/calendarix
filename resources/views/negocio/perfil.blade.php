@extends('layouts.app')

@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
<style>
    body::before,
    body::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
        pointer-events: none;
    }

    body::before {
        background:
            radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28), transparent 60%),
            radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25), transparent 60%),
            radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3), transparent 60%),
            radial-gradient(circle 130px at 70% 35%, rgba(90, 78, 187, 0.3), transparent 60%),
            radial-gradient(circle 180px at 85% 10%, rgba(126, 121, 201, 0.25), transparent 60%),
            radial-gradient(circle 100px at 20% 75%, rgba(90, 78, 187, 0.22), transparent 60%),
            radial-gradient(circle 120px at 45% 90%, rgba(126, 121, 201, 0.26), transparent 60%),
            radial-gradient(circle 140px at 80% 80%, rgba(90, 78, 187, 0.3), transparent 60%);
        animation: bubblesBefore 18s ease-in-out infinite;
    }

    body::after {
        background:
            radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2), transparent 60%),
            radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28), transparent 60%),
            radial-gradient(circle 120px at 55% 45%, rgba(126, 121, 201, 0.24), transparent 60%),
            radial-gradient(circle 140px at 75% 55%, rgba(90, 78, 187, 0.22), transparent 60%),
            radial-gradient(circle 160px at 90% 35%, rgba(126, 121, 201, 0.23), transparent 60%),
            radial-gradient(circle 100px at 10% 85%, rgba(90, 78, 187, 0.2), transparent 60%),
            radial-gradient(circle 150px at 40% 10%, rgba(126, 121, 201, 0.28), transparent 60%),
            radial-gradient(circle 130px at 60% 85%, rgba(90, 78, 187, 0.25), transparent 60%);
        animation: bubblesAfter 22s ease-in-out infinite reverse;
    }

    @keyframes bubblesBefore {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    @keyframes bubblesAfter {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(30px); }
    }
</style>

{{-- Botón carrito flotante --}}
<div class="fixed top-6 right-6 z-50">
    <button id="carritoButton" class="relative bg-[#4a5eaa] text-white px-4 py-2 rounded-full shadow-lg hover:bg-[#3d4e94] transition">
        🛒 Carrito
        <span id="carritoCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">0</span>
    </button>
</div>

{{-- Modal del carrito (puede moverse a partial luego) --}}
@include('empresa.partials.carrito-modal', ['empresa' => $negocio])


<div class="py-10 relative z-10">
    <div class="max-w-6xl mx-auto">

        {{-- Portada --}}
        <div class="relative w-full h-80 rounded-2xl overflow-hidden shadow-2xl mb-14 bg-[#4a5eaa]">
            @if($negocio->neg_portada)
                <img src="{{ $negocio->neg_portada }}" class="absolute inset-0 w-full h-full object-cover" alt="Portada">
            @endif
        </div>

        {{-- Encabezado --}}
        <div class="relative -mt-32 flex items-center gap-8 px-10 pb-10">
            <div class="shrink-0">
                <img src="{{ $negocio->neg_imagen ?? '/images/default-user.png' }}"
                     class="w-40 h-40 rounded-full border-4 border-white object-cover shadow-xl bg-white"
                     alt="Avatar">
            </div>
            <div>
                <h1 class="text-4xl font-extrabold text-[#4a5eaa]">{{ $negocio->neg_nombre_comercial }}</h1>
                <p class="text-lg text-[#3B4269B3]">{{ $negocio->neg_categoria }}</p>
            </div>
        </div>

        {{-- Secciones --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-10">
            <div class="space-y-6">
                {{-- Información --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">📄 Información del negocio</h3>
                    <ul class="text-base text-gray-700 space-y-2">
                        <li><strong>Email:</strong> {{ $negocio->neg_email }}</li>
                        <li><strong>Teléfono:</strong> {{ $negocio->neg_telefono }}</li>
                        <li><strong>Dirección:</strong> {{ $negocio->neg_direccion }}</li>
                    </ul>
                </div>

                {{-- Servicios --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">💼 Servicios</h3>
                    @if($negocio->servicios->count())
                        <ul class="list-disc pl-5 text-base text-gray-700 space-y-2">
                            @foreach($negocio->servicios as $servicio)
                                <li class="flex justify-between items-center">
                                    <span>{{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                    <button class="agregar-carrito bg-green-500 hover:bg-green-600 text-white text-sm px-2 py-1 rounded"
                                            data-tipo="servicio"
                                            data-id="{{ $servicio->id }}"
                                            data-nombre="{{ $servicio->nombre }}"
                                            data-precio="{{ $servicio->precio }}">
                                        +
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-base text-gray-500">Este negocio aún no tiene servicios registrados.</p>
                    @endif
                </div>

                {{-- Productos --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">🛒 Productos</h3>
                    @if($negocio->productos && $negocio->productos->count())
                        <ul class="divide-y divide-gray-200">
                            @foreach($negocio->productos as $producto)
                                <li class="py-3">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $producto->imagen ?? '/images/product-placeholder.png' }}"
                                             class="w-14 h-14 object-cover rounded-md border"
                                             alt="{{ $producto->nombre }}">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800">{{ $producto->nombre }}</h4>
                                            <p class="text-sm text-gray-600">{{ $producto->descripcion_breve }}</p>
                                            <div class="flex items-center justify-between mt-1">
                                                <p class="text-sm text-[#4a5eaa] font-semibold">
                                                    ${{ number_format($producto->precio_venta, 0, ',', '.') }}
                                                    @if($producto->activar_oferta && $producto->precio_promocional)
                                                        <span class="text-sm line-through text-gray-400 ml-2">
                                                            ${{ number_format($producto->precio_promocional, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </p>
                                                <button class="agregar-carrito bg-green-500 hover:bg-green-600 text-white text-xs px-2 py-1 rounded"
                                                        data-tipo="producto"
                                                        data-id="{{ $producto->id }}"
                                                        data-nombre="{{ $producto->nombre }}"
                                                        data-precio="{{ $producto->precio_venta }}">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-base text-gray-500">Este negocio aún no tiene productos registrados.</p>
                    @endif
                </div>
            </div>

            {{-- Horarios y Calendario --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Horarios --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">⏰ Horarios de Atención</h3>
                    <table class="w-full text-base">
                        <thead>
                            <tr class="text-gray-600 border-b">
                                <th class="py-2 text-left">Día</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($negocio->horarios as $h)
                                <tr class="border-b text-gray-700">
                                    <td class="py-2">
                                        {{ \Carbon\Carbon::create()->startOfWeek()->addDays($h->dia_semana - 1)->locale('es')->isoFormat('dddd') }}
                                    </td>
                                    <td>{{ $h->hora_inicio ?? '—' }}</td>
                                    <td>{{ $h->hora_fin ?? '—' }}</td>
                                    <td>
                                        {!! $h->activo ? '<span class="text-green-600 font-semibold">Sí</span>' : '<span class="text-red-600">No</span>' !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Calendario --}}
                @if($negocio->bloqueos->count())
                    <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                        <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">📅 Días Bloqueados</h3>
                        <div id="calendarioBloqueos" class="rounded overflow-hidden"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 🗓️ Calendario
    const calendarEl = document.getElementById('calendarioBloqueos');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 500,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: [
                @foreach($negocio->bloqueos as $bloqueo)
                {
                    title: 'Bloqueado',
                    start: '{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',
                    allDay: true,
                    color: '#dc2626'
                },
                @endforeach
            ]
        });
        calendar.render();
    }

    // 🛒 Carrito
    let carrito = JSON.parse(localStorage.getItem('carritoNegocio')) || [];
    const modal = document.getElementById('modalCarrito');
    const lista = document.getElementById('carritoItems');
    const total = document.getElementById('carritoTotal');
    const count = document.getElementById('carritoCount');
    const inputHidden = document.getElementById('carritoJsonInput');

    function guardarCarrito() {
        localStorage.setItem('carritoNegocio', JSON.stringify(carrito));
    }

    function actualizarCarrito() {
        lista.innerHTML = '';
        let suma = 0;

        if (carrito.length === 0) {
            lista.innerHTML = `<li class="text-gray-500 text-sm text-center py-4">Tu carrito está vacío.</li>`;
        }

        carrito.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            suma += subtotal;

            const li = document.createElement('li');
            li.className = 'py-2 flex justify-between items-center border-b border-gray-100';
            li.innerHTML = `
                <div>
                    <span class="font-medium">${item.nombre}</span>
                    <span class="ml-2 text-gray-500 text-sm">($${item.precio.toLocaleString()} x ${item.cantidad})</span><br>
                    <span class="text-xs text-gray-600">Subtotal: $${subtotal.toLocaleString()}</span>
                </div>
                <button data-index="${index}" class="eliminar-item text-red-500 hover:text-red-700 text-sm">
                    Quitar
                </button>
            `;
            lista.appendChild(li);
        });

        total.textContent = '$' + suma.toLocaleString();
        count.textContent = carrito.length;
        if (inputHidden) inputHidden.value = JSON.stringify(carrito);
        guardarCarrito();
    }

    // ➕ Agregar al carrito
    document.querySelectorAll('.agregar-carrito').forEach(btn => {
        btn.addEventListener('click', function () {
            const nuevoItem = {
                id: this.dataset.id,
                nombre: this.dataset.nombre,
                precio: parseFloat(this.dataset.precio),
                tipo: this.dataset.tipo,
                cantidad: parseInt(this.dataset.cantidad || '1')
            };

            // Si ya existe, suma la cantidad
            const existente = carrito.find(item => item.id === nuevoItem.id && item.tipo === nuevoItem.tipo);
            if (existente) {
                existente.cantidad += nuevoItem.cantidad;
            } else {
                carrito.push(nuevoItem);
            }

            actualizarCarrito();

            const toast = document.createElement('div');
            toast.textContent = `${nuevoItem.nombre} agregado al carrito`;
            toast.className = 'fixed bottom-6 right-6 bg-black/80 text-white text-sm px-4 py-2 rounded shadow-lg z-50 animate-bounce';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        });
    });

    // 🧹 Eliminar del carrito
    lista.addEventListener('click', function (e) {
        if (e.target.classList.contains('eliminar-item')) {
            const index = parseInt(e.target.dataset.index);
            carrito.splice(index, 1);
            actualizarCarrito();
        }
    });

    // 🪟 Abrir modal
    document.getElementById('carritoButton')?.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // ❌ Cerrar modal
    document.getElementById('cerrarModalCarrito')?.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // ✅ Enviar carrito al checkout
    const formCheckout = document.getElementById('formCheckout');
    if (formCheckout) {
        formCheckout.addEventListener('submit', function (e) {
            if (carrito.length === 0) {
                e.preventDefault();
                alert('Tu carrito está vacío.');
            } else {
                inputHidden.value = JSON.stringify(carrito);
                localStorage.removeItem('carritoNegocio');
            }
        });
    }

    // 🔄 Cargar carrito al iniciar
    actualizarCarrito();
});
</script>
@endpush
