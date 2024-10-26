<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class leyesVacaciones extends Model
{

    protected $fillable =
    [
        'id_leyes',
        'descripcion',
        'anio_inicio',
        'anio_fin',
    ];

    use HasFactory;
}
