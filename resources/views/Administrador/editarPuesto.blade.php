@extends('plantillaAdmin')

@section('contenido')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">EDITAR PUESTO</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar puesto</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('updatePuesto', $puesto->id_puesto) }}" method="POST">
                    @csrf
                    {!! method_field('PUT') !!}
                    <div class="form-group">
                        <label for="divisionName">Nombre del puesto:</label>
                        <input value="{{ $puesto->nombre }}" name="nombre" id="di
                    visionName"
                            type="text" class="form-control" placeholder="Nombre que se asignará al puesto" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Descripción del puesto:</label>
                        <input value="{{ $puesto->descripcion }}" name="descripcion" id="divisionName" type="text"
                            class="form-control" placeholder="Describe de que se trata o a que esta enfocado el puesto"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Area:</label>
                        <select name="area" class="form-control" required>
                            <option value="{{ $puesto->area_id }}" selected>{{ $puesto->area }} - {{ $puesto->division }}
                            </option>
                            <option value="" disabled>Selecciona el área a la que pertenece...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id_area }}">{{ $area->area }} - {{ $area->division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Editar puesto</button>
                </form>
            </div>
        </div>
    </div>
@endsection
