<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'negocio_id',
        'nombre',
        'email',
        'telefono',
    ];

    /**
     * Relación con el negocio
     */
    public function negocio()
    {
        return $this->belongsTo(Negocio::class, 'negocio_id');
    }
}
