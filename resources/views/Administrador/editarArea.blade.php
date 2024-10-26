@extends('plantillaAdmin')

@section('contenido')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">EDITAR AREA</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar área</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('updateArea', $area->id_area) }}" method="POST">
                    @csrf
                    {!! method_field('PUT') !!}
                    <div class="form-group">
                        <label for="divisionName">Nombre del área:</label>
                        <input name="nombre" value="{{ $area->nombre }}" id="di
                    visionName"
                            type="text" class="form-control" placeholder="Nombre que se asignará al área" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Descripción del área:</label>
                        <input name="descripcion" value="{{ $area->descripcion }}" id="divisionName" type="text"
                            class="form-control" placeholder="Describe de que se trata o a que esta enfocada el área"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">División:</label>
                        <select name="division" class="form-control" required>
                            <option value="{{ $area->division_id }}" selected>{{ $area->division }}</option>
                            <option value="" disabled>Selecciona la división a la que pertenece...</option>
                            @foreach ($divisiones as $division)
                                <option value="{{ $division->id_division }}">{{ $division->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Editar área</button>
                </form>
            </div>
        </div>
    </div>
@endsection
