@extends('plantillaAdmin')

@section('contenido')
    @if (session()->has('creado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha registrado la nueva área',
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
                title: 'Se ha editado la área',
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
                title: 'Se ha eliminado la área',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CONSULTA PUESTOS</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a class="btn btn-primary" href="{{ route('crearPuesto') }}">Crear puesto</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Codigo:</th>
                                <th>División:</th>
                                <th>Area:</th>
                                <th>Nombre:</th>
                                <th>Descripción:</th>
                                <th>Opciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($puestos as $puesto)
                                <tr>
                                    <th class="col-1">{{ $puesto->id_puesto }}</th>
                                    <th>{{ $puesto->division }}</th>
                                    <th>{{ $puesto->area }}</th>
                                    <th class="col-2">{{ $puesto->nombre }}</th>
                                    <th class="col-3">{{ $puesto->descripcion }}</th>
                                    <th class="col-3">
                                        <a href="{{ route('editarPuesto', $puesto->id_puesto) }}"
                                            class="btn btn-success">Editar</a>
                                        <a class="btn btn-danger" href="#" data-toggle="modal"
                                            data-target="#eliminarArea{{ $puesto->id_puesto }}">
                                            Eliminar
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="eliminarArea{{ $puesto->id_puesto }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                    <div class="modal-body">Selecciona confirmar para eliminar este puesto
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">cancelar</button>
                                                        <form action="{{ route('deletePuesto', $puesto->id_puesto) }}"
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
