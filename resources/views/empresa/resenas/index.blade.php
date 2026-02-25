@extends('layouts.empresa')

@section('title', 'Reseñas - ' . $empresa->neg_nombre_comercial)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold" style="color: #5a31d7;">Reseñas</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex items-center gap-6">
            <div class="text-center">
                <div class="text-4xl font-bold" style="color: #5a31d7;">
                    {{ $promedioRating ?? '—' }}
                </div>
                <div class="flex items-center justify-center mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $promedioRating && $i <= round($promedioRating) ? 'text-yellow-400' : 'text-gray-200' }}" style="font-size: 0.875rem;"></i>
                    @endfor
                </div>
                <div class="text-sm text-gray-500 mt-1">{{ $resenas->count() }} {{ $resenas->count() === 1 ? 'reseña' : 'reseñas' }}</div>
            </div>

            <div class="flex-1">
                @for($star = 5; $star >= 1; $star--)
                    @php $count = $resenas->where('rating', $star)->count(); @endphp
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm text-gray-600 w-6 text-right">{{ $star }}</span>
                        <i class="fas fa-star text-yellow-400" style="font-size: 0.7rem;"></i>
                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full" style="background-color: #5a31d7; width: {{ $resenas->count() ? ($count / $resenas->count() * 100) : 0 }}%;"></div>
                        </div>
                        <span class="text-sm text-gray-500 w-8">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    @if($resenas->count())
        <div class="space-y-4">
            @foreach($resenas as $resena)
                <div class="bg-white rounded-xl shadow-sm border p-5 {{ !$resena->respuesta_negocio ? 'border-l-4' : '' }}" style="{{ !$resena->respuesta_negocio ? 'border-left-color: #ffa8d7;' : '' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="font-semibold text-gray-800">{{ $resena->user->name ?? 'Cliente' }}</span>
                            <span class="text-sm text-gray-400 ml-2">{{ $resena->created_at->format('d/m/Y') }}</span>
                            @if($resena->cita && $resena->cita->servicio)
                                <span class="text-sm text-gray-400 ml-2">- {{ $resena->cita->servicio->nombre }}</span>
                            @endif
                        </div>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $resena->rating ? 'text-yellow-400' : 'text-gray-200' }}" style="font-size: 0.8rem;"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-3">{{ $resena->comentario }}</p>

                    @if($resena->respuesta_negocio)
                        <div class="bg-gray-50 rounded-lg p-4 ml-4 border-l-2" style="border-left-color: #5a31d7;">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-store text-sm" style="color: #5a31d7;"></i>
                                <span class="font-medium text-sm" style="color: #5a31d7;">Respuesta del negocio</span>
                                <span class="text-xs text-gray-400">{{ $resena->respuesta_fecha?->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-gray-600 text-sm">{{ $resena->respuesta_negocio }}</p>
                        </div>
                    @else
                        <div class="mt-3 ml-4">
                            <button onclick="document.getElementById('respForm{{ $resena->id }}').classList.toggle('hidden')"
                                    class="text-sm font-medium hover:underline" style="color: #5a31d7;">
                                <i class="fas fa-reply"></i> Responder
                            </button>
                            <form id="respForm{{ $resena->id }}" action="{{ route('resenas.responder', $resena->id) }}" method="POST" class="hidden mt-2">
                                @csrf
                                <textarea name="respuesta_negocio" rows="2" required maxlength="1000"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5a31d7]"
                                          placeholder="Escribí tu respuesta..."></textarea>
                                <div class="flex gap-2 mt-2">
                                    <button type="submit" class="px-4 py-1.5 text-white text-sm rounded-lg" style="background-color: #5a31d7;">Enviar</button>
                                    <button type="button" onclick="this.closest('form').classList.add('hidden')"
                                            class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-lg">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border p-8 text-center">
            <i class="fas fa-star text-gray-300 text-3xl mb-3"></i>
            <p class="text-gray-500">Aún no hay reseñas para este negocio.</p>
        </div>
    @endif
</div>
@endsection
