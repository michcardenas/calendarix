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

    public function fotos()
    {
        return $this->hasMany(\App\Models\Empresa\FotoEmpresa::class, 'negocio_id')->orderBy('orden');
    }

    public function resenas()
    {
        return $this->hasMany(\App\Models\Resena::class, 'negocio_id')->latest();
    }
}
