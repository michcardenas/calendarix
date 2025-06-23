<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $fillable = [
        'neg_nombre',
        'neg_apellido',
        'neg_email',
        'neg_telefono',
        'neg_pais',
        'neg_acepto',
        'neg_imagen',
        'neg_nombre_comercial',
        'neg_sitio_web',
        'neg_categorias',
        'neg_equipo',
        'neg_direccion',
        'neg_virtual',
        'neg_direccion_confirmada',
    ];

    protected $casts = [
        'neg_categorias' => 'array',
        'neg_acepto' => 'boolean',
        'neg_virtual' => 'boolean',
        'neg_direccion_confirmada' => 'boolean',
    ];
}
