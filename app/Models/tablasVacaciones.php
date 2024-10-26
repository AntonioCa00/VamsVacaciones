<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tablasVacaciones extends Model
{

    protected $fillable =
    [
        'id_dias',
        'ingreso',
        'termino',
        'dias_disponibles',
        'acumulado',
        'ley_id',
    ];

    use HasFactory;
}
