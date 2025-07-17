@extends('layouts.empresa')

@section('title', 'Configuración del Negocio')

@section('content')
<div class="px-8 py-10 min-h-screen">
    {{-- Mensajes --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 rounded px-4 py-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 rounded px-4 py-3 mb-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-indigo-700">⚙️ Datos del negocio</h2>
        <p class="text-sm text-gray-600">Configura el nombre de tu negocio, país, idioma y enlaces externos.</p>
    </div>

    <div class="flex flex-wrap gap-6">
        {{-- Información del negocio --}}
        <div class="w-full md:w-[48%]">
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Información del negocio</h3>
                    <button class="text-sm text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded" id="btn-edit-neg-info">
                        Editar
                    </button>
                </div>

                <ul class="space-y-1 text-sm text-gray-700" id="neg-info-display">
                    <li><strong>Nombre:</strong> {{ $empresa->neg_nombre_comercial }}</li>
                    <li><strong>País:</strong> {{ $empresa->neg_pais ?? 'Colombia' }}</li>
                    <li><strong>Moneda:</strong> COP</li>
                    <li><strong>Idioma predeterminado:</strong> Español</li>
                </ul>

                <form action="{{ route('negocio.guardar') }}" method="POST" id="form-edit-neg-info" class="hidden mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                    
                    <div>
                        <label for="confneg_nombre" class="block text-sm font-medium text-gray-700">Nombre del negocio</label>
                        <input type="text" name="confneg_nombre" id="confneg_nombre"
                               value="{{ $empresa->neg_nombre_comercial }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="confneg_pais" class="block text-sm font-medium text-gray-700">País</label>
                        <select name="confneg_pais" id="confneg_pais"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>{{ $empresa->neg_pais ?? 'Colombia' }}</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded-md">
                            Guardar
                        </button>
                        <button type="button"
                                class="bg-gray-400 hover:bg-gray-500 text-white text-sm px-4 py-2 rounded-md"
                                id="btn-cancel-neg-info">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Enlaces externos --}}
        <div class="w-full md:w-[48%]">
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🔗 Enlaces externos</h3>
                <form action="{{ route('negocio.guardar') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    <div>
                        <label for="confneg_facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="url" name="confneg_facebook" id="confneg_facebook"
                               placeholder="https://facebook.com/tuempresa"
                               value="{{ $empresa->neg_facebook }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="confneg_instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                        <input type="url" name="confneg_instagram" id="confneg_instagram"
                               placeholder="https://instagram.com/tuempresa"
                               value="{{ $empresa->neg_instagram }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="confneg_web" class="block text-sm font-medium text-gray-700">Sitio Web</label>
                        <input type="url" name="confneg_web" id="confneg_web"
                               placeholder="https://tuempresa.com"
                               value="{{ $empresa->neg_sitio_web }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                        Guardar Enlaces
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('btn-edit-neg-info').addEventListener('click', function () {
        document.getElementById('neg-info-display').classList.add('hidden');
        document.getElementById('form-edit-neg-info').classList.remove('hidden');
    });

    document.getElementById('btn-cancel-neg-info').addEventListener('click', function () {
        document.getElementById('neg-info-display').classList.remove('hidden');
        document.getElementById('form-edit-neg-info').classList.add('hidden');
    });
</script>
@endpush
@endsection
