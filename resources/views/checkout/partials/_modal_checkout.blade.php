<div id="modalCheckout" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-5xl rounded-2xl shadow-lg p-6 relative">
    <button type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700" data-close-checkout>✕</button>
    <h2 class="text-2xl font-bold text-[#4a5eaa] mb-4 flex items-center gap-2">
      <span>🧾</span> <span>Revisa tu pedido</span>
    </h2>

    @include('checkout.partials._contenido_checkout', [
      'items' => $items,
      'total' => $total,
      'negocioId' => $negocioId,
    ])
  </div>
</div>
