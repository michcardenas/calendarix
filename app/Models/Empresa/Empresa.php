<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'negocios';

    public function clientes()
    {
        return $this->hasMany(\App\Models\Cliente::class, 'negocio_id');
    }
}
