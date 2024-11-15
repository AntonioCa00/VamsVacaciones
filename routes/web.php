<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\controladorAdmin;
use App\Http\Controllers\controladorEmpleado;
use App\Http\Controllers\controladorEncargado;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('validar-login',[Login::class,'loginUser'])->name('validate');
Route::get('logout',[Login::class,'logout'])->name('logout');

Route::middleware(['alreadyLoggedIn'])->group(function () {
    Route::get('/',[Login::class,'login'])->name('login');
});

Route::middleware(['authcheck'])->group(function () {

    Route::middleware(['check.role:Administrador'])->group(function () {
        Route::get('inicio/Administrador',[controladorAdmin::class,'index'])->name('indexAdmin');
        Route::get('mi-perfil/Administrador',[controladorAdmin::class,'miPerfil'])->name('miPerfilAdmin');
        Route::get('panel/puestos',[controladorAdmin::class,'panelPuestos'])->name('panelPuestos');
        Route::get('divisiones',[controladorAdmin::class,'tableDivisiones'])->name('divisiones');
        Route::get('divisiones/crear',[controladorAdmin::class,'crearDivision'])->name('crearDivision');
        Route::get('divisiones/editar/{id}',[controladorAdmin::class,'editarDivision'])->name('editarDivision');
        Route::get('areas',[controladorAdmin::class,'tableAreas'])->name('areas');
        Route::get('areas/crear',[controladorAdmin::class,'crearArea'])->name('crearArea');
        Route::get('areas/editar/{id}',[controladorAdmin::class,'editarArea'])->name('editarArea');
        Route::get('puestos',[controladorAdmin::class,'tablePuestos'])->name('puestos');
        Route::get('puestos/crear',[controladorAdmin::class,'crearPuesto'])->name('crearPuesto');
        Route::get('puestos/editar/{id}',[controladorAdmin::class,'editarPuesto'])->name('editarPuesto');
        Route::get('personal',[controladorAdmin::class,'tablePersonal'])->name('personal');
        Route::get('personal/crear',[controladorAdmin::class,'crearPersonal'])->name('crearPersonal');
        Route::get('personal/editar/{id}',[controladorAdmin::class,'editarPersonal'])->name('editarPersonal');
        Route::get('permisos/Administrador',[controladorAdmin::class,'permisos'])->name('permisosAdm');
        Route::get('durante/Administrador',[controladorAdmin::class,'durante'])->name('duranteAdm');
        Route::get('ausentarse/Administrador',[controladorAdmin::class,'ausentarse'])->name('ausentarseAdm');
        Route::get('vacaciones/Administrador',[controladorAdmin::class,'vacaciones'])->name('vacacionesAdm');
        Route::get('historial/Administrador',[controladorAdmin::class,'tableHistorial'])->name('historialAdm');
        Route::get('consultar/vacaciones/Administrador',[controladorAdmin::class,'calendario'])->name('calendarioAdm');
        Route::get('consultar/vacaciones/Administrador/programacion', [controladorAdmin::class, 'getEvents'])->name('programaAdm');

        //-------------------
        Route::post('divisiones/create',[controladorAdmin::class,'createDivision'])->name('createDivision');
        Route::put('divisiones/update/{id}',[controladorAdmin::class,'updateDivision'])->name('updateDivision');
        Route::put('divisiones/delete/{id}',[controladorAdmin::class,'deleteDivision'])->name('deleteDivision');
        Route::post('areas/create',[controladorAdmin::class,'createArea'])->name('createArea');
        Route::put('areas/update/{id}',[controladorAdmin::class,'updateArea'])->name('updateArea');
        Route::put('areas/delete/{id}',[controladorAdmin::class,'deleteArea'])->name('deleteArea');
        Route::post('puestos/create',[controladorAdmin::class,'createPuesto'])->name('createPuesto');
        Route::put('puestos/update/{id}',[controladorAdmin::class,'updatePuesto'])->name('updatePuesto');
        Route::put('puestos/delete/{id}',[controladorAdmin::class,'deletePuesto'])->name('deletePuesto');
        Route::post('personal/create',[controladorAdmin::class,'createPersonal'])->name('createPersonal');
        Route::put('personal/update/{id}',[controladorAdmin::class,'updatePersonal'])->name('updatePersonal');
        Route::put('personal/delete/{id}',[controladorAdmin::class,'deletePersonal'])->name('deletePersonal');
        Route::post('permiso/durante/Admin',[controladorAdmin::class,'createDurante'])->name('createDuranteAdm');
        Route::post('permiso/ausentarse/Admin',[controladorAdmin::class,'createAusentarse'])->name('createAusentarseAdm');
        Route::post('permiso/vacaciones/Admin',[controladorAdmin::class,'createVacaciones'])->name('createVacacionesAdm');
        Route::delete('permiso/vacaciones{id}/delete/Admin',[controladorAdmin::class,'deleteVacacion'])->name('deleteVacacionAdm');
        Route::put('permiso/vacaciones{id}/updated/Admin',[controladorAdmin::class,'updateVacacion'])->name('updateVacacionAdm');
        Route::put('aprobar/vacaciones/Administrador',[controladorAdmin::class,'aprobarVacaciones'])->name('aprobarAdm');
    });

    Route::middleware(['check.role:General'])->group(function () {
        Route::get('inicio',[controladorEmpleado::class,'index'])->name('index');
        Route::get('mi-perfil',[controladorEmpleado::class,'miPerfil'])->name('miPerfil');
        Route::get('permisos',[controladorEmpleado::class,'permisos'])->name('permisos');
        Route::get('durante',[controladorEmpleado::class,'durante'])->name('durante');
        Route::get('ausentarse',[controladorEmpleado::class,'ausentarse'])->name('ausentarse');
        Route::get('vacacion',[controladorEmpleado::class,'vacaciones'])->name('vacaciones');
        Route::get('historial',[controladorEmpleado::class,'tableHistorial'])->name('historial');

        //----------------------------------------------
        Route::post('permiso/durante',[controladorEmpleado::class,'createDurante'])->name('createDurante');
        Route::post('permiso/ausentarse',[controladorEmpleado::class,'createAusentarse'])->name('createAusentarse');
        Route::post('permiso/vacaciones',[controladorEmpleado::class,'createVacaciones'])->name('createVacaciones');
        Route::put('permiso/vacaciones{id}/updated',[controladorEmpleado::class,'updateVacacion'])->name('updateVacacion');
        Route::delete('permiso/vacaciones{id}/delete',[controladorEmpleado::class,'deleteVacacion'])->name('deleteVacacion');

    });

    Route::middleware(['check.role:Encargado'])->group(function() {
        Route::get('inicio/Encargado',[controladorEncargado::class,'index'])->name('indexEncargado');
        Route::get('mi-perfil/Encargado',[controladorEncargado::class,'miPerfil'])->name('miPerfilEncargado');
        Route::get('permisos/Encargado',[controladorEncargado::class,'permisos'])->name('permisosEnc');
        Route::get('durante/Encargado',[controladorEncargado::class,'durante'])->name('duranteEnc');
        Route::get('ausentarse/Encargado',[controladorEncargado::class,'ausentarse'])->name('ausentarseEnc');
        Route::get('vacacion/Encargado',[controladorEncargado::class,'vacaciones'])->name('vacacionesEnc');
        Route::get('historial/Encargado',[controladorEncargado::class,'tableHistorial'])->name('historialEnc');
        Route::get('consultar/vacaciones',[controladorEncargado::class,'calendario'])->name('calendario');
        Route::get('consultar/vacaciones/programacion', [controladorEncargado::class, 'getEvents'])->name('programa');
        Route::get('personal/Encargado',[controladorEncargado::class,'tablePersonal'])->name('personalEnc');
        Route::get('personal/crear/Encargado',[controladorEncargado::class,'crearPersonal'])->name('crearPersonalEnc');
        Route::get('historial/Induvidual/{id}',[controladorEncargado::class,'histoIndividual'])->name('histoIndividual');
        Route::get('personal/editar/Encargado/{id}',[controladorEncargado::class,'editarPersonal'])->name('editarPersonalEnc');
        Route::get('personal/reporte/Encargado',[controladorEncargado::class,'reporteGeneral'])->name('reporte');

        //---------------------------------------------
        Route::post('permiso/durante/Encargado',[controladorEncargado::class,'createDurante'])->name('createDuranteEnc');
        Route::post('permiso/ausentarse/Encargado',[ controladorEncargado::class,'createAusentarse'])->name('createAusentarseEnc');
        Route::post('permiso/vacaciones/Encargado',[ controladorEncargado::class,'createVacaciones'])->name('createVacacionesEnc');
        Route::put('permiso/vacaciones{id}/updated/Encargado',[ controladorEncargado::class,'updateVacacion'])->name('updateVacacionEnc');
        Route::delete('permiso/vacaciones{id}/delete/Encargado',[ controladorEncargado::class,'deleteVacacion'])->name('deleteVacacionEnc');
        Route::put('aprobar/vacaciones/Encargado',[controladorEncargado::class,'aprobarVacaciones'])->name('aprobarEnc');
        Route::post('personal/create/Encargado',[controladorEncargado::class,'createPersonal'])->name('createPersonalEnc');
        Route::put('personal/update/Encargado/{id}',[controladorEncargado::class,'updatePersonal'])->name('updatePersonalEnc');
    });
});

