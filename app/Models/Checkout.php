<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Empresa\Empresa;
class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'negocio_id',
        'producto_id',
        'user_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
        'metodo_pago',
        'estado_pago',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
