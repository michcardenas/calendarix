<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenProducto extends Model
{
    protected $fillable = ['producto_id', 'ruta'];
    protected $table = 'imagenes_productos';

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
