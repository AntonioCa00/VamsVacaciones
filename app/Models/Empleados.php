<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    protected $fillable =
    [
        'id_empleado',
        'numero_empleado',
        'contrasena',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'puesto_id',
        'fecha_ingreso',
        'horario_id',
        'rol',
        'estatus',
        'created_at',
        'updated_at',
    ];

    use HasFactory;
}
