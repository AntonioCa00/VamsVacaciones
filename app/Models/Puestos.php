<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puestos extends Model
{
    protected $fillable =
    [
        'id_puesto',
        'nombre',
        'descripcion',
        'area_id',
        'estatus',
        'created_at',
        'updated_at',
    ];

    use HasFactory;
}
