<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\ServicioEmpresa;
use App\Models\Producto; // ✅ importa el modelo si lo usas aquí

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

    protected $casts = [
        'cantidad'        => 'int',
        'precio_unitario' => 'decimal:2',
        'precio_total'    => 'decimal:2',
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
