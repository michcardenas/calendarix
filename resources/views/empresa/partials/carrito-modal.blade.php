<div id="modalCarrito" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">
        <h2 class="text-2xl font-bold text-[#4a5eaa] mb-4">🛒 Tu Carrito</h2>

        <ul id="carritoItems" class="divide-y divide-gray-200 max-h-64 overflow-y-auto mb-4 text-sm text-gray-800"></ul>

        <div class="flex justify-between items-center mt-4">
            <p class="font-semibold text-lg">Total: <span id="carritoTotal" class="text-[#4a5eaa]">$0</span></p>
            <button id="cerrarModalCarrito" class="bg-gray-300 hover:bg-gray-400 text-sm px-4 py-2 rounded">Cerrar</button>
        </div>

        <form id="formCheckout" method="POST" action="{{ route('checkout.redirigir', $empresa->id) }}">
            @csrf
            <input type="hidden" name="carrito" id="carritoJsonInput">
            <button type="submit"
                class="w-full bg-[#6274c9] hover:bg-[#4e5bb0] text-white text-sm py-2 rounded shadow font-medium">
                🧾 Ir al Checkout
            </button>
        </form>
    </div>
</div>