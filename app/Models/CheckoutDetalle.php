<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\ServicioEmpresa;

class CheckoutDetalle extends Model
{
    use HasFactory;

    protected $table = 'checkout_detalles';

    protected $fillable = [
        'checkout_id',
        'producto_id',
        'servicio_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    // Relaciones
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function servicio()
    {
        return $this->belongsTo(ServicioEmpresa::class, 'servicio_id');
    }
}
