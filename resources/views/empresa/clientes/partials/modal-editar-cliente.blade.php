{{-- Modal Editar Cliente --}}
<div id="modalEditarCliente{{ $cliente->id }}" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg border border-[#4a5eaa1A] w-full max-w-md p-6 relative">
        <h2 class="text-lg font-bold text-[#4a5eaa] mb-4">✏️ Editar Cliente</h2>

        <form action="{{ route('empresa.clientes.update', ['empresa' => $empresa->id, 'cliente' => $cliente->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="negocio_id" value="{{ $empresa->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Nombre</label>
                <input type="text" name="nombre" value="{{ $cliente->nombre }}" class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#4a5eaa]" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Correo electrónico</label>
                <input type="email" name="email" value="{{ $cliente->email }}" class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#4a5eaa]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Teléfono</label>
                <input type="text" name="telefono" value="{{ $cliente->telefono }}" class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#4a5eaa]">
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <button type="button"
                    onclick="document.getElementById('modalEditarCliente{{ $cliente->id }}').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-[#4a5eaa] text-white rounded hover:bg-[#6C88C4] transition">
                    Actualizar
                </button>
            </div>
        </form>

        <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
            onclick="document.getElementById('modalEditarCliente{{ $cliente->id }}').classList.add('hidden')">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

