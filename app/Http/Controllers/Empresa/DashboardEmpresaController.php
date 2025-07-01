<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;

class DashboardEmpresaController extends Controller
{
    public function index($id)
    {
        $empresa = Negocio::findOrFail($id); // <- Asegúrate de tener el modelo correcto
        return view('empresa.dashboard', compact('empresa'));
    }
}
