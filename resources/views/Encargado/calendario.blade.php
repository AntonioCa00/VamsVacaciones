@extends('plantillaEncargado')

@section('contenido')
    @if (session()->has('aprobado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se han aprobado las vacaciones!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">
        <div class="py-1 d-flex justify-content-between align-items-center">
            <!-- Page Heading -->
            <h1 class="h3 mb-1 text-gray-800">CALENDARIO DE VACACIONES SOLICITADAS</h1>
            <div>
                <h3>Colores:</h3>
                <div class="d-flex align-items-left">
                    <button class="btn" style="background: #587dd2; color: white;">Pendiente</button>
                    <button class="btn" style="background: #18bb7d; color: white;">Aprobado</button>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="card-body">
                <div id='calendar'></div>
                @foreach ($events as $event)
                    <!-- Modal para el evento -->
                    <div class="modal fade" id="editEventModal{{ $event['id'] }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar solicitud vacaciones</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">X</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('aprobarEnc') }}" method="POST">
                                        <input type="hidden" name="eventId" id="eventId{{ $event['id'] }}">
                                        <div class="form-group d-flex">
                                            <div class="me-5">
                                                <label for="desde">Del día:</label>
                                                <input id="eventStart{{ $event['id'] }}" name="desde" type="date"
                                                    class="form-control" required>
                                            </div>
                                            <div class="me-5">
                                                <label for="hasta">Al día:</label>
                                                <input id="eventEnd{{ $event['id'] }}" name="hasta" type="date"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    @csrf
                                    {!! method_field('PUT') !!}
                                    <button type="submit" class="btn btn-primary">Aprobar</button>
                                    </form>
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: ['dayGrid', 'interaction'],
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },
                        locale: 'es', // Configurar el idioma a español
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            day: 'Día'
                        },
                        hiddenDays: [0], // Oculta domingos (0) y sábados (6)
                        events: {
                            url: 'vacaciones/programacion',
                            method: 'GET',
                            failure: function() {
                                alert('Hubo un error al cargar los eventos!');
                            },
                            success: function(data) {
                                console.log('Eventos cargados:',
                                    data); // Asegúrate de que se están cargando correctamente
                            }
                        },
                        editable: true,
                        eventLimit: true,
                        eventContent: function(info) {
                            return {
                                html: '<b>' + info.event.title + '</b><br>' + (info.event.extendedProps.notas ||
                                    '')
                            };
                        },
                        eventClick: function(info) {
                            if (info.event.extendedProps.status === '0') {
                                // Si el estatus es '0', construir correctamente los IDs dinámicos de los campos
                                var eventIdField = 'eventId' + info.event.id; // ID dinámico del campo 'eventId'
                                var eventStartField = 'eventStart' + info.event
                                    .id; // ID dinámico del campo 'eventStart'
                                var eventEndField = 'eventEnd' + info.event
                                    .id; // ID dinámico del campo 'eventEnd'

                                // Cargar los valores correctos en los campos del modal
                                document.getElementById(eventIdField).value = info.event
                                    .id; // Asignar el ID del evento
                                document.getElementById(eventStartField).value = info.event.start.toISOString()
                                    .split('T')[0]; // Asignar la fecha de inicio
                                document.getElementById(eventEndField).value = info.event.extendedProps
                                    .originalEnd;; // Asignar la fecha de fin original

                                // Mostrar el modal correspondiente al evento con el ID dinámico
                                var modalId = 'editEventModal' + info.event.id;
                                $('#' + modalId).modal('show'); // Mostrar el modal

                            } else {
                                // Si el estatus no es '0', mostrar alerta
                                alert('Vacaciones Aprobadas de: ' + info.event.title);
                            }
                        }
                    });

                    calendar.render();
                });
            </script>
        </div> <!-- Fin de la clase container-fluid -->
    </div> <!-- Fin de la clase container-fluid -->
@endsection
