<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  {{-- Resumen --}}
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Resumen del pedido</h3>

    <ul class="divide-y divide-gray-200 text-sm">
      @foreach ($items as $it)
        <li class="py-3 flex items-start justify-between">
          <div class="pr-3">
            <p class="font-medium text-gray-900">{{ $it['nombre'] ?? 'Ítem' }}</p>
            <p class="text-gray-600">
              Cantidad: {{ $it['cantidad'] }} &middot; ${{ number_format($it['precio_unitario'] ?? $it['precio'], 0, ',', '.') }}
            </p>
          </div>
          @php
            $pu = (float)($it['precio_unitario'] ?? $it['precio']);
            $linea = $pu * (int)$it['cantidad'];
          @endphp
          <div class="font-semibold text-gray-900 whitespace-nowrap">
            ${{ number_format($linea, 0, ',', '.') }}
          </div>
        </li>
      @endforeach
    </ul>

    <div class="border-t mt-4 pt-4 flex items-center justify-between">
      <p class="text-base font-semibold">Total</p>
      <p class="text-xl font-extrabold text-[#4a5eaa]">
        ${{ number_format($total, 0, ',', '.') }}
      </p>
    </div>
  </div>

  {{-- Formulario --}}
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Tus datos</h3>

    <div id="checkoutErrors" class="hidden rounded bg-red-50 text-red-700 px-4 py-3 text-sm mb-3"></div>

    <form id="checkoutGuardarForm" method="POST" action="{{ route('checkout.confirmar', $negocioId) }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="nombre" class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200" required>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="nombre"></p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Correo</label>
        <input type="email" name="email" class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200" required>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="email"></p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input type="text" name="telefono" class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200" required>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="telefono"></p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Dirección</label>
        <input type="text" name="direccion" class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200" required>
        <p class="text-red-600 text-xs mt-1 hidden" data-error-for="direccion"></p>
      </div>

      <button type="submit" class="w-full bg-[#6274c9] hover:bg-[#4e5bb0] text-white font-medium py-2 rounded-lg shadow flex items-center justify-center gap-2">
        ✅ <span>Finalizar pedido</span>
      </button>
    </form>
  </div>
</div>
