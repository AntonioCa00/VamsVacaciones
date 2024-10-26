@extends('plantillaEncargado')

@section('contenido')
    @if (session()->has('creado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha registrado nuevo personal',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('editado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha editado el personal',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('eliminado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha eliminado el personal',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <style>
        .modal-body-scrollable {
            max-height: 70vh;
            overflow-y: auto;
            /* Se habilita el desplazamiento vertical si el contenido excede la altura máxima */
        }
    </style>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CONSULTA PERSONAL</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a class="btn btn-primary" href="{{ route('crearPersonalEnc') }}">Agregar nuevo personal</a>
                <a class="btn btn-success" href="{{ route('reporte') }}" style="margin-left: 70%;"><i
                    class="fas fa-download fa-sm text-white-50"></i>   Reporte general</a>
            </div>
            <div class="card-header py-3">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>N° Empleado:</th>
                                <th>Nombres:</th>
                                <th>Apellidos:</th>
                                <th>Puesto:</th>
                                <th>Detalles:</th>
                                <th>Eliminar:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($personal as $persona)
                                <tr>
                                    <th class="col-1">{{ $persona->numero_empleado }}</th>
                                    <th class="col-2">{{ $persona->nombres }}</th>
                                    <th class="col-3">{{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                    </th>
                                    <th class="col-3">{{ $persona->puesto }}</th>
                                    <th class="col-1 text-center">
                                        <a href="#" data-toggle="modal"
                                            data-target="#detalles{{ $persona->id_empleado }}">
                                            <img src="{{ asset('img/detalles.png') }}" alt="Abrir detalles">
                                        </a>
                                    <th class="col-1">
                                        <a class="btn btn-danger" href="#" data-toggle="modal"
                                            data-target="#eliminarPersona{{ $persona->id_empleado }}">
                                            Eliminar
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="eliminarPersona{{ $persona->id_empleado }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una
                                                            decisión?</h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">Selecciona confirmar para eliminar este
                                                        trabajador</div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">cancelar</button>
                                                        {{-- {{ route('deletePersonal',$persona->id_empleado) }} --}}
                                                        <form action="{{ route('deletePersonal', $persona->id_empleado) }}"
                                                            method="POST">
                                                            @csrf
                                                            {!! method_field('PUT') !!}
                                                            <button type="submit"
                                                                class="btn btn-primary">confirmar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <div class="modal fade" id="detalles{{ $persona->id_empleado }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Información detallada del
                                                    personal</h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body modal-body-scrollable">
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Nombre(s):</b></label>
                                                    <h6>{{ $persona->nombres }}</h6>
                                                </div>
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Apellido Paterno:</b></label>
                                                    <h6>{{ $persona->apellido_paterno }}</h6>
                                                </div>
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Apellido Materno:</b></label>
                                                    <h6>{{ $persona->apellido_materno }}</h6>
                                                </div>
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Fecha de
                                                            nacimiento:</b></label>
                                                    <h6>{{ $persona->fecha_nacimiento }}</h6>
                                                </div>
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Puesto:</b></label>
                                                    <h6>{{ $persona->puesto }}</h6>
                                                </div>
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Fecha de ingreso:</b></label>
                                                    <h6>{{ $persona->fecha_ingreso }}</h6>
                                                </div>
                                                <div class="form-group">
                                                    <b><label for="exampleFormControlInput1">Horario:</b></label>
                                                    <h6>{{ $persona->horario }}</h6>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{ route('editarPersonalEnc', $persona->id_empleado) }}"
                                                    class="btn btn-success">Actualizar información</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
