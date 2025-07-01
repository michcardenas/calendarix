<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;

class ConfiguracionEmpresaController extends Controller
{
    public function guardarConfiguracion(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        
        $request->validate([
            'bloques' => 'required|array',
            'bloques.*' => 'string|in:servicios,galeria,horario,ubicacion,contacto'
        ]);
        
        $negocio->configuracion_bloques = json_encode($request->bloques);
        $negocio->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Configuración guardada exitosamente'
        ]);
    }
    
    public function obtenerConfiguracion($id)
    {
        $negocio = Negocio::findOrFail($id);
        $bloques = json_decode($negocio->configuracion_bloques, true) ?? [];
        
        return response()->json([
            'bloques' => $bloques
        ]);
    }
    
    public function vistaPrevia($id)
    {
        $negocio = Negocio::with('servicios')->findOrFail($id);
        $bloques = json_decode($negocio->configuracion_bloques, true) ?? [];
        
        return view('empresa.vista-previa', compact('negocio', 'bloques'));
    }
}