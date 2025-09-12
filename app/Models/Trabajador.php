<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\Empresa;

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
     * Relación con la empresa (negocio)
     */
    public function negocio()
    {
        return $this->belongsTo(Empresa::class, 'negocio_id');
    }

    /**
     * Citas asignadas a este trabajador
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'trabajador_id');
    }
}
