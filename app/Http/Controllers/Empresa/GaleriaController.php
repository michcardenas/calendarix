<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Empresa\FotoEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    public function index($empresaId)
    {
        $empresa = Negocio::findOrFail($empresaId);
        $fotos = FotoEmpresa::where('negocio_id', $empresa->id)->orderBy('orden')->get();

        return view('empresa.galeria.index', [
            'empresa'        => $empresa,
            'fotos'          => $fotos,
            'currentPage'    => 'galeria',
            'currentSubPage' => 'galeria',
        ]);
    }

    public function store(Request $request, $empresaId)
    {
        $request->validate([
            'fotos'   => 'required|array|min:1',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $empresa = Negocio::findOrFail($empresaId);
        $actuales = FotoEmpresa::where('negocio_id', $empresa->id)->count();
        $maxOrden = FotoEmpresa::where('negocio_id', $empresa->id)->max('orden') ?? 0;
        $subidas = 0;

        foreach ($request->file('fotos') as $foto) {
            if ($actuales + $subidas >= 12) break;

            $path = $foto->store("galerias/{$empresa->id}", 'public');
            $maxOrden++;

            FotoEmpresa::create([
                'negocio_id' => $empresa->id,
                'ruta'       => $path,
                'orden'      => $maxOrden,
            ]);

            $subidas++;
        }

        $msg = $subidas === 1 ? 'Foto subida correctamente.' : "$subidas fotos subidas correctamente.";
        return redirect()->back()->with('success', $msg);
    }

    public function destroy($empresaId, $fotoId)
    {
        $foto = FotoEmpresa::where('negocio_id', $empresaId)->findOrFail($fotoId);

        Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada correctamente.');
    }
}
