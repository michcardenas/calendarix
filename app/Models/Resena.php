<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'cita_id',
        'negocio_id',
        'user_id',
        'nombre_cliente',
        'email_cliente',
        'rating',
        'comentario',
        'respuesta_negocio',
        'respuesta_fecha',
    ];

    protected $casts = [
        'rating' => 'integer',
        'respuesta_fecha' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
