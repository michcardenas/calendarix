<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\FotoEmpresa;
use App\Models\Empresa\ServicioEmpresa;
use App\Models\Empresa\Empresa;

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
        'neg_portada',
        'neg_virtual',
        'neg_direccion_confirmada',
        'configuracion_bloques',
        'neg_facebook',
        'neg_instagram',
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

    public function horarios()
    {
        return $this->hasMany(HorarioLaboral::class, 'negocio_id');
    }

    public function bloqueos()
    {
        return $this->hasMany(DiaBloqueado::class, 'negocio_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function productos()
    {
        return $this->hasMany(\App\Models\Producto::class, 'negocio_id');
    }
}
