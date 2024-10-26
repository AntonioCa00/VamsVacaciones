@extends('plantillaAdmin')

@section('contenido')
    @if (session()->has('duplicado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Ya se ha registrado esa área',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CREAR AREAS</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registrar nueva área</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('createArea') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="divisionName">Nombre del área:</label>
                        <input name="nombre" id="di
                    visionName" type="text" class="form-control"
                            placeholder="Nombre que se asignará al área" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Descripción del área:</label>
                        <input name="descripcion" id="divisionName" type="text" class="form-control"
                            placeholder="Describe de que se trata o a que esta enfocada el área" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">División:</label>
                        <select name="division" class="form-control" required>
                            <option value="" selected disabled>Selecciona la división a la que pertenece...</option>
                            @foreach ($divisiones as $division)
                                <option value="{{ $division->id_division }}">{{ $division->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Crear área</button>
                </form>
            </div>
        </div>
    </div>
@endsection
