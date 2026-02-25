@extends('layouts.empresa')

@section('title', 'Procedencia de los clientes')

@section('content')
<div class="px-8 py-10 min-h-screen">
    {{-- Encabezado --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#5a31d7]">📊 Procedencia de los clientes</h2>
        <p class="text-sm text-[#3B4269B3]">Gestiona cómo tus clientes descubren tu negocio.</p>
    </div>

    <div class="bg-white border border-[#E5E7EB] rounded-lg p-6 shadow-sm">
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
                <label for="neg_instagram" class="block text-sm font-medium text-[#3B4269]">Instagram</label>
                <input type="text" name="neg_instagram" id="neg_instagram"
                       value="{{ $empresa->neg_instagram }}"
                       class="mt-1 block w-full border-[#D1D5DB] rounded-md shadow-sm focus:ring-[#5a31d7] focus:border-[#5a31d7]">
            </div>

            <div>
                <label for="neg_facebook" class="block text-sm font-medium text-[#3B4269]">Facebook</label>
                <input type="text" name="neg_facebook" id="neg_facebook"
                       value="{{ $empresa->neg_facebook }}"
                       class="mt-1 block w-full border-[#D1D5DB] rounded-md shadow-sm focus:ring-[#5a31d7] focus:border-[#5a31d7]">
            </div>

            <div>
                <label for="neg_sitio_web" class="block text-sm font-medium text-[#3B4269]">Sitio Web</label>
                <input type="text" name="neg_sitio_web" id="neg_sitio_web"
                       value="{{ $empresa->neg_sitio_web }}"
                       class="mt-1 block w-full border-[#D1D5DB] rounded-md shadow-sm focus:ring-[#5a31d7] focus:border-[#5a31d7]">
            </div>

            <div class="text-end pt-4">
                <button type="submit"
                        class="bg-[#5a31d7] hover:bg-[#7b5ce0] text-white font-medium px-4 py-2 rounded-md text-sm">
                    💾 Guardar Procedencia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
