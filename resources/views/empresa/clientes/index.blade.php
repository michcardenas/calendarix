@extends('layouts.empresa')

@section('title', 'Clientes')

@section('content')
    <div class="px-6 py-8">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-purple-700">👥 Clientes</h2>
                <p class="text-sm text-gray-500">Gestión de tus clientes registrados.</p>
            </div>
            <button onclick="document.getElementById('modalCrearCliente').classList.remove('hidden')"
                class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 text-sm">
                <i class="fas fa-user-plus mr-2"></i> Nuevo Cliente
            </button>
        </div>

        {{-- Tabla de clientes --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-600 text-left">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Correo</th>
                        <th class="px-4 py-2">Teléfono</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-2">{{ $cliente->email ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $cliente->telefono ?? '—' }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <button onclick="document.getElementById('modalEditarCliente{{ $cliente->id }}').classList.remove('hidden')"
                                    class="text-blue-600 hover:underline">Editar</button>

                                <form action="{{ route('empresa.clientes.destroy', [$empresa->id, $cliente->id]) }}"
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
