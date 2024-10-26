@extends('plantillaEmpleado')

@section('contenido')
    @if (session()->has('entra'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Bienvenido a control vacaciones VAMS.',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">MI PERFIL</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="card mb-3" style="max-width: 100%;">
                    <div class="card-header">DATOS PERSONALES</div>
                    <div class="card-body text-dark">
                        <div class="mb-2" style="display: flex; align-items: center;">
                            <p class="card-text"><strong>Nombre completo: </strong> {{ $personal->nombres }}
                                {{ $personal->apellido_paterno }} {{ $personal->apellido_materno }}</p>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <p class="card-text"><strong>Fecha de nacimiento: </strong> {{ $personal->fecha_nacimiento }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card mb-3" style="max-width: 100%;">
                    <div class="card-header">DATOS LABORALES</div>
                    <div class="card-body text-dark">
                        <div class="mb-2" style="display: flex; align-items: center;">
                            <p class="card-text"><strong>Puesto: </strong> {{ $personal->puesto }}</p>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <p class="card-text"><strong>Fecha de ingreso: </strong> {{ $personal->fecha_ingreso }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
