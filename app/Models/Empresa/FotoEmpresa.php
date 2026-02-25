<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;
use App\Models\Negocio;

class FotoEmpresa extends Model
{
    protected $table = 'fotos_empresa';

    protected $fillable = [
        'negocio_id',
        'ruta',
        'orden',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
