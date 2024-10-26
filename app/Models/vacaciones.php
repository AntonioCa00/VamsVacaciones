<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vacaciones extends Model
{

    protected $fillable =
    [
        'id_vacaciones',
        'fecha_inicio',
        'fecha_fin',
        'dias_tomados',
        'observaciones',
        'pdf',
        'estatus',
        'empleado_id',
        'created_at',
        'updated_at',
    ];

    use HasFactory;
}
