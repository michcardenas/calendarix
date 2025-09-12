<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\ServicioEmpresa;
use App\Models\Empresa\Empresa;
use App\Models\User;
use App\Models\Trabajador;

class Cita extends Model
{
    protected $fillable = [
        'negocio_id',
        'user_id',
        'nombre_cliente',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'notas',
        'estado',
        'servicio_id',
        'precio_cerrado',
        'trabajador_id',   // ← nuevo campo
    ];

    protected $casts = [
        'fecha' => 'date', // usa 'datetime' si tu columna es DATETIME
    ];

    /**
     * Relación con el negocio
     */
    public function negocio()
    {
        return $this->belongsTo(Empresa::class, 'negocio_id');
    }

    /**
     * Relación con el usuario que creó la cita
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el servicio
     */
    public function servicio()
    {
        return $this->belongsTo(ServicioEmpresa::class, 'servicio_id');
    }

    /**
     * Relación con el trabajador asignado
     */
    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
