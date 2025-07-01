<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\FotoEmpresa;
use App\Models\Empresa\ServicioEmpresa; 

class Negocio extends Model
{
    protected $fillable = [
        'user_id',
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
        'configuracion_bloques',
    ];

    protected $casts = [
        'neg_categorias' => 'array',
        'neg_acepto' => 'boolean',
        'neg_virtual' => 'boolean',
        'neg_direccion_confirmada' => 'boolean',
        'configuracion_bloques' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método para obtener bloques configurados
    public function getBloquesConfigurados()
    {
        return $this->configuracion_bloques ?? [];
    }

    // Método para verificar si un bloque está activo
    public function tieneBloque($tipo)
    {
        $bloques = $this->getBloquesConfigurados();
        return in_array($tipo, $bloques);
    }

    public function servicios()
    {
        return $this->hasMany(ServicioEmpresa::class);
    }


}
