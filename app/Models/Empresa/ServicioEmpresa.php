<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class ServicioEmpresa extends Model
{
    protected $table = 'servicios_empresa';

    protected $fillable = [
        'negocio_id',
        'nombre',
        'descripcion',
        'precio',
    ];

    public function negocio()
    {
        return $this->belongsTo(\App\Models\Negocio::class, 'negocio_id');
    }
}
