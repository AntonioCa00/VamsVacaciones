<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use App\Models\leyesVacaciones;
use App\Models\tablasVacaciones;
use App\Models\Vacaciones;
use App\Models\horarios;
use App\Models\Puestos;
use Carbon\Carbon;
use GuzzleHttp\Client;

//-------PHPOFFICE---------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class controladorEncargado extends Controller
{
    public function index(){

    $user = Empleados::where('id_empleado', session('loginId'))->first();

    // Crear una instancia de Carbon a partir de la variable de fecha
    $fecha = Carbon::createFromFormat('Y-m-d', $user->fecha_ingreso);
    // Obtener el año
    $anio = $fecha->year;

    switch (true) {
        case $anio >= 2023:

            // Calcular la diferencia en días entre la fecha de ingreso y la fecha actual
            $diferencia = Carbon::now()->diffInDays($user->fecha_ingreso);
            // Convertir la diferencia de días a años en formato decimal
            $antiguedad = round($diferencia / 365, 5);

            // Buscar si la antigüedad está entre ingreso y término en la tabla 'tablas_Vacaciones'
            $registro = tablasVacaciones::where('ley_id', 2)
                ->where('ingreso', '<=', $antiguedad)  // Comparar antigüedad con el valor de 'ingreso'
                ->where('termino', '>=', $antiguedad)  // Comparar antigüedad con el valor de 'termino'
                ->first();

            $dias_tomados = vacaciones::where('empleado_id', session('loginId'))
                ->sum('dias_tomados');

            // Agregar a la sesión
            session()->put('dias_tomados', $dias_tomados);

            $dias_disponibles = $registro->acumulado - $dias_tomados;

            // Agregar a la sesión
            session()->put('dias_disponibles', $dias_disponibles);
            break;

            case $anio < 2023:
                // Convierte fecha de finalización en instancia de carbon
                $fechaCarbon = Carbon::parse('2022-12-31'); // Esta es la fecha de finalización
                $fechaIngreso = Carbon::parse($user->fecha_ingreso);
                // Calcular la diferencia en días entre la fecha de ingreso y la fecha de la primer ley
                $diferencia1 = $fechaCarbon->diffInDays($user->fecha_ingreso);
                // Convertir la diferencia de días a años en formato decimal
                $antiguedad1 = round($diferencia1 / 365, 5);
                $dias1 = $antiguedad1 * 6;

                // Calcular segundo corte
                $fechaSegundo = Carbon::parse('2023-01-01'); // Esta es la fecha de inicio de la segunda ley
                $fechaAnio = $fechaIngreso->addYear(); // Esta será la fecha un año

                // Calcular la diferencia en días entre la fecha de ingreso y la nueva fecha
                $diferencia2 = $fechaSegundo->diffInDays($fechaAnio);

                // Convertir la diferencia de días a años en formato decimal
                $antiguedad2 = round($diferencia2 / 365, 6);
                $dias2 = $antiguedad2 * 12;

                // Calcular la diferencia en días entre la fecha de ingreso y la fecha actual
                $diferencia3 = Carbon::now()->diffInDays($user->fecha_ingreso);
                // Convertir la diferencia de días a años en formato decimal
                $antiguedad3 = round($diferencia3 / 365, 6);

                // Buscar si la antigüedad está entre ingreso y término en la tabla 'tablas_Vacaciones'
                $dias3 = tablasVacaciones::where('ley_id', 2)
                ->where('ingreso', '<=', $antiguedad3)  // Comparar antigüedad con el valor de 'ingreso'
                ->where('termino', '>=', $antiguedad3)  // Comparar antigüedad con el valor de 'termino'
                ->first();

                $acumulado = round($dias1+$dias2+($dias3->acumulado-12));
                $dias_tomados = vacaciones::where('empleado_id', session('loginId'))
                ->sum('dias_tomados');

                // Agregar a la sesión
                session()->put('dias_tomados', $dias_tomados);

                $dias_disponibles = $acumulado - $dias_tomados;

                // Agregar a la sesión
                session()->put('dias_disponibles', $dias_disponibles);
                break;

    }
        return view('Encargado.index');
    }

    public function permisos () {
        return view('Encargado.panelSolicitudes');
    }

    public function durante (){
        return view('Encargado.crearDurante');
    }

    public function createDurante (Request $req){

        //Define el tipo de permiso que se esta solicitando
        $permiso = 'Durante';

        //Formatea los request a Carbon
        $fecha = Carbon::parse($req->fecha);
        $desde = Carbon::parse($req->desde);
        $hasta = Carbon::parse($req->hasta);

        //Almacena el motivo en variable
        $motivo = $req->motivo;

        // Formatear la fecha como "dd/MES/aaaa"
        $fechaFormateada = $fecha->format('d/M/Y'); // Esto te dará algo como "01/OCT/2024"

        //Meses en españo
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fechaFormateadaEsp = $fecha->translatedFormat('d/M/Y');

        // Obtener la diferencia en horas y minutos exactos
        $diff = $hasta->diff($desde);

        // Convertir a formato de 12 horas con AM/PM
        $inicio = $desde->format('g:i A'); // g:i A da 12 horas con minutos y AM/PM
        $fin= $hasta->format('g:i A');
        $duracion = $diff->h . ' hrs ' . $diff->i . ' min';

        // Crear un arreglo asociativo con las variables
        $data = [
            'fecha' => $fechaFormateadaEsp,
            'inicio' => $inicio,
            'fin' => $fin,
            'duracion' => $duracion,
            'motivo'=>$motivo
        ];
        // Obtener la fecha actual
        $fechaHoy = Carbon::now();

        // Formatear la fecha como "dd/MES/aaaa"
        $fechaHoyF = $fechaHoy->format('d/M/Y'); // Est1o te da la fecha en formato string

        // Si prefieres los nombres de los meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer el locale para español
        $fechaHoyFEsp = $fechaHoy->translatedFormat('d/M/Y'); // Aquí está la corrección

        $datosEmpleado = [
            'empleadoN'=>session('empleadoN'),
            'nombres' => session('loginNombres'),
            'fecha'=>$fechaHoyFEsp,
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'puesto'=>session('puesto'),
            'area'=>session('area'),

        ];

        // Incluir el archivo FormatoMultiple.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/FormatoMultiple.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }

    public function ausentarse(){
        return view('Encargado.crearAusentarse');
    }

    public function createAusentarse(Request $req){

        //Define el tipo de permiso que se esta solicitando
        $permiso = 'Ausentarse';

        //Formatea los request a Carbon
        $desde = Carbon::parse($req->desde);
        $hasta = Carbon::parse($req->hasta);
        $fechaHoy = Carbon::now();
        $tipo = $req->tipo;
        $motivo = $req->motivo;

        //Meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

        //Meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

        $dias = horarios::select('horarios.nombre as horario')
        ->join('empleados', 'horarios.id_horario', 'empleados.horario_id')
        ->where('empleados.id_empleado', session('loginId'))->first();

        // Separar los días en un array
        $diasLaborales = explode(' / ', $dias->horario);

        // Mapear los nombres de los días a los valores de Carbon
        $mapDias = [
            'Lunes' => Carbon::MONDAY,
            'Martes' => Carbon::TUESDAY,
            'Miercoles' => Carbon::WEDNESDAY,
            'Jueves' => Carbon::THURSDAY,
            'Viernes' => Carbon::FRIDAY,
            'Sabado' => Carbon::SATURDAY,
            'Domingo' => Carbon::SUNDAY
        ];

        // Crear un array con los valores numéricos correspondientes a los días de trabajo
        $diasLaboralesNumeros = array_map(function($dia) use ($mapDias) {
            return $mapDias[$dia];
        }, $diasLaborales);

        // Obtener los días festivos de México
        $year = $desde->year; // Obtener el año de la fecha inicial
        $yearF = $hasta->year;
        $diasFestivos = $this->obtenerDiasFestivos($year,$yearF);

        // Calcular la duración en días laborales excluyendo festivos
        $duracion = 0;
        for ($date = $desde->copy(); $date->lte($hasta); $date->addDay()) {
            // Verificar si el día es un día laboral y no un día festivo
            if (in_array($date->dayOfWeek, $diasLaboralesNumeros) && !in_array($date->toDateString(), $diasFestivos)) {
                $duracion++;
            }
        }

        // Crear un arreglo asociativo con las variables
        $data = [
            'inicio' => $fechaDesdeEsp,
            'fin' => $fechaHastaEsp,
            'tipo'=>$tipo,
            'duracion' => $duracion,
            'motivo'=>$motivo
        ];

        // Si prefieres los nombres de los meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer el locale para español
        $fechaHoyEsp = $fechaHoy->translatedFormat('d/M/Y'); // Aquí está la corrección

        $datosEmpleado = [
            'empleadoN'=>session('empleadoN'),
            'nombres' => session('loginNombres'),
            'fecha'=>$fechaHoyEsp,
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'puesto'=>session('puesto'),
            'area'=>session('area'),
        ];

        // Incluir el archivo FormatoMultiple.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/FormatoMultiple.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;

    }

    public function vacaciones (){
        return view('Encargado.crearVacacion');
    }

    public function createVacaciones(Request $req){
        //Define el tipo de permiso que se esta solicitando
        $permiso = 'Vacaciones';

        //Formatea los request a Carbon
        $desde = Carbon::parse($req->desde);
        $hasta = Carbon::parse($req->hasta);

        $dias = horarios::select('horarios.nombre as horario')
        ->join('empleados', 'horarios.id_horario', 'empleados.horario_id')
        ->where('empleados.id_empleado', session('loginId'))->first();

        // Separar los días en un array
        $diasLaborales = explode(' / ', $dias->horario);

        // Mapear los nombres de los días a los valores de Carbon
        $mapDias = [
            'Lunes' => Carbon::MONDAY,
            'Martes' => Carbon::TUESDAY,
            'Miercoles' => Carbon::WEDNESDAY,
            'Jueves' => Carbon::THURSDAY,
            'Viernes' => Carbon::FRIDAY,
            'Sabado' => Carbon::SATURDAY,
            'Domingo' => Carbon::SUNDAY
        ];

        // Crear un array con los valores numéricos correspondientes a los días de trabajo
        $diasLaboralesNumeros = array_map(function($dia) use ($mapDias) {
            return $mapDias[$dia];
        }, $diasLaborales);

        // Obtener los días festivos de México
        $year = $desde->year; // Obtener el año de la fecha inicial
        $yearF = $hasta->year;
        $diasFestivos = $this->obtenerDiasFestivos($year,$yearF);

        // Calcular la duración en días laborales excluyendo festivos
        $duracion = 0;
        for ($date = $desde->copy(); $date->lte($hasta); $date->addDay()) {
            // Verificar si el día es un día laboral y no un día festivo
            if (in_array($date->dayOfWeek, $diasLaboralesNumeros) && !in_array($date->toDateString(), $diasFestivos)) {
                $duracion++;
            }
        }

        if ($duracion <= session('dias_disponibles')){

            $fechaHoy = Carbon::now();
            $motivo = $req->motivo;

            //Meses en español
            Carbon::setLocale('es_MX');$fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

            //Meses en español
            Carbon::setLocale('es_MX');$fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

            $restantes = session('dias_disponibles')-$duracion;

            // Crear un arreglo asociativo con las variables
            $data = [
                'inicio' => $fechaDesdeEsp,
                'fin' => $fechaHastaEsp,
                'duracion' => $duracion,
                'motivo'=>$motivo,
                'restante'=>$restantes
            ];

            // Si prefieres los nombres de los meses en español
            setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer el locale para español
            $fechaHoyEsp = $fechaHoy->translatedFormat('d/M/Y'); // Aquí está la corrección

            $datosEmpleado = [
                'empleadoN'=>session('empleadoN'),
                'nombres' => session('loginNombres'),
                'fecha'=>$fechaHoyEsp,
                'apellidoP' => session('loginApepat'),
                'apellidoM' => session('loginApemat'),
                'puesto'=>session('puesto'),
                'area'=>session('area'),
            ];

            // Determinación del ID de la nueva requisición y preparación del PDF
            $ultimaSolicitud = vacaciones::select('id_vacacion')->latest('id_vacacion')->first();
            if (empty($ultimaSolicitud)){
                $idcorresponde = 1;
            } else {
                $idcorresponde = $ultimaSolicitud->id_vacacion + 1;
            }

            // Se genera el nombre y ruta para guardar PDF
            $nombreArchivo = 'vacaciones_' . $idcorresponde . '.pdf';
            $rutaDescargas = 'vacaciones/' . $nombreArchivo;

            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); //* Iniciar el búfer de salida para pasar las variables al PDF
            include(public_path('/pdf/TCPDF-main/examples/FormatoMultiple.php'));
            ob_end_clean();

            vacaciones::create([
                "id_vacacion"=>$idcorresponde,
                "fecha_inicio"=>$desde,
                "fecha_fin"=>$hasta,
                "dias_tomados"=>$duracion,
                "observaciones"=>$motivo,
                "pdf"=>$rutaDescargas,
                "estatus"=>'0',
                "empleado_id"=>session('loginId'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now(),
            ]);

            return redirect('historial/Encargado')->with('creado','creado');

        } else{
            return back()->with('insuficiente','insuficiente')->withInput();
        }
    }

    public function tableHistorial() {
        // Obtener el historial de vacaciones
        $historialVac = vacaciones::where('empleado_id', session('loginId'))->get();

        foreach ($historialVac as $historial) {
            // Convertir la fecha de inicio a una instancia de Carbon y formatear solo la fecha como dd/MM/yyyy
            $fechaDesdeEsp = Carbon::parse($historial->fecha_inicio)->translatedFormat('d/M/Y');
            $historial->inicio = $fechaDesdeEsp;

            // Convertir la fecha de fin a una instancia de Carbon y formatear solo la fecha como dd/MM/yyyy
            $fechaHastaEsp = Carbon::parse($historial->fecha_fin)->translatedFormat('d/M/Y');
            $historial->fin = $fechaHastaEsp;

            // Primero, convertir a una instancia de Carbon y formatear a 'Y-m-d'
            $fechaCreated = date('d/m/Y', strtotime($historial->created_at));
            // Luego, convertir a una instancia de Carbon y formatear a 'd/M/Y'
            $fechaCreatedEsp = Carbon::parse($historial->created_at)->translatedFormat('d/M/Y');

            $historial->fecha_creacion = $fechaCreatedEsp;
        }

        // Retornar la vista con la variable compactada
        return view('Encargado.historialVacaciones', compact('historialVac'));
    }

    public function updateVacacion(Request $req, $id){
        //Define el tipo de permiso que se esta solicitando
        $permiso = 'Vacaciones';

        //Formatea los request a Carbon
        $desde = Carbon::parse($req->desde);
        $hasta = Carbon::parse($req->hasta);

        $dias = horarios::select('horarios.nombre as horario')
        ->join('empleados', 'horarios.id_horario', 'empleados.horario_id')
        ->where('empleados.id_empleado', session('loginId'))->first();

        // Separar los días en un array
        $diasLaborales = explode(' / ', $dias->horario);

        // Mapear los nombres de los días a los valores de Carbon
        $mapDias = [
            'Lunes' => Carbon::MONDAY,
            'Martes' => Carbon::TUESDAY,
            'Miercoles' => Carbon::WEDNESDAY,
            'Jueves' => Carbon::THURSDAY,
            'Viernes' => Carbon::FRIDAY,
            'Sabado' => Carbon::SATURDAY,
            'Domingo' => Carbon::SUNDAY
        ];

        // Crear un array con los valores numéricos correspondientes a los días de trabajo
        $diasLaboralesNumeros = array_map(function($dia) use ($mapDias) {
            return $mapDias[$dia];
        }, $diasLaborales);

        // Obtener los días festivos de México
        $year = $desde->year; // Obtener el año de la fecha inicial
        $yearF = $hasta->year;
        $diasFestivos = $this->obtenerDiasFestivos($year,$yearF);

        // Calcular la duración en días laborales excluyendo festivos
        $duracion = 0;
        for ($date = $desde->copy(); $date->lte($hasta); $date->addDay()) {
            // Verificar si el día es un día laboral y no un día festivo
            if (in_array($date->dayOfWeek, $diasLaboralesNumeros) && !in_array($date->toDateString(), $diasFestivos)) {
                $duracion++;
            }
        }

        if ($duracion <= session('dias_disponibles')){

            $fechaHoy = Carbon::now();
            $motivo = $req->motivo;

            //Meses en español
            Carbon::setLocale('es_MX');$fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

            //Meses en español
            Carbon::setLocale('es_MX');$fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

            $restantes = session('dias_disponibles')-$duracion;

            // Crear un arreglo asociativo con las variables
            $data = [
                'inicio' => $fechaDesdeEsp,
                'fin' => $fechaHastaEsp,
                'duracion' => $duracion,
                'motivo'=>$motivo,
                'restante'=>$restantes
            ];

            // Si prefieres los nombres de los meses en español
            setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer el locale para español
            $fechaHoyEsp = $fechaHoy->translatedFormat('d/M/Y'); // Aquí está la corrección

            $datosEmpleado = [
                'empleadoN'=>session('empleadoN'),
                'nombres' => session('loginNombres'),
                'fecha'=>$fechaHoyEsp,
                'apellidoP' => session('loginApepat'),
                'apellidoM' => session('loginApemat'),
                'puesto'=>session('puesto'),
                'area'=>session('area'),
            ];

            $idcorresponde = $id;

            // Se genera el nombre y ruta para guardar PDF
            $nombreArchivo = 'vacaciones_' . $idcorresponde . '.pdf';
            $rutaDescargas = 'vacaciones/' . $nombreArchivo;


            $fileToDelete = public_path($rutaDescargas);
            // Luego, verifica si el archivo realmente existe antes de intentar eliminarlo.
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }

            // Incluir el archivo FormatoMultiple.php y pasar la ruta del archivo como una variable
            ob_start(); //* Iniciar el búfer de salida para pasar las variables al PDF
            include(public_path('/pdf/TCPDF-main/examples/FormatoMultiple.php'));
            ob_end_clean();

            vacaciones::where('id_vacacion',$id)->update([
                "fecha_inicio"=>$desde,
                "fecha_fin"=>$hasta,
                "dias_tomados"=>$duracion,
                "observaciones"=>$motivo,
                "pdf"=>$rutaDescargas,
                "estatus"=>'0',
                "updated_at"=>Carbon::now(),
            ]);

            return redirect('historial/Encargado')->with('editado','editado');

        } else{
            return back()->with('insuficiente','insuficiente')->withInput();
        }
    }

    public function deleteVacacion($id){
        $vacacion = vacaciones::where('id_vacacion',$id)->first();

        $fileToDelete = public_path($vacacion->pdf);
        // Luego, verifica si el archivo realmente existe antes de intentar eliminarlo.
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        vacaciones::where('id_vacacion',$id)->delete();

        return back()->with('eliminado','eliminado');
    }

    public function aprobarVacaciones (Request $req){
        //Define el tipo de permiso que se esta solicitando
        $permiso = 'Vacaciones';

        //Formatea los request a Carbon
        $id = $req->eventId;
        $desde = Carbon::parse($req->desde);
        $hasta = Carbon::parse($req->hasta);

        $dias = horarios::select('horarios.nombre as horario')
        ->join('empleados', 'horarios.id_horario', 'empleados.horario_id')
        ->where('empleados.id_empleado', session('loginId'))->first();

        // Separar los días en un array
        $diasLaborales = explode(' / ', $dias->horario);

        // Mapear los nombres de los días a los valores de Carbon
        $mapDias = [
            'Lunes' => Carbon::MONDAY,
            'Martes' => Carbon::TUESDAY,
            'Miercoles' => Carbon::WEDNESDAY,
            'Jueves' => Carbon::THURSDAY,
            'Viernes' => Carbon::FRIDAY,
            'Sabado' => Carbon::SATURDAY,
            'Domingo' => Carbon::SUNDAY
        ];

        // Crear un array con los valores numéricos correspondientes a los días de trabajo
        $diasLaboralesNumeros = array_map(function($dia) use ($mapDias) {
            return $mapDias[$dia];
        }, $diasLaborales);

        // Obtener los días festivos de México
        $year = $desde->year; // Obtener el año de la fecha inicial
        $yearF = $hasta->year;
        $diasFestivos = $this->obtenerDiasFestivos($year,$yearF);

        // Calcular la duración en días laborales excluyendo festivos
        $duracion = 0;
        for ($date = $desde->copy(); $date->lte($hasta); $date->addDay()) {
            // Verificar si el día es un día laboral y no un día festivo
            if (in_array($date->dayOfWeek, $diasLaboralesNumeros) && !in_array($date->toDateString(), $diasFestivos)) {
                $duracion++;
            }
        }

        $fechaHoy = Carbon::now();
        $motivo = $req->motivo;

        //Meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

        //Meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

        $restantes = session('dias_disponibles')-$duracion;

        // Crear un arreglo asociativo con las variables
        $data = [
            'inicio' => $fechaDesdeEsp,
            'fin' => $fechaHastaEsp,
            'duracion' => $duracion,
            'motivo'=>$motivo,
            'restante'=>$restantes
        ];

        // Si prefieres los nombres de los meses en español
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer el locale para español
        $fechaHoyEsp = $fechaHoy->translatedFormat('d/M/Y'); // Aquí está la corrección

        $datosEmpleado = [
            'empleadoN'=>session('empleadoN'),
            'nombres' => session('loginNombres'),
            'fecha'=>$fechaHoyEsp,
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'puesto'=>session('puesto'),
            'area'=>session('area'),
        ];

        $idcorresponde = $id;

        // Se genera el nombre y ruta para guardar PDF
        $nombreArchivo = 'vacaciones_' . $idcorresponde . '.pdf';
        $rutaDescargas = 'vacaciones/' . $nombreArchivo;


        $fileToDelete = public_path($rutaDescargas);
        // Luego, verifica si el archivo realmente existe antes de intentar eliminarlo.
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        // Incluir el archivo FormatoMultiple.php y pasar la ruta del archivo como una variable
        ob_start(); //* Iniciar el búfer de salida para pasar las variables al PDF
        include(public_path('/pdf/TCPDF-main/examples/FormatoMultiple.php'));
        ob_end_clean();

        vacaciones::where('id_vacacion',$id)->update([
            "fecha_inicio"=>$desde,
            "fecha_fin"=>$hasta,
            "dias_tomados"=>$duracion,
            "pdf"=>$rutaDescargas,
            "estatus"=>'1',
            "updated_at"=>Carbon::now(),
        ]);

        return redirect('consultar/vacaciones')->with('aprobado','aprobado');
    }

    public function calendario(){
        // Obtener las programaciones activas o en reprogramación y mapear los datos para el calendario de eventos
        $events = vacaciones::select('vacaciones.id_vacacion', 'empleados.nombres', 'empleados.apellido_paterno', 'empleados.apellido_materno', 'vacaciones.fecha_inicio', 'vacaciones.fecha_fin', 'vacaciones.estatus')
            ->join('empleados', 'vacaciones.empleado_id', 'empleados.id_empleado')
            ->get()->map(function ($event) {
                $title = $event->nombres . ' ' . $event->apellido_paterno . ' ' . $event->apellido_materno;
                // Asignar colores según el estatus
                $color = $event->estatus == '0' ? '#587dd2 ' : '#18bb7d ';

                return [
                    'id' => $event->id_vacacion,
                    'title' => $title,
                    'start' => $event->fecha_inicio,
                    'end' => \Carbon\Carbon::parse($event->fecha_fin)->addDay()->toDateString(),
                    'originalEnd' => $event->fecha_fin, // Agregar la fecha de fin original
                    'status' => $event->estatus,
                    'color' => $color, // Agrega el color aquí
                ];
            });
        return view('Encargado.calendario',compact('events'));
    }

    public function getEvents(){
        // Obtener las programaciones activas o en reprogramación y mapear los datos para el calendario de eventos
        $events = vacaciones::select('vacaciones.id_vacacion', 'empleados.nombres', 'empleados.apellido_paterno', 'empleados.apellido_materno', 'vacaciones.fecha_inicio', 'vacaciones.fecha_fin', 'vacaciones.estatus')
            ->join('empleados', 'vacaciones.empleado_id', 'empleados.id_empleado')
            ->get()->map(function ($event) {
                $title = $event->nombres . ' ' . $event->apellido_paterno . ' ' . $event->apellido_materno;
                // Asignar colores según el estatus
                $color = $event->estatus == '0' ? '#587dd2 ' : '#18bb7d';

                return [
                    'id' => $event->id_vacacion,
                    'title' => $title,
                    'start' => $event->fecha_inicio,
                    'end' => \Carbon\Carbon::parse($event->fecha_fin)->addDay()->toDateString(),
                    'originalEnd' => $event->fecha_fin, // Agregar la fecha de fin original
                    'status' => $event->estatus,
                    'color' => $color, // Agrega el color aquí
                ];
            });

        // Devolver la respuesta JSON con los eventos
        return response()->json($events);
    }

    public function tablePersonal(){
        $personal = Empleados::select('empleados.*','puestos.nombre as puesto','horarios.nombre as horario')
        ->join('horarios', 'empleados.horario_id','horarios.id_horario')
        ->join('puestos','empleados.puesto_id','puestos.id_puesto')
        ->where('empleados.estatus','1')
        ->get();

        return view('Encargado.personal',compact('personal'));
    }

    public function crearPersonal (){
        $puestos = Puestos::select('puestos.id_puesto','puestos.nombre','areas.nombre as area')
        ->join('areas','puestos.area_id','areas.id_area')
        ->where('puestos.estatus','1')
        ->get();

        return view('Encargado.crearPersonal',compact('puestos'));
    }

    public function createPersonal (Request $req){
        $personal = Empleados::whereRaw('UPPER(nombres) = ?', [strtoupper($req->nombres)])
        ->whereRaw('UPPER(apellido_paterno) = ?', [strtoupper($req->apellidoP)])
        ->whereRaw('UPPER(apellido_materno) = ?', [strtoupper($req->apellidoM)])
        ->first();

        $n_empleado = Empleados::where('numero_empleado',$req->numero)->first();

        $contrasena = $this->formatearFecha($req->fecha_naci);

        $rol = 'General';
        // Procesar los datos si son válidos
        $dias = $req->input('dias');

        // Concatenar los departamentos en una sola cadena
        $dia = implode(' / ', $dias);

        $horario = horarios::where('nombre', $dia)->first();

        if(empty($horario)){
            horarios::create([
                "nombre"=>$dia,
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            $horarioFinal = horarios::latest()->first();
        } else {
            $horarioFinal = $horario;
        }

        if(empty($personal)){
            if (empty($n_empleado) || $n_empleado->estatus ==='0' ){
                Empleados::create([
                    "numero_empleado"=>$req->numero,
                    "contrasena"=>$contrasena,
                    "nombres"=>$req->nombres,
                    "apellido_paterno"=>$req->apellidoP,
                    "apellido_materno"=>$req->apellidoM,
                    "fecha_nacimiento"=>$req->fecha_naci,
                    "puesto_id"=>$req->puesto,
                    "fecha_ingreso"=>$req->fecha_ingreso,
                    "horario_id"=>$horarioFinal->id_horario,
                    "rol"=>$rol,
                    "estatus"=>"1",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now(),
                ]);
            } else {
                return back()->with('numero','numero')->withInput();
            }
        } elseif ($personal->estatus === '0'){
            if (empty($n_empleado)){
                Empleados::create([
                    "numero_empleado"=>$req->numero,
                    "contrasena"=>$contrasena,
                    "nombres"=>$req->nombres,
                    "apellido_paterno"=>$req->apellidoP,
                    "apellido_materno"=>$req->apellidoM,
                    "fecha_nacimiento"=>$req->fecha_naci,
                    "puesto_id"=>$req->puesto,
                    "fecha_ingreso"=>$req->fecha_ingreso,
                    "horario_id"=>$horarioFinal->id_horario,
                    "rol"=>$rol,
                    "estatus"=>"1",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now(),
                ]);
            } else {
                return back()->with('numero','numero')->withInput();
            }
        } elseif ($personal->estatus === '1'){
            return back()->with('duplicado','duplicado');
        }

        return redirect('personal/Encargado') ->with('creado','creado');
    }

    public function editarPersonal($id){
        $persona = Empleados::select('empleados.*','horarios.nombre as horario')
        ->join('horarios','empleados.horario_id','horarios.id_horario')
        ->where('id_empleado',$id)->first();

        $puestos = Puestos::select('puestos.id_puesto','puestos.nombre','areas.nombre as area')
        ->join('areas','puestos.area_id','areas.id_area')
        ->where('puestos.estatus','1')
        ->get();

        return view('Encargado.editarPersonal',compact('persona','puestos'));
    }

    public function updatePersonal(Request $req, $id){
        $n_empleado = Empleados::where('numero_empleado',$req->numero)
        ->where('id_empleado','!=',$id)
        ->first();

        $contrasena = $this->formatearFecha($req->fecha_naci);

        // Procesar los datos si son válidos
        $dias = $req->input('dias');

        // Concatenar los departamentos en una sola cadena
        $dia = implode(' / ', $dias);

        $horario = horarios::where('nombre', $dia)->first();

        if(empty($horario)){
            horarios::create([
                "nombre"=>$dia,
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            $horarioFinal = horarios::latest()->first();
        } else {
            $horarioFinal = $horario;
        }

        if (empty($n_empleado) || $n_emple){
            Empleados::where('id_empleado',$id)->update([
                "numero_empleado"=>$req->numero,
                "nombres"=>$req->nombres,
                "apellido_paterno"=>$req->apellidoP,
                "apellido_materno"=>$req->apellidoM,
                "fecha_nacimiento"=>$req->fecha_naci,
                "puesto_id"=>$req->puesto,
                "fecha_ingreso"=>$req->fecha_ingreso,
                "horario_id"=>$horarioFinal->id_horario,
                "updated_at"=>Carbon::now(),
            ]);
        } else {
            return back()->with('numero','numero')->withInput();
        }

        return redirect('personal/Encargado')->with('editado','editado');
    }

    public function histoIndividual($id){
        $historialVac = vacaciones::where('empleado_id',$id)
        ->get();

        $empleado = Empleados::where('id_empleado',$id)->first();

        foreach ($historialVac as $historial) {
            // Convertir la fecha de inicio a una instancia de Carbon y formatear solo la fecha como dd/MM/yyyy
            $fechaDesdeEsp = Carbon::parse($historial->fecha_inicio)->translatedFormat('d/M/Y');
            $historial->inicio = $fechaDesdeEsp;

            // Convertir la fecha de fin a una instancia de Carbon y formatear solo la fecha como dd/MM/yyyy
            $fechaHastaEsp = Carbon::parse($historial->fecha_fin)->translatedFormat('d/M/Y');
            $historial->fin = $fechaHastaEsp;

            // Primero, convertir a una instancia de Carbon y formatear a 'Y-m-d'
            $fechaCreated = date('d/m/Y', strtotime($historial->created_at));
            // Luego, convertir a una instancia de Carbon y formatear a 'd/M/Y'
            $fechaCreatedEsp = Carbon::parse($historial->created_at)->translatedFormat('d/M/Y');

            $historial->fecha_creacion = $fechaCreatedEsp;
        }

        return view('Encargado.histIndividual',compact('historialVac','empleado'));
    }

    public function reporteGeneral(){

        // Obtener todos los empleados
        $empleados = Empleados::select('empleados.*','puestos.nombre as puesto', 'areas.nombre as area','divisiones.nombre as division')
        ->join('puestos','empleados.puesto_id','puestos.id_puesto')
        ->join('areas','puestos.area_id','areas.id_area')
        ->join('divisiones','areas.division_id','divisiones.id_division')
        ->orderBy('empleados.id_empleado','asc')
        ->get();

        $historial = vacaciones::select('empleados.*','divisiones.nombre as division','areas.nombre as area','puestos.nombre as puesto',
        'vacaciones.*','vacaciones.estatus as vacacionEstatus')
        ->join('empleados','vacaciones.empleado_id','empleados.id_empleado')
        ->join('puestos','empleados.puesto_id','puestos.id_puesto')
        ->join('areas','puestos.area_id','areas.id_area')
        ->join('divisiones','areas.division_id','divisiones.id_division')
        ->orderBy('vacaciones.fecha_inicio','desc')
        ->get();

        // Formatear fechas
        $historial->transform(function($item) {
            $item->fecha_inicio = Carbon::parse($item->fecha_inicio)->format('d/m/Y');
            $item->fecha_fin = Carbon::parse($item->fecha_fin)->format('d/m/Y');
            $item->fecha_solicitud = Carbon::parse($item->created_at)->format('d/m/Y');
            // Repite el formato para otras fechas si es necesario
            return $item;
        });

        // Inicializar el arreglo para almacenar resultados
        $resultados = [];

        $hoy = Carbon::now();
        // Formatear la fecha en dd/mm/aaaa
        $fechaCorte3 = $hoy->format('d/m/Y');

        foreach ($empleados as $user) {
            // Crear una instancia de Carbon a partir de la variable de fecha
            $fecha = Carbon::createFromFormat('Y-m-d', $user->fecha_ingreso);
            // Obtener el año
            $anio = $fecha->year;

            // Inicializar las variables para el empleado
            $dias_tomados = 0;
            $dias_disponibles = 0;

            switch (true) {
                case $anio >= 2023:
                    // Calcular la diferencia en días entre la fecha de ingreso y la fecha actual
                    $diferencia = Carbon::now()->diffInDays($user->fecha_ingreso);
                    // Convertir la diferencia de días a años en formato decimal
                    $antiguedad = round($diferencia / 365, 5);

                    // Buscar si la antigüedad está entre ingreso y término en la tabla 'tablas_Vacaciones'
                    $registro = tablasVacaciones::where('ley_id', 2)
                        ->where('ingreso', '<=', $antiguedad)  // Comparar antigüedad con el valor de 'ingreso'
                        ->where('termino', '>=', $antiguedad)  // Comparar antigüedad con el valor de 'termino'
                        ->first();

                    if ($registro) {
                        $dias_tomados = vacaciones::where('empleado_id', $user->id_empleado)
                            ->where('estatus', '1')
                            ->sum('dias_tomados');

                        $dias_disponibles = $registro->acumulado - $dias_tomados;

                        // Agregar los resultados al arreglo
                        $resultados[] = [
                            'id_empleado' => $user->id_empleado,
                            'division'=>$user->division,
                            'area'=>$user->area,
                            'puesto'=>$user->puesto,
                            'numero_empleado'=>$user->numero_empleado,
                            'nombres' => $user->nombres,
                            'apellido_paterno'=>$user->apellido_paterno,
                            'apellido_materno'=>$user->apellido_materno,
                            'fecha_ingreso'=>$user->fecha_ingreso,
                            'fecha_corte'=>$fechaCorte3,
                            'fecha_conclusion1'=>null,
                            'antiguedad1'=>null,
                            'dias1'=>null,
                            'inicio2'=>null,
                            'fechaCorte2'=>null,
                            'antiguedad2'=>null,
                            'dias2'=>null,
                            'inicio3'=>$user->fecha_ingreso,
                            'fechaCorte3'=>$fechaCorte3,
                            'antiguedad3'=>$antiguedad,
                            'dias3'=>$registro->acumulado,
                            'acumulado'=>$registro->acumulado,
                            'dias_disponibles' => $dias_disponibles,
                            'dias_tomados' => $dias_tomados,
                            'estatus'=>$user->estatus,
                        ];
                    }
                    break;

                case $anio < 2023:
                    // Convierte fecha de finalización en instancia de carbon
                    $fechaCarbon = Carbon::parse('2022-12-31'); // Esta es la fecha de finalización
                    $fechaIngreso = Carbon::parse($user->fecha_ingreso);
                    // Calcular la diferencia en días entre la fecha de ingreso y la fecha de la primer ley
                    $diferencia1 = $fechaCarbon->diffInDays($user->fecha_ingreso);
                    // Convertir la diferencia de días a años en formato decimal
                    $antiguedad1 = round($diferencia1 / 365, 5);
                    $dias1 = $antiguedad1 * 6;

                    // Calcular segundo corte
                    $fechaSegundo = Carbon::parse('2023-01-01'); // Esta es la fecha de inicio de la segunda ley
                    $fechaAnio = $fechaIngreso->addYear(); // Esta será la fecha un año
                    $fechasegundoCorte = Carbon::parse($fechaAnio)->format('d/m/Y');

                    // Calcular la diferencia en días entre la fecha de ingreso y la nueva fecha
                    $diferencia2 = $fechaSegundo->diffInDays($fechaAnio);

                    // Convertir la diferencia de días a años en formato decimal
                    $antiguedad2 = round($diferencia2 / 365, 6);
                    $dias2 = $antiguedad2 * 12;

                    // Calcular la diferencia en días entre la fecha de ingreso y la fecha actual
                    $diferencia3 = Carbon::now()->diffInDays($user->fecha_ingreso);
                    // Convertir la diferencia de días a años en formato decimal
                    $antiguedad3 = round($diferencia3 / 365, 6);

                    // Buscar si la antigüedad está entre ingreso y término en la tabla 'tablas_Vacaciones'
                    $diasLey = tablasVacaciones::where('ley_id', 2)
                        ->where('ingreso', '<=', $antiguedad3)  // Comparar antigüedad con el valor de 'ingreso'
                        ->where('termino', '>=', $antiguedad3)  // Comparar antigüedad con el valor de 'termino'
                        ->first();

                    if ($diasLey) {
                        $dias3 =$diasLey->acumulado - 12;
                        $acumulado = round($dias1 + $dias2 + $dias3);
                        $dias_tomados = vacaciones::where('empleado_id', $user->id_empleado)
                            ->where('estatus', '1')
                            ->sum('dias_tomados');

                        $dias_disponibles = $acumulado - $dias_tomados;

                        // Agregar los resultados al arreglo
                        $resultados[] = [
                            'id_empleado' => $user->id_empleado,
                            'division'=>$user->division,
                            'area'=>$user->area,
                            'puesto'=>$user->puesto,
                            'numero_empleado'=>$user->numero_empleado,
                            'nombres' => $user->nombres,
                            'apellido_paterno'=>$user->apellido_paterno,
                            'apellido_materno'=>$user->apellido_materno,
                            'fecha_ingreso'=>$user->fecha_ingreso,
                            'fecha_corte'=>$fechaCorte3,
                            'fecha_conclusion1'=>'31/12/2022',
                            'antiguedad1'=>$antiguedad1,
                            'dias1'=>$dias1,
                            'inicio2'=>'01/01/2023',
                            'fechaCorte2'=>$fechasegundoCorte,
                            'antiguedad2'=>$antiguedad2,
                            'dias2'=>$dias2,
                            'inicio3'=>$fechasegundoCorte,
                            'fechaCorte3'=>$fechaCorte3,
                            'antiguedad3'=>$antiguedad3,
                            'dias3'=>$dias3,
                            'acumulado'=>$acumulado,
                            'dias_disponibles' => $dias_disponibles,
                            'dias_tomados' => $dias_tomados,
                            'estatus'=>$user->estatus,
                        ];
                    }
                    break;
            }
        }

        // Crear un nuevo archivo Excel para los datos de las requisiciones
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Asignar nombre a la hoja
        $sheet->setTitle('Tabla de saldos de vacaciones');

        // Combinar celdas de la fila 1 para el título
        $sheet->mergeCells('I2:L2');
        $sheet->setCellValue('I2', 'TABLA DE VACACIONES');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I2')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet->setCellValue('B4', 'ACTIVO/BAJA');
        $sheet->setCellValue('C4', 'DIVISION');
        $sheet->setCellValue('D4', 'NUMERO DE EMPLEADO');
        $sheet->setCellValue('E4', 'APELLIDO PATERNO');
        $sheet->setCellValue('F4', 'APELLIDO MATERNO');
        $sheet->setCellValue('G4', 'NOMBRE');
        $sheet->setCellValue('H4', 'AREA');
        $sheet->setCellValue('I4', 'PUESTO');
        $sheet->setCellValue('J4', 'FECHA INGRESO');
        $sheet->setCellValue('K4', 'FECHA CONCLUSION ANTIGUA LEY');
        $sheet->setCellValue('L4', 'ANTIGUEDAD HASTA 2022');
        $sheet->setCellValue('M4', 'DIAS');
        $sheet->setCellValue('N4', ' ');
        $sheet->setCellValue('O4', 'INICIO');
        $sheet->setCellValue('P4', 'FECHA DE CORTE');
        $sheet->setCellValue('Q4', 'DIAS CORRESPONDIENTES HASTA EL AÑO');
        $sheet->setCellValue('R4', 'DIAS');
        $sheet->setCellValue('S4', ' ');
        $sheet->setCellValue('T4', 'INICIO');
        $sheet->setCellValue('U4', 'FECHA DE CORTE');
        $sheet->setCellValue('V4', 'ANTIGUEDAD ACTUAL');
        $sheet->setCellValue('W4', 'DIAS');
        $sheet->setCellValue('X4', ' ');
        $sheet->setCellValue('Y4', 'TOTAL DIAS');
        $sheet->setCellValue('Z4', ' ');
        $sheet->setCellValue('AA4', 'TOMADOS');
        $sheet->setCellValue('AB4', 'DISPONIBLES');

        // Habilitar ajuste de texto en los encabezados y centrar
        $sheet->getStyle('B4:AB4')->getAlignment()->setWrapText(true);
        $sheet->getStyle('B4:AB4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:AB4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('B4:AB4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('B4:AB4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Escribir los datos de los usuarios en el archivo Excel
        $rowNumber = 5;
        foreach ($resultados as $usuario) {
            $usuario['estatus'] = ($usuario['estatus']=='1') ? 'ACTIVO' : 'BAJA';

            $sheet->setCellValue('B' . $rowNumber, $usuario['estatus']);
            $sheet->setCellValue('C' . $rowNumber, $usuario['division']);
            $sheet->setCellValue('D' . $rowNumber, $usuario['numero_empleado']);
            $sheet->setCellValue('E' . $rowNumber, $usuario['apellido_paterno']);
            $sheet->setCellValue('F' . $rowNumber, $usuario['apellido_materno']);
            $sheet->setCellValue('G' . $rowNumber, $usuario['nombres']);
            $sheet->setCellValue('H' . $rowNumber, $usuario['area']);
            $sheet->setCellValue('I' . $rowNumber, $usuario['puesto']);
            $sheet->setCellValue('J' . $rowNumber, $usuario['fecha_ingreso']);
            $sheet->setCellValue('K' . $rowNumber, $usuario['fecha_conclusion1']);
            $sheet->setCellValue('L' . $rowNumber, $usuario['antiguedad1']);
            $sheet->setCellValue('M' . $rowNumber, $usuario['dias1']);
            $sheet->setCellValue('N' . $rowNumber, '');
            $sheet->setCellValue('O' . $rowNumber, $usuario['inicio2']);
            $sheet->setCellValue('P' . $rowNumber, $usuario['fechaCorte2']);
            $sheet->setCellValue('Q' . $rowNumber, $usuario['antiguedad2']);
            $sheet->setCellValue('R' . $rowNumber, $usuario['dias2']);
            $sheet->setCellValue('S' . $rowNumber, '');
            $sheet->setCellValue('T' . $rowNumber, $usuario['fechaCorte3']);
            $sheet->setCellValue('U' . $rowNumber, $usuario['fechaCorte3']);
            $sheet->setCellValue('V' . $rowNumber, $usuario['antiguedad3']);
            $sheet->setCellValue('W' . $rowNumber, $usuario['dias3']);
            $sheet->setCellValue('X' . $rowNumber, '');
            $sheet->setCellValue('Y' . $rowNumber, $usuario['acumulado']);
            $sheet->setCellValue('Z' . $rowNumber, '');
            $sheet->setCellValue('AA' . $rowNumber, $usuario['dias_tomados']);
            $sheet->setCellValue('AB' . $rowNumber, $usuario['dias_disponibles']);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('B' . $rowNumber . ':AB' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('B' . $rowNumber . ':AB' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('B4:AB4');

        // Establecer el color de fondo en ciertas columnas
        $sheet->getStyle('M4:M'.$rowNumber-1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $sheet->getStyle('R4:R'.$rowNumber-1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $sheet->getStyle('W4:W'.$rowNumber-1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

        // Ajustar manualmente el tamaño de las columnas con encabezados largos
        $sheet->getColumnDimension('B')->setAutoSize(true); // ACTIVO/BAJA
        $sheet->getColumnDimension('C')->setAutoSize(true); // DIVISION
        $sheet->getColumnDimension('D')->setWidth(12); // FECHA INGRESO
        $sheet->getColumnDimension('E')->setAutoSize(true); // APELLIDO PATERNO
        $sheet->getColumnDimension('F')->setAutoSize(true); // APELLIDO MATERNO
        $sheet->getColumnDimension('G')->setAutoSize(true); // NOMBRE
        $sheet->getColumnDimension('H')->setAutoSize(true); // AREA
        $sheet->getColumnDimension('I')->setAutoSize(true); // PUESTO

        // Fijar el tamaño de columnas con encabezados largos
        $sheet->getColumnDimension('J')->setWidth(15); // FECHA INGRESO
        $sheet->getColumnDimension('K')->setWidth(20); // FECHA CONCLUSION ANTIGUA LEY
        $sheet->getColumnDimension('L')->setWidth(15); // ANTIGUEDAD HASTA 2022
        $sheet->getColumnDimension('M')->setWidth(10); // DIAS
        $sheet->getColumnDimension('N')->setWidth(5);
        $sheet->getColumnDimension('O')->setWidth(12); // INICIO
        $sheet->getColumnDimension('P')->setWidth(12); // FECHA DE CORTE
        $sheet->getColumnDimension('Q')->setWidth(20); // DIAS CORRESPONDIENTES HASTA EL AÑO
        $sheet->getColumnDimension('R')->setWidth(10); // DIAS
        $sheet->getColumnDimension('S')->setWidth(5);
        $sheet->getColumnDimension('T')->setWidth(12); // INICIO
        $sheet->getColumnDimension('U')->setWidth(12); // FECHA DE CORTE
        $sheet->getColumnDimension('V')->setWidth(15); // ANTIGUEDAD ACTUAL
        $sheet->getColumnDimension('W')->setWidth(10); // DIAS
        $sheet->getColumnDimension('X')->setWidth(5);
        $sheet->getColumnDimension('Y')->setWidth(10); // TOTAL DIAS
        $sheet->getColumnDimension('Z')->setWidth(5);
        $sheet->getColumnDimension('AA')->setWidth(10); // TOMADOS
        $sheet->getColumnDimension('AB')->setWidth(10); // DISPONIBLES

        // Crear segunda hoja
        $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'HISTORIAL DE SOLICITUDES');
        $spreadsheet->addSheet($sheet2);

        // Combinar celdas de la fila 1 para el título
        $sheet2->mergeCells('G2:K2');
        $sheet2->setCellValue('G2', 'HISTORIAL DE VACACIONES');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet2->getStyle('G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('G2')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet2->setCellValue('B4', 'ACTIVO/BAJA');
        $sheet2->setCellValue('C4', 'DIVISION');
        $sheet2->setCellValue('D4', 'NUMERO DE EMPLEADO');
        $sheet2->setCellValue('E4', 'APELLIDO PATERNO');
        $sheet2->setCellValue('F4', 'APELLIDO MATERNO');
        $sheet2->setCellValue('G4', 'NOMBRE');
        $sheet2->setCellValue('H4', 'AREA');
        $sheet2->setCellValue('I4', 'PUESTO');
        $sheet2->setCellValue('J4', 'FECHA SOLICITUD');
        $sheet2->setCellValue('K4', 'DIAS TOMADOS');
        $sheet2->setCellValue('L4', 'FECHA INICIO');
        $sheet2->setCellValue('M4', 'FECHA FIN');
        $sheet2->setCellValue('N4', 'COMENTARIOS');
        $sheet2->setCellValue('O4', 'ESTATUS');
        $sheet2->setCellValue('P4', 'PDF');


        // Habilitar ajuste de texto en los encabezados y centrar
        $sheet2->getStyle('B4:P4')->getAlignment()->setWrapText(true);
        $sheet2->getStyle('B4:P4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('B4:P4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Establecer el color de fondo de los encabezados
        $sheet2->getStyle('B4:P4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');

        // Añadir bordes gruesos a los encabezados
        $sheet2->getStyle('B4:P4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Escribir los datos de los usuarios en el archivo Excel
        $rowNumber = 5;

        // Ruta base donde se almacenan los archivos de vacaciones
        $rutaBase = 'C:/laragon/www/VamsVacaciones/public/';

        foreach ($historial as $usuario) {

            $usuario->estatus = ($usuario->estatus == '1') ? 'ACTIVO' : 'BAJA';
            $usuario->vacacionEstatus = ($usuario->vacacionEstatus == '1') ? 'APROBADA' : 'SOLICITADA';

            $sheet2->setCellValue('B' . $rowNumber, $usuario->estatus);
            $sheet2->setCellValue('C' . $rowNumber, $usuario->division);
            $sheet2->setCellValue('D' . $rowNumber, $usuario->numero_empleado);
            $sheet2->setCellValue('E' . $rowNumber, $usuario->apellido_paterno);
            $sheet2->setCellValue('F' . $rowNumber, $usuario->apellido_materno);
            $sheet2->setCellValue('G' . $rowNumber, $usuario->nombres);
            $sheet2->setCellValue('H' . $rowNumber, $usuario->area);
            $sheet2->setCellValue('I' . $rowNumber, $usuario->puesto);
            $sheet2->setCellValue('J' . $rowNumber, $usuario->fecha_solicitud);
            $sheet2->setCellValue('K' . $rowNumber, $usuario->dias_tomados);
            $sheet2->setCellValue('L' . $rowNumber, $usuario->fecha_inicio);
            $sheet2->setCellValue('M' . $rowNumber, $usuario->fecha_fin);
            $sheet2->setCellValue('N' . $rowNumber, $usuario->observaciones);
            $sheet2->setCellValue('O' . $rowNumber, $usuario->vacacionEstatus);
            if(empty($usuario->pdf)){
                $sheet2->setCellValue('P' . $rowNumber, $usuario->pdf);
            } else {
                // Ruta completa al archivo de vacaciones del empleado
                $rutaArchivo = $rutaBase . $usuario->pdf;
                $sheet2->setCellValue('P' . $rowNumber, '=HYPERLINK("' . $rutaArchivo . '", "Ver archivo")');
            }

            // Centrar las celdas de la fila actual
            $sheet2->getStyle('B' . $rowNumber . ':P' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet2->getStyle('B' . $rowNumber . ':P' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet2->setAutoFilter('B4:P4');

        // Ajustar manualmente el tamaño de las columnas con encabezados largos
        $sheet2->getColumnDimension('B')->setAutoSize(true); // ACTIVO/BAJA
        $sheet2->getColumnDimension('C')->setAutoSize(true); // DIVISION
        $sheet2->getColumnDimension('D')->setWidth(12); // NUMERO DE EMPLEADO
        $sheet2->getColumnDimension('E')->setAutoSize(true); // APELLIDO PATERNO
        $sheet2->getColumnDimension('F')->setAutoSize(true); // APELLIDO MATERNO
        $sheet2->getColumnDimension('G')->setAutoSize(true); // NOMBRE
        $sheet2->getColumnDimension('H')->setAutoSize(true); // AREA
        $sheet2->getColumnDimension('I')->setAutoSize(true); // PUESTO

        // Fijar el tamaño de columnas con encabezados largos
        $sheet2->getColumnDimension('J')->setWidth(15); // FECHA SOLICITUD
        $sheet2->getColumnDimension('K')->setWidth(20); // DIAS TOMADOS
        $sheet2->getColumnDimension('L')->setWidth(15); // FECHA INICIO
        $sheet2->getColumnDimension('M')->setWidth(10); // FECHA FIN
        $sheet2->getColumnDimension('N')->setWidth(25); // COMENTARIOS
        $sheet2->getColumnDimension('O')->setWidth(12); // ESTATUS
        $sheet2->getColumnDimension('P')->setWidth(12); // PDF

        //REGRESA A HOJA 1
        $spreadsheet->setActiveSheetIndex(0);

        // Configurar el archivo para descarga
        $fileName = 'reporte_TablaVacaciones_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Crear una respuesta de transmisión para la descarga
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

    }

    public function miPerfil () {
        $personal = Empleados::select('empleados.*','puestos.nombre as puesto')
        ->where('id_empleado',session('loginId'))
        ->join('puestos','empleados.puesto_id','puestos.id_puesto')
        ->first();

        $personal->fecha_nacimiento = Carbon::parse($personal->fecha_nacimiento)->format('d/m/Y');
        $personal->fecha_ingreso = Carbon::parse($personal->fecha_ingreso)->format('d/m/Y');

        return view('Encargado.miPerfil',compact('personal'));
    }

    function formatearFecha($fecha) {
        // Divide la fecha en día, mes y año
        $partes = explode('-', $fecha);

        // Verifica que haya 3 partes: día, mes y año
        if(count($partes) === 3) {
            $dia = $partes[2];  // dd
            $mes = $partes[1];  // mm
            $año = substr($partes[0], -2);  // aa (últimos dos dígitos del año)

            // Retorna el formato: año, mes y día sin "/"
            return $año . $mes . $dia;
        }

        // Si el formato de la fecha no es válido, retornar null o un mensaje de error
        return null;
    }

    public function obtenerDiasFestivos($year,$yearF)
    {
        $client = new Client();

        if($year == $yearF){
            try {
                $response = $client->request('GET', "https://date.nager.at/api/v3/PublicHolidays/{$year}/MX");
                $festivos = json_decode($response->getBody(), true);

                // Filtrar solo los días públicos
                $diasPublicos = array_filter($festivos, function($festivo) {
                    return in_array('Public', $festivo['types']); // Verificar si 'Public' está en el array types
                });

                // Extraer las fechas de los días festivos públicos
                return array_column($diasPublicos, 'date');
            } catch (\Exception $e) {
                // Manejo de errores
                \Log::error('Error al obtener días festivos: ' . $e->getMessage());
                return []; // Retorna un arreglo vacío en caso de error
            }
        } else {
            try {
                $response1 = $client->request('GET', "https://date.nager.at/api/v3/PublicHolidays/{$year}/MX");
                $festivos1 = json_decode($response1->getBody(), true);

                $response2 = $client->request('GET', "https://date.nager.at/api/v3/PublicHolidays/{$yearF}/MX");
                $festivos2 = json_decode($response2->getBody(), true);

                // Filtrar solo los días públicos
                $diasPublicos1 = array_filter($festivos1, function($festivo) {
                    return in_array('Public', $festivo['types']); // Verificar si 'Public' está en el array types
                });

                // Filtrar solo los días públicos
                $diasPublicos2 = array_filter($festivos2, function($festivo) {
                    return in_array('Public', $festivo['types']); // Verificar si 'Public' está en el array types
                });

                // Extraer las fechas de los días festivos públicos
                $diasFestivos = array_merge($diasPublicos1,$diasPublicos2);

                // Extraer las fechas de los días festivos públicos
                return array_column($diasFestivos, 'date');
            } catch (\Exception $e) {
                // Manejo de errores
                \Log::error('Error al obtener días festivos: ' . $e->getMessage());
                return []; // Retorna un arreglo vacío en caso de error
            }
        }
    }
}
