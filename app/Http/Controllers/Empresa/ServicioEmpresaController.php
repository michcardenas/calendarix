<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa\ServicioEmpresa;
use App\Models\Negocio;

class ServicioEmpresaController extends Controller
{
    public function guardar(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        
        $request->validate([
            'servicios' => 'required|array|min:1',
            'servicios.*.nombre' => 'required|string|max:255',
            'servicios.*.precio' => 'required|numeric|min:0',
            'servicios.*.descripcion' => 'nullable|string|max:1000',
        ]);
        
        // Obtener IDs de servicios que se van a mantener
        $serviciosParaMantener = collect($request->input('servicios', []))
            ->filter(function($servicio) {
                return isset($servicio['id']) && !empty($servicio['id']);
            })
            ->pluck('id')   
            ->toArray();
            
        // Eliminar servicios que no están en la lista
        ServicioEmpresa::where('negocio_id', $negocio->id)
            ->whereNotIn('id', $serviciosParaMantener)
            ->delete();
        
        // Crear o actualizar servicios
        foreach ($request->input('servicios', []) as $servicioData) {
            ServicioEmpresa::updateOrCreate(
                ['id' => $servicioData['id'] ?? null],
                [
                    'negocio_id'  => $negocio->id,
                    'nombre'      => $servicioData['nombre'],
                    'descripcion' => $servicioData['descripcion'] ?? null,
                    'precio'      => $servicioData['precio'],
                ]
            );
        }
        
        return redirect()->route('empresa.editor.dashboard', $negocio->id)->with('success', 'Servicios actualizados correctamente');

    }
    
    public function mostrar($id)
    {
        $negocio = Negocio::with('servicios')->findOrFail($id);
        return view('empresa.bloques.servicios', compact('negocio'));
    }
    
    public function eliminar(Request $request, $id, $servicioId)
    {
        $negocio = Negocio::findOrFail($id);
        $servicio = ServicioEmpresa::where('negocio_id', $negocio->id)
                                  ->findOrFail($servicioId);
        
        $servicio->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Servicio eliminado correctamente'
        ]);
    }


}