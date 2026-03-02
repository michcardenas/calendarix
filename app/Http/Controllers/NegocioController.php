<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Resena;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    public function explorar(Request $request)
    {
        $query = Negocio::withCount('resenas')
            ->withAvg('resenas', 'rating');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('neg_nombre_comercial', 'like', "%{$q}%")
                  ->orWhere('neg_categorias', 'like', "%{$q}%")
                  ->orWhere('neg_direccion', 'like', "%{$q}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('neg_categorias', 'like', '%"' . $request->categoria . '"%');
        }

        $negocios = $query->latest()->paginate(12);

        return view('negocio.explorar', compact('negocios'));
    }

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
