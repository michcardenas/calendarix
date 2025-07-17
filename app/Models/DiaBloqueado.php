<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaBloqueado extends Model
{
    protected $table = 'dias_bloqueados';

    protected $fillable = ['negocio_id', 'fecha_bloqueada'];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
