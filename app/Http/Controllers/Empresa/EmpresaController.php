<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio; 

class EmpresaController extends Controller
{
    public function dashboard($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.dashboard', [
            'empresa' => $empresa,
            'currentPage' => 'dashboard'
        ]);
    }

    public function configuracion($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion'
        ]);
    }

    public function agenda($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.agenda', [
            'empresa' => $empresa,
            'currentPage' => 'agenda'
        ]);
    }

    public function clientes($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.clientes', [
            'empresa' => $empresa,
            'currentPage' => 'clientes'
        ]);
    }

    // Métodos para las subsecciones de configuración
    public function configCentros($id)
    {
        $empresa = Negocio::findOrFail($id);

        // Crear colección de centros virtual (puedes cambiar luego por tabla real)
        $centros = collect();
        if ($empresa->neg_direccion) {
            $centros->push([
                'nombre' => $empresa->neg_nombre_comercial ?? 'Centro principal',
                'direccion' => $empresa->neg_direccion,
            ]);
        }

        return view('empresa.configuracion.centros', [
            'empresa' => $empresa,
            'centros' => $centros,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'centros'
        ]);
    }

    public function negocio($id)
    {
        $empresa = negocio::findOrFail($id);
        return view('empresa.configuracion.negocio', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'negocio',
        ]);
    }

    public function configCitas($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion.citas', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'citas'
        ]);
    }

    public function configVentas($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion.ventas', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'ventas'
        ]);
    }

    public function configFacturacion($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion.facturacion', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'facturacion'
        ]);
    }

    public function configEquipo($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion.equipo', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'equipo'
        ]);
    }

    public function configFormularios($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion.formularios', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'formularios'
        ]);
    }

    public function configPagos($id)
    {
        $empresa = Negocio::findOrFail($id);

        
        return view('empresa.configuracion.pagos', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'pagos'
        ]);
    }
}