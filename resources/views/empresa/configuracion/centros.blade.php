@extends('layouts.empresa')

@section('title', 'Centros')

@section('content')
<div class="px-8 py-10  min-h-screen ">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-indigo-600">🏢 Centros</h2>
        <p class="text-sm text-gray-600">Gestiona la información y las ubicaciones de los centros de tu negocio.</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        @if($centros->isEmpty())
            <p class="text-center text-gray-400">No hay centros registrados aún.</p>
        @else
            <ul class="divide-y divide-gray-200 mb-6">
                @foreach($centros as $centro)
                    <li class="py-4" id="centro-principal">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $centro['nombre'] }}</p>
                                <p class="text-sm text-gray-600" id="direccion-text-principal">
                                    Dirección: {{ $centro['direccion'] }}
                                </p>

                                <form method="POST"
                                      action="{{ route('empresa.configuracion.centros.update', 'principal') }}"
                                      class="hidden mt-2 space-y-2"
                                      id="form-direccion-principal">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" name="direccion"
                                               value="{{ $centro['direccion'] }}"
                                               class="form-input px-4 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full sm:w-auto"
                                               required>

                                        <button type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm">
                                            💾 Guardar
                                        </button>
                                        <button type="button"
                                                onclick="cancelarEdicion('principal')"
                                                class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded-md text-sm">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>

                                <span class="text-xs text-gray-500 mt-1 block">Centro principal</span>
                            </div>

                            <button class="text-indigo-600 hover:underline text-sm font-medium"
                                    onclick="editarDireccion('principal')">
                                ✏️ Editar
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="text-right mt-4">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fas fa-plus mr-1"></i> Añadir Centro
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editarDireccion(id) {
        document.getElementById('direccion-text-' + id).style.display = 'none';
        document.getElementById('form-direccion-' + id).classList.remove('hidden');
    }

    function cancelarEdicion(id) {
        document.getElementById('direccion-text-' + id).style.display = 'block';
        document.getElementById('form-direccion-' + id).classList.add('hidden');
    }
</script>
@endpush
