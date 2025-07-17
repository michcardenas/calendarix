@extends('layouts.app')

@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
<div class="bg-gray-100 py-10">
    <div class="max-w-4xl mx-auto">

{{-- Portada o color institucional --}}
<div class="relative w-full h-60 rounded-lg overflow-hidden shadow bg-indigo-600 mb-8">
    @if($negocio->neg_imagen)
        <img src="{{ $negocio->neg_imagen }}" class="absolute inset-0 w-full h-full object-cover" alt="Portada">
    @endif
</div>

{{-- Avatar + Encabezado --}}
<div class="relative -mt-14 flex items-center gap-4 px-6 pb-6">
    <div class="shrink-0">
        <img src="{{ $negocio->neg_imagen ?? '/images/default-user.png' }}"
             class="w-28 h-28 rounded-full border-4 border-white object-cover shadow-lg bg-white"
             alt="Avatar">
    </div>
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $negocio->neg_nombre_comercial }}</h1>
        <p class="text-gray-500 text-sm">{{ $negocio->neg_categoria }}</p>
    </div>
</div>
        {{-- Secciones --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6">

            {{-- Info y Servicios --}}
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Información del negocio</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>Email:</strong> {{ $negocio->neg_email }}</li>
                        <li><strong>Teléfono:</strong> {{ $negocio->neg_telefono }}</li>
                        <li><strong>Dirección:</strong> {{ $negocio->neg_direccion }}</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg p-4 shadow">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Servicios</h3>
                    @if($negocio->servicios->count())
                        <ul class="list-disc pl-4 text-sm text-gray-700 space-y-1">
                            @foreach($negocio->servicios as $servicio)
                                <li>{{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Este negocio aún no tiene servicios registrados.</p>
                    @endif
                </div>
            </div>

            {{-- Horarios y Calendario --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Horarios de Atención</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 border-b">
                                <th class="py-1 text-left">Día</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($negocio->horarios as $h)
                                <tr class="border-b text-gray-700">
                                    <td class="py-1">
                                        {{ \Carbon\Carbon::create()->startOfWeek()->addDays($h->dia_semana - 1)->locale('es')->isoFormat('dddd') }}
                                    </td>
                                    <td>{{ $h->hora_inicio ?? '—' }}</td>
                                    <td>{{ $h->hora_fin ?? '—' }}</td>
                                    <td>{!! $h->activo ? '<span class="text-green-600 font-semibold">Sí</span>' : '<span class="text-red-600">No</span>' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($negocio->bloqueos->count())
                <div class="bg-white rounded-lg p-4 shadow">
                    <h3 class="text-md font-semibold text-gray-700 mb-4">Calendario de Días Bloqueados</h3>
                    <div id="calendarioBloqueos" class="rounded overflow-hidden"></div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendarioBloqueos');
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 500,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: [
                    @foreach($negocio->bloqueos as $bloqueo)
                        {
                            title: 'Bloqueado',
                            start: '{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',
                            allDay: true,
                            color: '#dc2626'
                        },
                    @endforeach
                ]
            });
            calendar.render();
        }
    });
</script>
@endpush
