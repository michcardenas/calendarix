<div id="modalEditarTrabajador{{ $trabajador->id }}" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg border border-[#5a31d71A] w-full max-w-md p-6 relative">
        <h2 class="text-lg font-bold text-[#5a31d7] mb-4">✏️ Editar Trabajador</h2>

        <form action="{{ route('empresa.trabajadores.update', ['empresa' => $empresa->id, 'trabajador' => $trabajador->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Nombre</label>
                <input type="text" name="nombre" value="{{ $trabajador->nombre }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#5a31d7]" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Correo electrónico</label>
                <input type="email" name="email" value="{{ $trabajador->email }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#5a31d7]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Teléfono</label>
                <input type="text" name="telefono" value="{{ $trabajador->telefono }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#5a31d7]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Foto</label>
                @if($trabajador->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $trabajador->foto) }}" alt="Foto" class="w-16 h-16 rounded-full object-cover">
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a31d7]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Especialidades</label>
                <input type="text" name="especialidades" value="{{ $trabajador->especialidades }}" placeholder="Ej: Cortes, Colorimetría, Barba"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#5a31d7]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#3B4269]">Bio</label>
                <textarea name="bio" rows="2" maxlength="500" placeholder="Breve descripción del profesional..."
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#5a31d7]">{{ $trabajador->bio }}</textarea>
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <button type="button"
                    onclick="document.getElementById('modalEditarTrabajador{{ $trabajador->id }}').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-[#5a31d7] text-white rounded hover:bg-[#7b5ce0] transition">
                    Actualizar
                </button>
            </div>
        </form>

        <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
            onclick="document.getElementById('modalEditarTrabajador{{ $trabajador->id }}').classList.add('hidden')">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
