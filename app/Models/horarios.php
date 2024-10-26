<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class horarios extends Model
{
    protected $fillable =
    [
        'id_horario',
        'nombre',
        'estatus',
        'created_at',
        'updated_at',
    ];

    use HasFactory;
}
