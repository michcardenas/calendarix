@extends('layouts.empresa')

@section('title', 'Clientes')

@section('content')
    <div class="px-6 py-8">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-[#5a31d7]">👥 Clientes</h2>
                <p class="text-sm text-[#3B4269B3]">Gestión de tus clientes registrados.</p>
            </div>
            <button onclick="document.getElementById('modalCrearCliente').classList.remove('hidden')"
                class="bg-[#5a31d7] hover:bg-[#7b5ce0] text-white px-4 py-2 rounded text-sm transition-all">
                <i class="fas fa-user-plus mr-2"></i> Nuevo Cliente
            </button>
        </div>

        {{-- Tabla de clientes --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto border border-[#5a31d71A]">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-[#f6f5f7] text-[#3B4269] font-semibold">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Correo</th>
                        <th class="px-4 py-2">Teléfono</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-[#f6f5f7] transition">
                            <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-2">{{ $cliente->email ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $cliente->telefono ?? '—' }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <button onclick="document.getElementById('modalEditarCliente{{ $cliente->id }}').classList.remove('hidden')"
                                    class="text-[#5a31d7] hover:underline font-medium">Editar</button>

                                <form action="{{ route('empresa.clientes.destroy', ['empresa' => $empresa->id, 'cliente' => $cliente->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- 🔁 Modal de edición individual --}}
                        @include('empresa.clientes.partials.modal-editar-cliente', ['cliente' => $cliente])
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-400">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ➕ Modal Crear Cliente --}}
    @include('empresa.clientes.partials.modal-crear-cliente')
@endsection
