@extends('layouts.app')

@section('content')
<style>
    body::before,
    body::after {
        content: '';
        position: fixed;
        inset: 0;
        background-repeat: no-repeat;
        background-size: cover;
        z-index: 0;
        pointer-events: none;
    }

    body::before {
        background:
            radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
            radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25) 0%, transparent 60%),
            radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3) 0%, transparent 60%);
        animation: bubblesBefore 18s ease-in-out infinite;
    }

    body::after {
        background:
            radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2) 0%, transparent 60%),
            radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28) 0%, transparent 60%);
        animation: bubblesAfter 22s ease-in-out infinite reverse;
    }

    @keyframes bubblesBefore {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    @keyframes bubblesAfter {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(30px); }
    }
</style>

<div class="flex items-center justify-center min-h-screen bg-red-50 px-4">
    <div class="max-w-md w-full bg-white shadow-lg rounded-xl p-8 text-center border border-red-200">
        <svg class="w-20 h-20 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M21 12a9 9 0 1 1 -18 0a9 9 0 0 1 18 0z" />
        </svg>
        <h2 class="text-2xl font-bold text-red-600 mb-2">Algo salió mal</h2>
        <p class="text-gray-600 mb-6">No pudimos completar tu pedido. Por favor intenta de nuevo o contáctanos si el problema persiste.</p>
        <a href="{{ route('carrito.ver', $negocio_id ?? 1) }}"
           class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg shadow">
            Volver al carrito
        </a>
    </div>
</div>
@endsection
