<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inicio de sesion</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css'
    ) }}" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gradient-primary">

    @if(session()->has('error'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Usuario no encontrado, revise sus credenciales.',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
    @endif

    @if(session()->has('contras'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Su contraseña no coincide, revisela por favor.',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
    @endif

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center" style="flex-grow: 1;">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-flex justify-content-center align-items-center" style="background-color:#2f3859 ">
                                <img src="{{asset('img/LOGOVAMS.jpg')}}" alt="Logo VAMS" style="max-width: 100%; height: auto;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                                    </div>
                                    <form class="user" method="POST" action="{{route('validate')}}">
                                        @csrf <!-- Agrega el campo CSRF token para protección -->
                                        <div class="form-group">
                                            <label for="Numero_empleado">Número de empleado:</label>
                                            <input value="{{old('numero')}}" type="text" class="form-control form-control-user" name="numero"
                                                id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Ingresa tu numero de empleado" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Contraseña">Contraseña:</label>
                                            <input type="password" class="form-control form-control-user" name="contrasena"
                                                id="exampleInputPassword" placeholder="Tu fecha de nacimiento en formato aa/mm/dd" required>
                                        </div>
                                        <div class="form-group">
                                            <!-- Otros campos si es necesario -->
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Entrar
                                        </button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
