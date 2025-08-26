<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'negocio_id','user_id','nombre_cliente',
        'fecha','hora_inicio','hora_fin','notas','estado',
    ];

    public function negocio()
    {
        // tu modelo Empresa apunta a la tabla 'negocios'
        return $this->belongsTo(\App\Models\Empresa\Empresa::class, 'negocio_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
