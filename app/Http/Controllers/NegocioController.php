<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Resena;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    public function show($slug)
    {
        $negocio = Negocio::where('slug', $slug)
            ->withPerfilData()
            ->firstOrFail();

        $resenas = $negocio->resenas;
        $promedioRating = $resenas->count() ? round($resenas->avg('rating'), 1) : null;

        return view('negocio.perfil', [
            'negocio'         => $negocio,
            'trabajadores'    => $negocio->trabajadores,
            'resenas'         => $resenas,
            'promedioRating'  => $promedioRating,
            'totalResenas'    => $resenas->count(),
        ]);
    }
}
