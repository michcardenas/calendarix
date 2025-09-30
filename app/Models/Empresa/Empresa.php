<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'negocios';

    // Permitir acceso a todos los atributos (ya que es un modelo proxy)
    protected $guarded = [];

    public function clientes()
    {
        return $this->hasMany(\App\Models\Cliente::class, 'negocio_id');
    }

    public function trabajadores()
    {
        return $this->hasMany(\App\Models\Trabajador::class, 'negocio_id');
    }

    public function productos()
    {
        return $this->hasMany(\App\Models\Producto::class, 'negocio_id');
    }
}
