<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use Session;

class Login extends Controller
{

    public function login(){
        return view("login");
    }

    /*
        TODO: Función que valida la existencia del usuario en la base de datos y permitir o rechazar el acceso

        @returns redireccion a la vista permitida
    */
    public function loginUser (Request $req){
        $user = Empleados::select('empleados.*','puestos.nombre as puesto','areas.nombre as area')
        ->join('puestos','empleados.puesto_id','puestos.id_puesto')
        ->join('areas','puestos.area_id','areas.id_area')
        ->where('numero_empleado', '=', $req->numero)
        ->first(); //Hace la consulta basada en el nombre de usuario
        if ($user){ //valida que exista un usuario llamdado así
            if($user->contrasena == $req->contrasena){  //Compara las contraseñas para iniciar sesion
                //Agrega todos los datos del usuario a la session activa
                $attributes = [
                    'loginId' => $user->id_empleado,
                    'empleadoN' => $user->numero_empleado,
                    'loginNombres' => $user->nombres,
                    'loginApepat' => $user->apellido_paterno,
                    'loginApemat' => $user->apellido_materno,
                    'puesto'=>$user->puesto,
                    'rol'=>$user->rol,
                    'area'=>$user->area,
                    'fecha_ingreso'=>$user->fecha_ingreso,
                ];

                $req->session()->put($attributes);


                //Asigna una redireccion a la vista correspondiente segun su rol
                if($user->rol == "Administrador"){
                    return redirect('inicio/Administrador')->with('entra','entra');
                }elseif ($user->rol == "Encargado"){
                    return redirect('inicio/Encargado')->with('entra','entra');
                    //return redirect('inicio/GerenciaGeneral')->with('entra','entra');
                } else{
                    return redirect('inicio')->with('entra','entra');
                    //return redirect('inicio')->with('entra','entra');
                }
            } else{
                //en caso de no coincidir las contraseñas regresa un mensaje de revisar contraseñas
                return back()->with('contras','contras')->withInput();
            }
        } else {
            //en caso de no encontrar el nombre de usuario regresa un mensaje de revisar información
            return back()->with('error','error');
        }
    }

    /*
    TODO: Función que cierra la sesion iniciada

    @return redireccion a la vista del login
    */
    public function logout(){
        if(Session::has('loginId')){ //valida si existe una session creada
            Session::pull('loginId'); //Termina la session
            session()->flush(); //Elimina la informacion almacenada en la session del usuario
            return redirect('/');
        }
    }

}

