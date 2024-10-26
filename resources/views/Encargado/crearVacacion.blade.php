@extends('plantillaEncargado')

@section('contenido')
    @if (session()->has('insuficiente'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: '¡No cuentas con dias suficientes para esta solicitud!',
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
            <h1 class="h3 mb-2 text-gray-800">CREAR PERMISO DE VACACIONES</h1>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Crear solicitud</h6>
                </div>
                <div class="card-body">
                    <h3 class="text-center">Datos de registro</h3>
                    <form action="{{ route('createVacacionesEnc') }}" method="POST">
                        @csrf
                        <div class="form-group d-flex">
                            <div class="me-5">
                                <label for="desde">Del día:</label>
                                <input value="{{ old('desde') }}" name="desde" id="desde" type="date"
                                    class="form-control" required>
                            </div>
                            <div class="me-5">
                                <label for="hasta">Al día:</label>
                                <input value="{{ old('hasta') }}" name="hasta" id="hasta" type="date"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">comentarios y observaciones:</label>
                            <input type="text" class="form-control" name="motivo" id="motivo"
                                placeholder="Escribe algun comentario u observacion de tu solicitud">
                        </div>
                        <button class="btn btn-primary">Crear permiso</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
