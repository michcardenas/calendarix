<div id="modalCrearCliente" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-lg font-bold text-purple-700 mb-4">➕ Crear Cliente</h2>

        <form action="{{ route('empresa.clientes.store', $empresa->id) }}" method="POST">
            @csrf

            <input type="hidden" name="negocio_id" value="{{ $empresa->id }}">


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="telefono" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="document.getElementById('modalCrearCliente').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white rounded hover:bg-purple-800">
                    Guardar
                </button>
            </div>
        </form>

        <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
            onclick="document.getElementById('modalCrearCliente').classList.add('hidden')">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
