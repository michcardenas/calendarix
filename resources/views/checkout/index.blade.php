@extends('layouts.app')

@section('content')

<style>
    body::before,
    body::after {
        content: '';
        position: fixed;
        inset: 0;
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
        pointer-events: none;
    }

    body::before {
        background:
            radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
            radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25) 0%, transparent 60%),
            radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3) 0%, transparent 60%);
        animation: bubblesBefore 18s ease-in-out infinite;
    }

    body::after {
        background:
            radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2) 0%, transparent 60%),
            radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28) 0%, transparent 60%);
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

<div class="flex items-center justify-center min-h-screen px-4 relative z-10">
    <div class="w-full max-w-7xl bg-white/80 backdrop-blur-md rounded-xl px-6 py-10 shadow-lg">
        <h2 class="text-xl font-bold text-[#6274c9] mb-6">🛍 Checkout de {{ $empresa->neg_nombre_comercial }}</h2>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Productos y Servicios --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Productos --}}
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-[#4B5563]">📦 Productos</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($productos as $producto)
                        <div class="bg-white border border-gray-100 rounded-xl p-3 shadow-sm hover:shadow-md transition text-sm">
                            @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-full h-24 object-cover rounded mb-1">
                            @endif
                            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $producto->nombre }}</h4>
                            <p class="text-xs text-gray-500 truncate">{{ $producto->descripcion_breve }}</p>
                            <p class="text-[#6274c9] text-sm font-semibold mt-1">$ {{ number_format($producto->precio_venta, 0, ',', '.') }}</p>

                            <form action="{{ route('checkout.add', $empresa->id) }}" method="POST" class="mt-2 flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                <input type="number" name="cantidad" value="1" min="1" class="w-14 text-xs border rounded px-1">
                                <button type="submit" class="bg-[#6274c9] hover:bg-[#4e5bb0] text-white text-xs px-3 py-1 rounded">Agregar</button>
                            </form>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">No hay productos disponibles.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Servicios --}}
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-[#4B5563]">🧰 Servicios</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($servicios as $servicio)
                        <div class="bg-white border border-gray-100 rounded-xl p-3 shadow-sm hover:shadow-md transition text-sm">
                            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $servicio->nombre }}</h4>
                            <p class="text-xs text-gray-500 truncate">{{ $servicio->descripcion }}</p>
                            <p class="text-[#6274c9] text-sm font-semibold mt-1">$ {{ number_format($servicio->precio, 0, ',', '.') }}</p>

                            <form action="{{ route('checkout.add', $empresa->id) }}" method="POST" class="mt-2 flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="servicio_id" value="{{ $servicio->id }}">
                                <input type="number" name="cantidad" value="1" min="1" class="w-14 text-xs border rounded px-1">
                                <button type="submit" class="bg-[#6274c9] hover:bg-[#4e5bb0] text-white text-xs px-3 py-1 rounded">Agregar</button>
                            </form>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">No hay servicios disponibles.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Carrito --}}
            <div>
                <h3 class="text-lg font-semibold mb-3 text-[#4B5563]">🧺 Carrito</h3>
                @if(count($carrito))
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow space-y-3 text-sm">
                    @php $total = 0; @endphp
                    @foreach($carrito as $index => $item)
                    @php
                        $precio = $item['precio_unitario'] ?? ($item['precio'] ?? 0);
                        $cantidad = $item['cantidad'] ?? 1;
                        $subtotal = $precio * $cantidad;
                        $total += $subtotal;
                    @endphp
                    <div class="border-b pb-2 relative pr-6">
                        <p class="font-semibold text-gray-800 truncate">
                            {{ $item['tipo'] === 'producto' ? '🛒' : '🛠️' }} {{ $item['nombre'] ?? 'Sin nombre' }}
                        </p>
                        <p class="text-gray-500">Cantidad: {{ $cantidad }}</p>
                        <p class="text-gray-500">Subtotal: ${{ number_format($subtotal, 0, ',', '.') }}</p>

                        <form action="{{ route('checkout.remove', $empresa->id) }}" method="POST" class="absolute top-0 right-0">
                            @csrf
                            <input type="hidden" name="indice" value="{{ $index }}">
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm" title="Quitar">✖</button>
                        </form>
                    </div>
                    @endforeach

                    <div class="pt-2 font-semibold text-gray-800 border-t">
                        Total: ${{ number_format($total, 0, ',', '.') }}
                    </div>

                    <form action="{{ route('checkout.finalizar', $empresa->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full bg-[#6274c9] hover:bg-[#4e5bb0] text-white py-2 rounded text-sm shadow">
                            ✅ Finalizar pedido
                        </button>
                    </form>
                </div>
                @else
                <p class="text-gray-500 text-sm">Tu carrito está vacío.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
