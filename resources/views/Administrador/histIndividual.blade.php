@extends('plantillaAdmin')

@section('contenido')
    @if (session()->has('creado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha registrado una nueva solicitud',
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
                title: 'Se ha editado la solicitud',
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
                title: 'Se ha eliminado el historiall',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">HISTORIAL VACACIONES DE: {{ $empleado->nombres }} {{ $empleado->apellido_paterno}}</h1>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Vaciones disponibles </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dias_disponiblesI }} Días
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dias_tomadosI}} Días</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Desde:</th>
                                <th>Hasta:</th>
                                <th>Días tomados:</th>
                                <th>Estado:</th>
                                <th>Detalles:</th>
                                @if (session('area') != "LOGISTICA")
                                    <th>Eliminar:</th>
                                @endif                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historialVac as $historial)
                                <tr>
                                    <th>{{ $historial->inicio }}</th>
                                    <th class="col-2">{{ $historial->fin }} {{ $historial->apellido_materno }}</th>
                                    <th class="col-2">{{ $historial->dias_tomados }} Días</th>
                                    <th>
                                        {{ $historial->estatus === '0' ? 'Solicitadas' : 'Autorizado' }}
                                    </th>
                                    <th class="col-1">
                                        <a href="{{ asset('vacaciones/' . $historial->pdf) }}" target="_blank">
                                            <img class="imagen-container" src="{{ asset('img/detalles.png') }}" alt="Abrir PDF">
                                        </a>
                                    </th>
                                    @if (session('area') != "LOGISTICA")
                                        <th class="col-1">
                                            <a class="btn btn-danger" href="#" data-toggle="modal"
                                                data-target="#eliminarhistorial{{ $historial->id_empleado }}">
                                                Eliminar
                                            </a>
                                            <!-- Modal Eliminar Solicitud-->
                                            <div class="modal fade" id="eliminarhistorial{{ $historial->id_empleado }}"
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
                                                        <div class="modal-body">Selecciona confirmar para eliminar esta
                                                            solicitud</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button"
                                                                data-dismiss="modal">cancelar</button>
                                                            <form
                                                                action="{{ route('deleteVacacionEnc', $historial->id_vacacion) }}"
                                                                method="POST">
                                                                @csrf
                                                                {!! method_field('DELETE') !!}
                                                                <button type="submit"
                                                                    class="btn btn-primary">confirmar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    @endif                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
