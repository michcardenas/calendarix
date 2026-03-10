<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\FotoEmpresa;
use App\Models\Empresa\ServicioEmpresa;
use App\Models\Empresa\Empresa;
use App\Models\Resena;
use Illuminate\Support\Str;

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
        'slug',
        'neg_sitio_web',
        'neg_categorias',
        'neg_equipo',
        'neg_direccion',
        'neg_latitud',
        'neg_longitud',
        'neg_portada',
        'neg_virtual',
        'neg_direccion_confirmada',
        'configuracion_bloques',
        'neg_facebook',
        'neg_instagram',
        'neg_descripcion',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($negocio) {
            if (empty($negocio->slug)) {
                $negocio->slug = static::generateUniqueSlug(
                    $negocio->neg_nombre_comercial ?: $negocio->neg_nombre
                );
            }
        });

        static::updating(function ($negocio) {
            if ($negocio->isDirty('neg_nombre_comercial') && $negocio->neg_nombre_comercial) {
                $negocio->slug = static::generateUniqueSlug(
                    $negocio->neg_nombre_comercial,
                    $negocio->id
                );
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'negocio';
        }
        $slug = $base;
        $counter = 1;
        while (true) {
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = "{$base}-{$counter}";
            $counter++;
        }
        return $slug;
    }

    protected $casts = [
        'neg_categorias' => 'array',
        'neg_acepto' => 'boolean',
        'neg_virtual' => 'boolean',
        'neg_direccion_confirmada' => 'boolean',
        'neg_latitud' => 'float',
        'neg_longitud' => 'float',
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

    public function citas()
    {
        return $this->hasMany(Cita::class, 'negocio_id');
    }

    public function trabajadores()
    {
        return $this->hasMany(Trabajador::class, 'negocio_id');
    }

    public function fotos()
    {
        return $this->hasMany(FotoEmpresa::class, 'negocio_id')->orderBy('orden');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'negocio_id')->latest();
    }

    public function scopeWithPerfilData($query)
    {
        return $query->with([
            'servicios',
            'trabajadores',
            'fotos',
            'horarios',
            'bloqueos',
            'resenas' => fn($q) => $q->with('user')->latest(),
        ]);
    }
}
