<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use App\Models\leyesVacaciones;
use App\Models\tablasVacaciones;
use App\Models\vacaciones;
use App\Models\horarios;
use GuzzleHttp\Client;

use Carbon\Carbon;

class controladorEmpleado extends Controller
{
    public function index (){

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
            $antiguedad = round($diferencia / 365, 2);

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
        return view('Empleados.index');
    }

    public function miPerfil(){
        $personal = Empleados::select('empleados.*','puestos.nombre as puesto')
        ->where('id_empleado',session('loginId'))
        ->join('puestos','empleados.puesto_id','puestos.id_puesto')
        ->first();

        $personal->fecha_nacimiento = Carbon::parse($personal->fecha_nacimiento)->format('d/m/Y');
        $personal->fecha_ingreso = Carbon::parse($personal->fecha_ingreso)->format('d/m/Y');

        return view('Empleados.miPerfil',compact('personal'));
    }

    public function permisos (){
        return view('Empleados.panelSolicitudes');
    }

    public function durante (){
        return view('Empleados.crearDurante');
    }

    public function createDurante (Request $req){

        if($req->hasta < $req->desde){
            return back()->with('incorrecto','incorrecto');
        } else{

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
            Carbon::setLocale('es_MX');$fechaFormateadaEsp = $fecha->translatedFormat('d/M/Y');

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
    }

    public function ausentarse(){
        return view('Empleados.crearAusentarse');
    }

    public function createAusentarse(Request $req){

        if($req->hasta < $req->desde){
            return back()->with('incorrecto','incorrecto');
        } else{

            //Define el tipo de permiso que se esta solicitando
            $permiso = 'Ausentarse';

            //Formatea los request a Carbon
            $desde = Carbon::parse($req->desde);
            $hasta = Carbon::parse($req->hasta);
            $fechaHoy = Carbon::now();
            $tipo = $req->tipo;
            $motivo = $req->motivo;

            //Meses en español
            Carbon::setLocale('es_MX');$fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

            //Meses en español
            Carbon::setLocale('es_MX');$fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

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
    }

    public function vacaciones (){
        return view('Empleados.crearVacacion');
    }

    public function createVacaciones(Request $req){

        if($req->hasta < $req->desde){
            return back()->with('incorrecto','incorrecto');
        } else{
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

            if ($duracion < session('dias_disponibles')){

                $fechaHoy = Carbon::now();
                $motivo = $req->motivo;

                //Meses en español
                Carbon::setLocale('es_MX');    $fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

                //Meses en español
                Carbon::setLocale('es_MX');    $fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

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

                return redirect('historial')->with('creado','creado');

            } else{
                return back()->with('insuficiente','insuficiente')->withInput();
            }
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
        return view('Empleados.historialVacaciones', compact('historialVac'));
    }

    public function updateVacacion(Request $req, $id){

        if($req->hasta < $req->desde){
            return back()->with('incorrecto','incorrecto');
        } else{

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

            if ($duracion < session('dias_disponibles')){

                $fechaHoy = Carbon::now();
                $motivo = $req->motivo;

                //Meses en español
                Carbon::setLocale('es_MX');    $fechaDesdeEsp = $desde->translatedFormat('d/M/Y');

                //Meses en español
                Carbon::setLocale('es_MX');    $fechaHastaEsp = $hasta->translatedFormat('d/M/Y');

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

                return redirect('historial')->with('editado','editado');

            } else{
                return back()->with('insuficiente','insuficiente')->withInput();
            }
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
