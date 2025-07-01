<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;

class EditorEmpresaController extends Controller
{
public function index($id)
    {
        $negocio = Negocio::with('servicios')->findOrFail($id);

        // Suponiendo que tu modelo ya tiene este método definido
        $bloques = $negocio->getBloquesConfigurados();

        return view('empresa.editor-dashboard', compact('negocio', 'bloques'));
    }
}


