@extends('plantillaAdmin')

@section('contenido')
    @if (session()->has('creado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha registrado la nueva división',
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
                title: 'Se ha editado la división',
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
                title: 'Se ha eliminado la división',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CONSULTA DIVISIONES</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a class="btn btn-primary" href="{{ route('crearDivision') }}">Crear división</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Codigo:</th>
                                <th>Nombre:</th>
                                <th>Descripción:</th>
                                <th>Opciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($divisiones as $division)
                                <tr>
                                    <th class="col-1">{{ $division->id_division }}</th>
                                    <th class="col-3">{{ $division->nombre }}</th>
                                    <th>{{ $division->descripcion }}</th>
                                    <th class="col-3">
                                        <a href="{{ route('editarDivision', $division->id_division) }}"
                                            class="btn btn-success">Editar</a>
                                        <a class="btn btn-danger" href="#" data-toggle="modal"
                                            data-target="#eliminarDiv{{ $division->id_division }}">
                                            Eliminar
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="eliminarDiv{{ $division->id_division }}" tabindex="-1"
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
                                                    <div class="modal-body">Selecciona confirmar para eliminar esta división
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">cancelar</button>
                                                        <form action="{{ route('deleteDivision', $division->id_division) }}"
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
