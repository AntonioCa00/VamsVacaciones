@extends('plantillaEncargado')

@section('contenido')
    @if (session()->has('duplicado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Ya se ha registrado ese empleado',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('numero'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Ya se ha registrado ese numero de empleado',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CREAR PERSONAL</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registrar nuevo personal</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('createPersonalEnc') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="divisionName">Numero de empleado:</label>
                        <input value="{{ old('numero') }}" name="numero" id="di
                    visionName"
                            type="number" class="form-control" placeholder="Numero de empleado asignado" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Nombre(s):</label>
                        <input value="{{ old('nombres') }}" name="nombres" id="di
                    visionName"
                            type="text" class="form-control" placeholder="Escribe los nombres" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Apellido Paterno:</label>
                        <input value="{{ old('apellidoP') }}" name="apellidoP" id="divisionName" type="text"
                            class="form-control" placeholder="Escribe el apellido paterno" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Apellido Materno:</label>
                        <input value="{{ old('apellidoM') }}" name="apellidoM" id="divisionName" type="text"
                            class="form-control" placeholder="Escribe el apellido materno" required>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Fecha de nacimiento:</label>
                        <input value="{{ old('fecha_naci') }}" name="fecha_naci" id="divisionName" type="date"
                            class="form-control" placeholder="Escribe o selecciona la fecha de ingreso" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Puesto a ocupar:</label>
                        <select name="puesto" class="form-control" required>
                            <option value="" selected disabled>Selecciona el puesto que se le asignó...</option>
                            @foreach ($puestos as $puesto)
                                <option value="{{ $puesto->id_puesto }}"
                                    {{ old('puesto') == $puesto->id_puesto ? 'selected' : '' }}>{{ $puesto->nombre }} -
                                    {{ $puesto->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="divisionName">Fecha de ingreso:</label>
                        <input value="{{ old('fecha_ingreso') }}" name="fecha_ingreso" id="divisionName" type="date"
                            class="form-control" placeholder="Escribe o selecciona la fecha de ingreso" required>
                    </div>
                    <label for="divisionName">Horario laboral:</label>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Lunes" class="btn-check" id="option1" checked>
                            <label class="btn btn-outline-success" for="option1">Lunes</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Martes" class="btn-check" id="option2" checked>
                            <label class="btn btn-outline-success" for="option2">Martes</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Miercoles" class="btn-check" id="option3"
                                checked>
                            <label class="btn btn-outline-success" for="option3">Miercoles</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Jueves" class="btn-check" id="option4" checked>
                            <label class="btn btn-outline-success" for="option4">Jueves</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Viernes" class="btn-check" id="option5"
                                checked>
                            <label class="btn btn-outline-success" for="option5">Viernes</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Sabado" class="btn-check" id="option6"
                                checked>
                            <label class="btn btn-outline-success" for="option6">Sabado</label><br>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear usuario</button>
                </form>
            </div>
        </div>
    </div>
@endsection
