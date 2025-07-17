@extends('layouts.empresa')

@section('title', 'Procedencia de los clientes')

@section('content')
<div class="px-8 py-10 min-h-screen">
    {{-- Encabezado --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-indigo-700">📊 Procedencia de los clientes</h2>
        <p class="text-sm text-gray-600">Gestiona cómo tus clientes descubren tu negocio.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4 relative">
                {{ session('success') }}
                <button type="button" class="absolute top-2 right-2 text-green-900" onclick="this.parentElement.remove()">
                    ✕
                </button>
            </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('empresa.configuracion.procedencia.update') }}" class="space-y-4">
            @csrf

            <div>
                <label for="neg_instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                <input type="text" name="neg_instagram" id="neg_instagram"
                       value="{{ $empresa->neg_instagram }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="neg_facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                <input type="text" name="neg_facebook" id="neg_facebook"
                       value="{{ $empresa->neg_facebook }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="neg_sitio_web" class="block text-sm font-medium text-gray-700">Sitio Web</label>
                <input type="text" name="neg_sitio_web" id="neg_sitio_web"
                       value="{{ $empresa->neg_sitio_web }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="text-end pt-4">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-md text-sm">
                    💾 Guardar Procedencia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
