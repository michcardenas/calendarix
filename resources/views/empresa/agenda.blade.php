

@extends('layouts.empresa')

@php $currentPage = 'agenda'; $currentSubPage = null; @endphp

@section('content')
<style>
    .content-area {
        margin-left: 14rem;
    }
</style>
<div class="container mt-4">
    <h2 class="mb-4">Agenda de la Empresa</h2>
    <div id='calendar'></div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [],
        });

        calendar.render();
    });
</script>
@endpush