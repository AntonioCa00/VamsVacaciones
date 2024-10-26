@extends('plantillaEncargado')

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

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Vaciones disponibles </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ session('dias_disponibles') }} Días
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Vacaciones tomadas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ session('dias_tomados') }} Días</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bienvenido al Sistema de Consulta de Vacaciones</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-2 mb-3" style="width: 50%;" src="{{ asset('img/Portada.png') }}"
                        alt="...">
                </div>
                <p>
                    Si tienes alguna pregunta o encuentras algún problema, por favor, contacta con el departamento de
                    recursos humanos para obtener ayuda.</p>
            </div>
        </div>
    </div>
@endsection
