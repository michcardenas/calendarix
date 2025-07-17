<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioLaboral extends Model
{
    protected $table = 'horarios_laborales';

    protected $fillable = [
        'negocio_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'activo',
    ];
}
