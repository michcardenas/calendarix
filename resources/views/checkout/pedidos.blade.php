@extends('layouts.app')

@section('content')
<style>
/* Fondo animado estilo Calendarix */
body::before,
body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-repeat: no-repeat;
  background-size: cover;
  z-index: 0;
  pointer-events: none;
}

body::before {
  background:
    radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
    radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25) 0%, transparent 60%),
    radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3) 0%, transparent 60%),
    radial-gradient(circle 130px at 70% 35%, rgba(90, 78, 187, 0.3) 0%, transparent 60%);
  animation: bubblesBefore 18s ease-in-out infinite;
}

body::after {
  background:
    radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2) 0%, transparent 60%),
    radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28) 0%, transparent 60%),
    radial-gradient(circle 120px at 55% 45%, rgba(126, 121, 201, 0.24) 0%, transparent 60%);
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

<div class="flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-6xl bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-[#6274c9] mb-6 text-center">
            📋 Pedidos de {{ $empresa->neg_nombre_comercial }}
        </h2>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($checkouts->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-[#6274c9] text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Producto / Servicio</th>
                            <th class="px-4 py-2 text-center">Cantidad</th>
                            <th class="px-4 py-2 text-center">Precio Unitario</th>
                            <th class="px-4 py-2 text-center">Total</th>
                            <th class="px-4 py-2 text-center">Método</th>
                            <th class="px-4 py-2 text-center">Estado</th>
                            <th class="px-4 py-2 text-center">Fecha</th>
                            <th class="px-4 py-2 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($checkouts as $checkout)
                            @foreach($checkout->detalles as $detalle)
                                <tr>
                                    <td class="px-4 py-2">
                                        {{ $detalle->producto->nombre ?? $detalle->servicio->nombre ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $detalle->cantidad }}</td>
                                    <td class="px-4 py-2 text-center">
                                        ${{ number_format($detalle->precio_unitario, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-center font-semibold">
                                        ${{ number_format($detalle->precio_total, 0, ',', '.') }}
                                    </td>

                                    @if ($loop->first)
                                        <td class="px-4 py-2 text-center" rowspan="{{ $checkout->detalles->count() }}">
                                            {{ $checkout->metodo_pago }}
                                        </td>
                                        <td class="px-4 py-2 text-center" rowspan="{{ $checkout->detalles->count() }}">
                                            <span class="px-2 py-1 rounded text-xs {{ $checkout->estado_pago === 'pagado' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ ucfirst($checkout->estado_pago) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center" rowspan="{{ $checkout->detalles->count() }}">
                                            {{ $checkout->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 text-center" rowspan="{{ $checkout->detalles->count() }}">
                                            @if($checkout->estado_pago === 'pendiente')
                                                <form action="{{ route('checkout.estado', $checkout->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                                        Marcar como pagado
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-green-700 text-xs">✓ Pagado</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-600 mt-8">No hay pedidos registrados aún.</p>
        @endif
    </div>
</div>
@endsection
