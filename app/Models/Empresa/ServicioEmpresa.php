<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;

class ServicioEmpresa extends Model
{
    protected $table = 'servicios_empresa';

    protected $fillable = [
        'negocio_id',
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'duracion',      // ← NUEVO
    ];

    public function negocio()
    {
        return $this->belongsTo(\App\Models\Negocio::class, 'negocio_id');
    }

   public function guardarServicio(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'categoria' => 'required',
        ]);

        ServicioEmpresa::create([
            'negocio_id' => auth()->user()->negocio->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
        ]);

        return redirect()->route('catalogo.servicios')->with('success', 'Servicio creado correctamente.');
    }


}
