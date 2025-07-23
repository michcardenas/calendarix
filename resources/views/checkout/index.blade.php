@extends('layouts.app')

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
    radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
    radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25) 0%, transparent 60%),
    radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3) 0%, transparent 60%),
    radial-gradient(circle 130px at 70% 35%, rgba(90, 78, 187, 0.3) 0%, transparent 60%),
    radial-gradient(circle 180px at 85% 10%, rgba(126, 121, 201, 0.25) 0%, transparent 60%),
    radial-gradient(circle 100px at 20% 75%, rgba(90, 78, 187, 0.22) 0%, transparent 60%),
    radial-gradient(circle 120px at 45% 90%, rgba(126, 121, 201, 0.26) 0%, transparent 60%),
    radial-gradient(circle 140px at 80% 80%, rgba(90, 78, 187, 0.3) 0%, transparent 60%);
  animation: bubblesBefore 18s ease-in-out infinite;
}

body::after {
  background:
    radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2) 0%, transparent 60%),
    radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28) 0%, transparent 60%),
    radial-gradient(circle 120px at 55% 45%, rgba(126, 121, 201, 0.24) 0%, transparent 60%),
    radial-gradient(circle 140px at 75% 55%, rgba(90, 78, 187, 0.22) 0%, transparent 60%),
    radial-gradient(circle 160px at 90% 35%, rgba(126, 121, 201, 0.23) 0%, transparent 60%),
    radial-gradient(circle 100px at 10% 85%, rgba(90, 78, 187, 0.2) 0%, transparent 60%),
    radial-gradient(circle 150px at 40% 10%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
    radial-gradient(circle 130px at 60% 85%, rgba(90, 78, 187, 0.25) 0%, transparent 60%);
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

{{-- 🧭 Contenedor centrado --}}
<div class="flex items-center justify-center min-h-screen px-4 relative z-10">
  <div class="w-full max-w-7xl bg-[#f6f5f7] rounded-xl px-6 py-10 shadow-lg">
      <h2 class="text-2xl font-bold text-[#6274c9] mb-6">🛍 Checkout de {{ $empresa->neg_nombre_comercial }}</h2>

      @if(session('success'))
          <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
              {{ session('success') }}
          </div>
      @endif

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          {{-- 🛒 Catálogo --}}
          <div class="lg:col-span-2">
              <h3 class="text-xl font-semibold mb-4 text-[#4B5563]">📦 Productos disponibles</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  @forelse($productos as $producto)
                      <div class="bg-white rounded-xl shadow p-4 border border-gray-100 hover:shadow-md transition">
                          <h4 class="text-lg font-semibold text-[#374151] mb-1">{{ $producto->nombre }}</h4>
                          <p class="text-sm text-[#6B7280] mb-2">{{ $producto->descripcion_breve }}</p>
                          <p class="text-sm text-[#111827] font-medium">💲{{ number_format($producto->precio_venta, 0, ',', '.') }}</p>

                          <form action="{{ route('checkout.add', ['id' => $empresa->id]) }}" method="POST" class="mt-3 flex gap-2">
                              @csrf
                              <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                              <input type="number" name="cantidad" value="1" min="1"
                                     class="w-16 border rounded text-center text-sm border-gray-300">
                              <button type="submit"
                                      class="bg-[#6274c9] text-white text-sm px-3 py-1 rounded hover:bg-[#4e5bb0] transition">
                                  ➕ Agregar
                              </button>
                          </form>
                      </div>
                  @empty
                      <p class="text-gray-500">No hay productos disponibles en este negocio.</p>
                  @endforelse
              </div>
          </div>

          {{-- 🧾 Carrito --}}
          <div>
              <h3 class="text-xl font-semibold mb-4 text-[#4B5563]">🛒 Tu carrito</h3>
              @if(count($carrito))
                  <div class="bg-white rounded-xl shadow p-4 space-y-3 border border-gray-100">
                      @php $total = 0; @endphp
                      @foreach($carrito as $item)
                          @php $subtotal = $item['precio_unitario'] * $item['cantidad']; $total += $subtotal; @endphp
                          <div class="border-b pb-2">
                              <p class="font-semibold text-[#374151]">{{ $item['nombre'] }}</p>
                              <p class="text-sm text-[#6B7280]">Cantidad: {{ $item['cantidad'] }}</p>
                              <p class="text-sm text-[#6B7280]">Subtotal: ${{ number_format($subtotal, 0, ',', '.') }}</p>
                          </div>
                      @endforeach

                      <div class="mt-4 font-semibold text-lg text-[#111827] border-t pt-2">
                          Total: ${{ number_format($total, 0, ',', '.') }}
                      </div>

                      <form action="{{ route('checkout.finalizar', ['id' => $empresa->id]) }}" method="POST" class="mt-4">
                          @csrf
                          <button type="submit"
                                  class="w-full bg-[#6274c9] hover:bg-[#4e5bb0] text-white px-4 py-2 rounded shadow">
                              ✅ Finalizar pedido
                          </button>
                      </form>
                  </div>
              @else
                  <p class="text-gray-500">Tu carrito está vacío.</p>
              @endif
          </div>
      </div>
  </div>
</div>

@endsection
