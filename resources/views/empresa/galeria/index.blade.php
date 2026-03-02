@extends('layouts.empresa')

@section('title', 'Galeria - ' . $empresa->neg_nombre_comercial)

@section('content')
<div class="w-full max-w-4xl">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #5a31d7;">
                <i class="fas fa-images mr-2 opacity-70"></i>Galeria de Fotos
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Mostra tu negocio con fotos atractivas.
                <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded-full text-xs font-semibold"
                      style="background-color: #f0ecfb; color: #5a31d7;">
                    {{ $fotos->count() }} / 12
                </span>
            </p>
        </div>
        @if($fotos->count() < 12)
            <button onclick="document.getElementById('fotoInput').click()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-all"
                    style="background-color: #5a31d7;">
                <i class="fas fa-plus"></i> Subir Fotos
            </button>
        @endif
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Zona de subida --}}
    @if($fotos->count() < 12)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form id="uploadForm" action="{{ route('empresa.galeria.store', $empresa->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label id="dropzone" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-[#5a31d7] hover:bg-[#5a31d7]/5 transition-all py-10">
                <div id="dropzone-placeholder">
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center mb-3" style="background-color: #f0ecfb;">
                            <i class="fas fa-cloud-upload-alt text-xl" style="color: #5a31d7;"></i>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">Haz clic o arrastra tus fotos aqui</span>
                        <span class="text-xs text-gray-400 mt-1">JPG, PNG, WebP &mdash; Max 2MB por foto &mdash; Hasta {{ 12 - $fotos->count() }} mas</span>
                    </div>
                </div>
                <input type="file" id="fotoInput" name="fotos[]" multiple
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       class="hidden" onchange="previewFotos(this)">
            </label>

            {{-- Preview de fotos seleccionadas --}}
            <div id="preview-container" class="hidden mt-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm text-gray-500 font-medium">
                        <i class="fas fa-photo-video mr-1" style="color: #5a31d7;"></i>
                        <span id="preview-count">0</span> fotos seleccionadas
                    </p>
                    <div class="flex gap-2">
                        <button type="button" onclick="limpiarSeleccion()" class="px-4 py-2 text-xs font-medium text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white rounded-lg transition"
                                style="background-color: #5a31d7;">
                            <i class="fas fa-upload"></i> Subir todas
                        </button>
                    </div>
                </div>
                <div id="preview-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>
            </div>
        </form>
    </div>
    @endif

    {{-- Galeria --}}
    @if($fotos->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($fotos as $foto)
                    <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-100 bg-gray-50" style="aspect-ratio: 1/1;">
                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto galeria"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-end justify-end p-2.5">
                            <form action="{{ route('empresa.galeria.destroy', [$empresa->id, $foto->id]) }}" method="POST"
                                  onsubmit="return confirm('Eliminar esta foto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white/90 backdrop-blur text-red-500 rounded-lg px-3 py-1.5 text-xs font-medium shadow-sm hover:bg-red-500 hover:text-white transition-all flex items-center gap-1.5">
                                    <i class="fas fa-trash-alt text-[10px]"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #f0ecfb;">
                <i class="fas fa-images text-2xl" style="color: #5a31d7; opacity: 0.5;"></i>
            </div>
            <p class="text-gray-600 font-medium mb-1">No hay fotos en la galeria</p>
            <p class="text-sm text-gray-400">Subi fotos de tu negocio para que los clientes lo conozcan mejor.</p>
        </div>
    @endif
</div>

<script>
function previewFotos(input) {
    const container = document.getElementById('preview-container');
    const grid = document.getElementById('preview-grid');
    const countEl = document.getElementById('preview-count');
    const maxAllowed = {{ 12 - $fotos->count() }};

    grid.innerHTML = '';

    if (!input.files || input.files.length === 0) {
        container.classList.add('hidden');
        return;
    }

    const files = Array.from(input.files).slice(0, maxAllowed);
    countEl.textContent = files.length;
    container.classList.remove('hidden');

    files.forEach(function(file, i) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50';
            div.style.aspectRatio = '1/1';
            div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" alt="Preview">';
            grid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    if (input.files.length > maxAllowed) {
        setTimeout(function() {
            const warn = document.createElement('p');
            warn.className = 'text-xs text-amber-500 mt-2 col-span-full';
            warn.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Solo se subiran ' + maxAllowed + ' fotos (limite de 12).';
            grid.after(warn);
        }, 100);
    }
}

function limpiarSeleccion() {
    document.getElementById('fotoInput').value = '';
    document.getElementById('preview-container').classList.add('hidden');
    document.getElementById('preview-grid').innerHTML = '';
}
</script>
@endsection
