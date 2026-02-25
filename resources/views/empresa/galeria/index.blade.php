@extends('layouts.empresa')

@section('title', 'Galería - ' . $empresa->neg_nombre_comercial)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold" style="color: #5a31d7;">Galería de Fotos</h1>
        @if($fotos->count() < 12)
            <button onclick="document.getElementById('fotoInput').click()"
                    class="px-4 py-2 text-white text-sm font-medium rounded-lg" style="background-color: #5a31d7;">
                <i class="fas fa-upload mr-1"></i> Subir Foto
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form id="uploadForm" action="{{ route('empresa.galeria.store', $empresa->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" id="fotoInput" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp"
               onchange="document.getElementById('uploadForm').submit()">
    </form>

    <p class="text-sm text-gray-500 mb-4">{{ $fotos->count() }} de 12 fotos subidas. Formatos: JPG, PNG, WebP. Max 2MB.</p>

    @if($fotos->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($fotos as $foto)
                <div class="relative group rounded-xl overflow-hidden shadow-sm border aspect-square">
                    <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto galería"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <form action="{{ route('empresa.galeria.destroy', [$empresa->id, $foto->id]) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta foto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white/90 text-red-600 rounded-full w-10 h-10 flex items-center justify-center hover:bg-white">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border p-8 text-center">
            <i class="fas fa-images text-gray-300 text-3xl mb-3"></i>
            <p class="text-gray-500 mb-2">No hay fotos en la galería.</p>
            <p class="text-sm text-gray-400">Subí fotos de tu negocio para que los clientes lo conozcan mejor.</p>
        </div>
    @endif
</div>
@endsection
