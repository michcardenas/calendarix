<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Empresa\Empresa;
use App\Models\Empresa\ServicioEmpresa;

class Checkout extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        'estado_pago',
        'metodo_pago',
        'user_id',
        'negocio_id',
        'total',
    ];

    // Relaciones
    public function negocio()
    {
        return $this->belongsTo(Empresa::class, 'negocio_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function servicio()
    {
        return $this->belongsTo(\App\Models\Empresa\ServicioEmpresa::class, 'servicio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(CheckoutDetalle::class);
    }
}
