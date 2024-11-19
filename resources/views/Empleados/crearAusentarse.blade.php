@extends('plantillaEmpleado')

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
                title: 'Fechas no permitidas',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CREAR PERMISO AUSENTARSE DE SUS LABORES</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Crear solicitud</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('createAusentarse') }}" method="POST">
                    @csrf
                    <div class="form-group d-flex">
                        <div class="me-5">
                            <label for="desde">Del día:</label>
                            <input name="desde" id="desde" type="date" class="form-control" required>
                        </div>
                        <div class="me-5">
                            <label for="hasta">Al día:</label>
                            <input name="hasta" id="hasta" type="date" class="form-control" required>
                        </div>
                        <div class="form-group me-5">
                            <label for="hasta">PERMISO CON:</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="btn-check" value="Con" name="tipo" id="option1"
                                    autocomplete="off" required disabled>
                                <label class="btn btn-outline-success" for="option1">CON GOCE</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="btn-check" value="Sin" name="tipo" id="option2"
                                    autocomplete="off" required>
                                <label class="btn btn-outline-secondary" for="option2">SIN GOCE</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="btn-check" value="Tiempo" name="tipo" id="option3"
                                    autocomplete="off" required>
                                <label class="btn btn-outline-primary" for="option3">TIEMPO X TIEMPO</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Motivo de salida:</label>
                        <input type="text" class="form-control" name="motivo" id="motivo"
                            placeholder="Describe el motivo de tu salida">
                    </div>
                    <button class="btn btn-primary">Crear permiso</button>
                </form>
            </div>
        </div>
    </div>
@endsection
