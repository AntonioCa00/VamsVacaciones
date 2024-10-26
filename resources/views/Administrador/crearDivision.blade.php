@extends('plantillaAdmin')

@section('contenido')
    @if (session()->has('duplicado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Ya se ha registrado esa división',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CREAR DIVISIONES</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registrar nueva division</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('createDivision') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="divisionName">Nombre de la división:</label>
                        <input name="nombre" id="divisionName" type="text" class="form-control"
                            placeholder="Nombre que se asignará a la división" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Descripción de la división:</label>
                        <input name="descripcion" id="divisionName" type="text" class="form-control"
                            placeholder="Describe de que se trata o a que esta enfocada la división" required>
                    </div>
                    <button class="btn btn-primary">Crear division</button>
                </form>
            </div>
        </div>
    </div>
@endsection
