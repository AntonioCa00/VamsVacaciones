@extends('plantillaAdmin')

@section('contenido')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">EDITAR DIVISION</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar división</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('updateDivision', $division->id_division) }}" method="POST">
                    @csrf
                    {!! method_field('PUT') !!}
                    <div class="form-group">
                        <label for="divisionName">Nombre de la división:</label>
                        <input value="{{ $division->nombre }}" name="nombre" id="divisionName" type="text"
                            class="form-control" placeholder="Nombre que se asignará a la división" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Descripción de la división:</label>
                        <input value="{{ $division->descripcion }}" name="descripcion" id="divisionName" type="text"
                            class="form-control" placeholder="Describe de que se trata o a que esta enfocada la división"
                            required>
                    </div>
                    <button class="btn btn-primary">Editar division</button>
                </form>
            </div>
        </div>
    </div>
@endsection
