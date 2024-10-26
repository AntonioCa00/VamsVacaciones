@extends('plantillaEncargado')

@section('contenido')
    @if (session()->has('duplicado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Ya se ha registrado ese puesto',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('incorrecto'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Horas no permitidas',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif


        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">CREAR PERMISO DURANTE JORNADA DE TRABAJO</h1>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Crear solicitud</h6>
                </div>
                <div class="card-body">
                    <h3 class="text-center">Datos de registro</h3>
                    <form action="{{ route('createDuranteEnc') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="divisionName">Fecha del día de ausencia:</label>
                            <input name="fecha" id="di
                    visionName" type="date" class="form-control"
                                placeholder="Fecha de ausencia" required>
                        </div>
                        <div class="form-group d-flex">
                            <div class="me-3">
                                <label for="desde">Desde:</label>
                                <input name="desde" id="desde" type="time" class="form-control" required>
                            </div>
                            <div>
                                <label for="hasta">Hasta:</label>
                                <input name="hasta" id="hasta" type="time" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Motivo de salida:</label>
                            <input type="text" class="form-control" name="motivo" id="motivo"
                                placeholder="Describe el motivo de tu salida" required>
                        </div>
                        <button class="btn btn-primary">Crear permiso</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
