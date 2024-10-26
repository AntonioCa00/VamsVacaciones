@extends('plantillaAdmin')

@section('contenido')
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
        <h1 class="h3 mb-2 text-gray-800">EDITAR PERSONAL</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar personal</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('updatePersonal', $persona->id_empleado) }}" method="POST">
                    @csrf
                    {!! method_field('PUT') !!}

                    <div class="form-group">
                        <label for="numero">Numero de empleado:</label>
                        <input value="{{ old('numero', $persona->numero_empleado) }}" name="numero" id="numero"
                            type="text" class="form-control" placeholder="Numero de empleado asignado al personal"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="nombres">Nombre(s):</label>
                        <input value="{{ old('nombres', $persona->nombres) }}" name="nombres" id="nombres" type="text"
                            class="form-control" placeholder="Escribe los nombres del personal" required>
                    </div>

                    <div class="form-group">
                        <label for="apellidoP">Apellido Paterno:</label>
                        <input value="{{ old('apellidoP', $persona->apellido_paterno) }}" name="apellidoP" id="apellidoP"
                            type="text" class="form-control" placeholder="Escribe el apellido paterno" required>
                    </div>

                    <div class="form-group">
                        <label for="apellidoM">Apellido Materno:</label>
                        <input value="{{ old('apellidoM', $persona->apellido_materno) }}" name="apellidoM" id="apellidoM"
                            type="text" class="form-control" placeholder="Escribe el apellido materno" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_naci">Fecha de nacimiento:</label>
                        <input value="{{ old('fecha_naci', $persona->fecha_nacimiento) }}" name="fecha_naci" id="fecha_naci"
                            type="date" class="form-control" placeholder="Escribe o selecciona la fecha de nacimiento"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="puesto">Puesto a ocupar:</label>
                        <select name="puesto" class="form-control" required>
                            <option value="" disabled>Selecciona el puesto que se le asignó...</option>
                            @foreach ($puestos as $puesto)
                                <option value="{{ $puesto->id_puesto }}"
                                    {{ old('puesto', $persona->puesto_id) == $puesto->id_puesto ? 'selected' : '' }}>
                                    {{ $puesto->nombre }} - {{ $puesto->area }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha de ingreso:</label>
                        <input value="{{ old('fecha_ingreso', $persona->fecha_ingreso) }}" name="fecha_ingreso"
                            id="fecha_ingreso" type="date" class="form-control"
                            placeholder="Escribe o selecciona la fecha de ingreso" required>
                    </div>
                    @php
                        // Separar los días en un array
                        $diasSeleccionados = explode(' / ', $persona->horario);
                    @endphp
                    <div class="form-group">
                        HORARIO:
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Lunes" class="btn-check" id="option1"
                                {{ in_array('Lunes', $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="option1">Lunes</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Martes" class="btn-check" id="option2"
                                {{ in_array('Martes', $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="option2">Martes</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Miercoles" class="btn-check" id="option3"
                                {{ in_array('Miercoles', $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="option3">Miercoles</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Jueves" class="btn-check" id="option4"
                                {{ in_array('Jueves', $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="option4">Jueves</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Viernes" class="btn-check" id="option5"
                                {{ in_array('Viernes', $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="option5">Viernes</label><br>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="dias[]" type="checkbox" value="Sabado" class="btn-check" id="option6"
                                {{ in_array('Sabado', $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="option6">Sabado</label><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol del usuario:</label>
                        <select name="rol" class="form-control" id="rol" required>
                            <option value="" disabled>Selecciona el rol que se le asignará al usuario</option>
                            <option value="Administrador"
                                {{ old('rol', $persona->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="Encargado" {{ old('rol', $persona->rol) == 'Encargado' ? 'selected' : '' }}>
                                Encargado de recursos humanos</option>
                            <option value="General" {{ old('rol', $persona->rol) == 'General' ? 'selected' : '' }}>Usuario
                                General</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar información</button>
                </form>
            </div>
        </div>
    </div>
@endsection
