<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisiones extends Model
{
    protected $fillable =
    [
        'id_division',
        'nombre',
        'descripcion',
        'estatus',
        'created_at',
        'updated_at',
    ];

    use HasFactory;
}
