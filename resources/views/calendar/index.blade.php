@extends('layouts.app')

@section('titulo', 'Calendario')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-calendar3"></i> Calendario</h3>
            <p class="text-subtitle text-muted">Calendario de eventos</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Calendario</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex space-around justify-content-between">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calendarFeedModal">
                        Añadir Google Calendar
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calendarApiModal">
                        Api Key
                    </button>
                </div>

                <div id="calendar" class="p-4" style="min-height: 600px; margin-top: 0.75rem; margin-bottom: 0.75rem; overflow-y: auto; border-color:black; border-width: thin; border-radius: 20px;"></div>

                <div class="list-group mt-3">
                    @foreach($feed as $f)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Google Calendar ID: {{ $f['googleCalendarId'] }}
                            <span>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteFeed('{{ $f['id'] }}')">Eliminar</button>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="calendarFeedModal" tabindex="-1" aria-labelledby="calendarFeedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarFeedModalLabel">Añadir api de Calendario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedForm" method="POST" action="{{ route('calendar.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="googleCalendarId" class="form-label">Google Calendar ID</label>
                            <input type="text" class="form-control" id="googleCalendarId" name="googleCalendarId" required>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color del Evento</label>
                            <input type="color" class="form-control p-0" id="color" name="color" value="#377dff">
                        </div>
                        <div class="mb-3">
                            <label for="textColor" class="form-label">Color del Texto</label>
                            <input type="color" class="form-control p-0" id="textColor" name="textColor" value="#ffffff">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Feed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="calendarApiModal" tabindex="-1" aria-labelledby="calendarApiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarApiModalLabel">Añadir api de Google</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('api.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="api_key" class="form-label">Api Key Google</label>
                            <input type="text" class="form-control" id="api_key" name="api_key" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Api</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales-all.global.min.js"></script>
    @include('partials.toast')
    <script>
        var feed = @json($feed);
        var api = @json(optional($api)->api_key);

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                googleCalendarApiKey: api,
                initialView: 'dayGridMonth',
                locale: 'es',
                navLinks: true,
                nowIndicator: true,
                businessHours: [
                    { daysOfWeek: [1], startTime: '08:00', endTime: '15:00' },
                    { daysOfWeek: [2], startTime: '08:00', endTime: '15:00' },
                    { daysOfWeek: [3], startTime: '08:00', endTime: '15:00' },
                    { daysOfWeek: [4], startTime: '08:00', endTime: '15:00' },
                    { daysOfWeek: [5], startTime: '08:00', endTime: '15:00' }
                ],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay,listWeek'
                },
                eventSources: feed,
            });
            calendar.render();
        });

        function deleteFeed(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/calendar-feeds/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('¡Eliminado!', 'El feed ha sido eliminado.', 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Algo salió mal al intentar eliminar el feed.', 'error');
                        }
                    }).catch(error => {
                        Swal.fire('Error!', 'Algo salió mal al intentar eliminar el feed.', 'error');
                    });
                }
            });
        }
    </script>
@endsection
