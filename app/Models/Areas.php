<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $fillable =
    [
        'id_area',
        'nombre',
        'descripcion',
        'division_id',
        'estatus',
        'created_at',
        'updated_at',
    ];

    use HasFactory;
}
