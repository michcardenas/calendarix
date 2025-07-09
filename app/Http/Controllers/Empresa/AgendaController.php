<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa\Empresa;

class AgendaController extends Controller
{
    public function index($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresa.agenda', compact('empresa'));
    }
}

