@extends('layouts.empresa')

@section('title', 'Trabajadores')

@section('content')
    <div class="px-6 py-8">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-[#4a5eaa]">👷 Trabajadores</h2>
                <p class="text-sm text-[#3B4269B3]">Gestión de tu equipo de trabajo.</p>
            </div>
            <button onclick="document.getElementById('modalCrearTrabajador').classList.remove('hidden')"
                class="bg-[#4a5eaa] hover:bg-[#6C88C4] text-white px-4 py-2 rounded text-sm transition-all">
                <i class="fas fa-user-plus mr-2"></i> Nuevo Trabajador
            </button>
        </div>

        {{-- Tabla de trabajadores --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto border border-[#4a5eaa1A]">
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
                    @forelse ($trabajadores as $trabajador)
                        <tr class="hover:bg-[#f6f5f7] transition">
                            <td class="px-4 py-2">{{ $trabajador->nombre }}</td>
                            <td class="px-4 py-2">{{ $trabajador->email ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $trabajador->telefono ?? '—' }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <button onclick="document.getElementById('modalEditarTrabajador{{ $trabajador->id }}').classList.remove('hidden')"
                                    class="text-[#4a5eaa] hover:underline font-medium">Editar</button>

                                <form action="{{ route('empresa.trabajadores.destroy', ['empresa' => $empresa->id, 'trabajador' => $trabajador->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('¿Estás seguro de eliminar este trabajador?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No hay trabajadores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Crear --}}
    @include('empresa.trabajadores.partials.modal-create')

    {{-- Modales de edición --}}
    @foreach ($trabajadores as $trabajador)
        @include('empresa.trabajadores.partials.modal-edit', ['trabajador' => $trabajador])
    @endforeach
@endsection
