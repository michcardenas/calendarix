<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'codigo_barras',
        'marca',
        'unidad_medida',
        'cantidad',
        'descripcion_breve',
        'descripcion_larga',
        'categoria',
        'precio_compra',
        'precio_venta',
        'precio_promocional',
        'activar_oferta',
        'controla_inventario',
        'stock',
        'stock_minimo',
        'estado_publicado',
        'mostrar_en_catalogo',
        'imagen',
    ];

    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class);
    }

}
