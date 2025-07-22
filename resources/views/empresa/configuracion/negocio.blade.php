@extends('layouts.empresa')

@section('title', 'Configuración del Negocio')

@section('content')
<div class="px-8 py-10 min-h-screen" style="background-color: #f6f5f7">
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
        <h2 class="text-2xl font-bold text-[#4a5eaa]">⚙️ Datos del negocio</h2>
        <p class="text-sm text-[#3B4269B3]">Configura el nombre de tu negocio, país, idioma y enlaces externos.</p>
    </div>

    <div class="flex flex-wrap gap-6">
        {{-- Información del negocio --}}
        <div class="w-full md:w-[48%]">
            <div class="bg-white border border-[#D1D5DB] rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-[#3B4269]">Información del negocio</h3>
                </div>

                <ul class="space-y-1 text-sm text-[#3B4269] hidden" id="neg-info-display">
                    <li><strong>Nombre:</strong> {{ $empresa->neg_nombre_comercial }}</li>
                    <li><strong>País:</strong> {{ $empresa->neg_pais ?? 'Colombia' }}</li>
                    <li><strong>Moneda:</strong> COP</li>
                    <li><strong>Idioma predeterminado:</strong> Español</li>
                </ul>

                <form action="{{ route('negocio.guardar') }}" method="POST" id="form-edit-neg-info" class="mt-4 space-y-3" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    @if($empresa->neg_imagen)
                    <div>
                        <label class="block text-sm font-medium text-[#3B4269] mb-1">Logo actual</label>
                        <img src="{{ $empresa->neg_imagen }}" alt="Imagen del negocio" class="w-32 h-32 object-cover rounded-md mb-3 border">
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Nueva imagen del negocio (opcional)</label>
                        <input type="file" name="confneg_imagen" accept="image/*" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    @if($empresa->neg_portada)
                    <div>
                        <label class="block text-sm font-medium text-[#3B4269] mb-1">Portada actual</label>
                        <img src="{{ $empresa->neg_portada }}" alt="Portada del negocio" class="w-full max-h-48 object-cover rounded-md mb-3 border">
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Nueva portada del negocio (opcional)</label>
                        <input type="file" name="confneg_portada" accept="image/*" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Nombre del negocio</label>
                        <input type="text" name="confneg_nombre" value="{{ $empresa->neg_nombre_comercial }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">País</label>
                        <select name="confneg_pais" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                            <option selected>{{ $empresa->neg_pais ?? 'Colombia' }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Nombre del propietario</label>
                        <input type="text" name="confneg_nombre_real" value="{{ $empresa->neg_nombre }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Apellido del propietario</label>
                        <input type="text" name="confneg_apellido" value="{{ $empresa->neg_apellido }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Correo electrónico</label>
                        <input type="email" name="confneg_email" value="{{ $empresa->neg_email }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Teléfono</label>
                        <input type="text" name="confneg_telefono" value="{{ $empresa->neg_telefono }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Equipo de trabajo</label>
                        <input type="text" name="confneg_equipo" value="{{ $empresa->neg_equipo }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Dirección</label>
                        <input type="text" name="confneg_direccion" value="{{ $empresa->neg_direccion }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="confneg_virtual" value="1" {{ $empresa->neg_virtual ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#4a5eaa] shadow-sm">
                            <span class="ml-2 text-sm text-[#3B4269]">¿Negocio virtual?</span>
                        </label>
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="confneg_direccion_confirmada" value="1" {{ $empresa->neg_direccion_confirmada ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#4a5eaa] shadow-sm">
                            <span class="ml-2 text-sm text-[#3B4269]">¿Dirección confirmada?</span>
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="text-white text-sm px-4 py-2 rounded-md bg-[#4a5eaa] hover:bg-[#6C88C4]">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Enlaces externos --}}
        <div class="w-full md:w-[48%]">
            <div class="bg-white border border-[#D1D5DB] rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-[#3B4269]">🔗 Enlaces externos</h3>
                <form action="{{ route('negocio.guardar') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Facebook</label>
                        <input type="url" name="confneg_facebook" placeholder="https://facebook.com/tuempresa" value="{{ $empresa->neg_facebook }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Instagram</label>
                        <input type="url" name="confneg_instagram" placeholder="https://instagram.com/tuempresa" value="{{ $empresa->neg_instagram }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#3B4269]">Sitio Web</label>
                        <input type="url" name="confneg_web" placeholder="https://tuempresa.com" value="{{ $empresa->neg_sitio_web }}" class="w-full border-[#D1D5DB] rounded-md shadow-sm">
                    </div>

                    <button type="submit" class="text-white px-4 py-2 rounded-md text-sm bg-[#4a5eaa] hover:bg-[#6C88C4]">
                        Guardar Enlaces
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
