<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\ServicioEmpresa;
use App\Models\Empresa\Empresa;
use App\Models\User;

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
        'servicio_id',      // ← nuevo campo
        'precio_cerrado',   // ← opcional si lo usas en la migración
    ];

    protected $casts = [
        'fecha' => 'date', // usa 'datetime' si tu columna es DATETIME
    ];

    // Relaciones
    public function negocio()
    {
        // apunta a la tabla negocios
        return $this->belongsTo(Empresa::class, 'negocio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function servicio()
    {
        return $this->belongsTo(ServicioEmpresa::class, 'servicio_id');
    }
}
