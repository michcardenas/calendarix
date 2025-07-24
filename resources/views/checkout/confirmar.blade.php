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

<div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded px-8 pt-6 pb-8">
    <h2 class="text-2xl font-bold text-center mb-6 text-[#6274c9]">Confirmar Pedido</h2>

    <form action="{{ route('checkout.guardar', $pedido->id) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre completo</label>
            <input name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Correo electrónico</label>
            <input name="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
            <input name="telefono" class="shadow appearance-none border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Dirección de entrega</label>
            <textarea name="direccion" class="shadow appearance-none border rounded w-full py-2 px-3" rows="3" required></textarea>
        </div>

        <h3 class="text-lg font-semibold mb-2">Resumen del pedido:</h3>
        <ul class="mb-4 text-sm">
            @foreach($pedido->detalles as $detalle)
            <li>
                - {{ $detalle->producto->nombre ?? $detalle->servicio->nombre }} ({{ $detalle->cantidad }} unidades)
                → ${{ number_format($detalle->precio_total, 0, ',', '.') }}
            </li>
            @endforeach
        </ul>

        <div class="text-right">
            <button type="submit" class="bg-[#6274c9] hover:bg-[#4b5db8] text-white font-bold py-2 px-4 rounded">
                Finalizar Pedido
            </button>
        </div>
    </form>
</div>
@endsection
