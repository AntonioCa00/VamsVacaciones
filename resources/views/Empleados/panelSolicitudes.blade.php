@extends('plantillaEmpleado')

@section('contenido')
    @if (session()->has('entra'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Bienvenido a control vacaciones VAMS.',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">SOLICITAR PERMISOS</h1>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Card permiso durante -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card" style="width: 100%;">
                    <img src="{{ asset('img/durante.png') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"> <u><strong>Durante jornada de trabajo </strong> </u> </h5>
                        <a href="{{ route('durante') }}" class="btn btn-info">Solicitar</a>
                    </div>
                </div>
            </div>

            <!-- Card permiso dias -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card" style="width: 100%;">
                    <img src="{{ asset('img/ausentarse.png') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"> <u><strong>Ausentarse de sus labores </strong> </u> </h5>
                        {{-- <p class="card-text">Some quick example text to build on the card title and.</p> --}}
                        <a href="{{ route('ausentarse') }}" class="btn btn-info">Solicitar</a>
                    </div>
                </div>
            </div>

            <!-- Card vacaciones -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card" style="width: 100%;">
                    <img src="{{ asset('img/vacaciones.png') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"> <u><strong>Vacaciones </strong> </u> </h5>
                        @if (session('dias_disponibles') > 0)
                            <a href="{{ route('vacaciones') }}" class="btn btn-info">Solicitar</a>
                        @else
                            <a href="#" class="btn btn-info" onclick="return false;"
                                style="pointer-events: none; background-color: gray; cursor: not-allowed;">Solicitar</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
