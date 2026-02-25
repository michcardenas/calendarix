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
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $empresa = Negocio::findOrFail($empresaId);

        $maxOrden = FotoEmpresa::where('negocio_id', $empresa->id)->max('orden') ?? 0;

        $path = $request->file('foto')->store("galerias/{$empresa->id}", 'public');

        FotoEmpresa::create([
            'negocio_id' => $empresa->id,
            'ruta'       => $path,
            'orden'      => $maxOrden + 1,
        ]);

        return redirect()->back()->with('success', 'Foto subida correctamente.');
    }

    public function destroy($empresaId, $fotoId)
    {
        $foto = FotoEmpresa::where('negocio_id', $empresaId)->findOrFail($fotoId);

        Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada correctamente.');
    }
}
