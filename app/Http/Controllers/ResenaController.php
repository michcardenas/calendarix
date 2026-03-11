<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Resena;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cita_id'    => 'required|exists:citas,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $cita = Cita::findOrFail($request->cita_id);

        if ($cita->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tenés permiso para reseñar esta cita.');
        }

        if ($cita->estado !== 'completada') {
            return redirect()->back()->with('error', 'Solo podés reseñar citas completadas.');
        }

        if (Resena::where('cita_id', $cita->id)->exists()) {
            return redirect()->back()->with('error', 'Ya dejaste una reseña para esta cita.');
        }

        Resena::create([
            'cita_id'    => $cita->id,
            'negocio_id' => $cita->negocio_id,
            'user_id'    => Auth::id(),
            'rating'     => $request->rating,
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Reseña enviada correctamente.');
    }

    /**
     * Mostrar formulario publico de calificacion (signed URL, no requiere auth).
     */
    public function calificar(Request $request, Cita $cita)
    {
        $cita->load(['servicio', 'trabajador', 'negocio']);

        if ($cita->estado !== 'completada') {
            abort(404);
        }

        $resenaExistente = Resena::where('cita_id', $cita->id)->first();

        return view('resenas.calificar', [
            'cita'             => $cita,
            'negocio'          => $cita->negocio,
            'yaCalificada'     => (bool) $resenaExistente,
            'resenaExistente'  => $resenaExistente,
        ]);
    }

    /**
     * Guardar reseña desde formulario publico (no requiere auth).
     */
    public function calificarStore(Request $request, Cita $cita)
    {
        $request->validate([
            'rating'     => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        if ($cita->estado !== 'completada') {
            return back()->with('error', 'Solo se pueden calificar citas completadas.');
        }

        if (Resena::where('cita_id', $cita->id)->exists()) {
            return back()->with('error', 'Esta cita ya fue calificada.');
        }

        Resena::create([
            'cita_id'         => $cita->id,
            'negocio_id'      => $cita->negocio_id,
            'user_id'         => Auth::id(),
            'nombre_cliente'  => $cita->nombre_cliente,
            'email_cliente'   => $cita->email_cliente,
            'rating'          => $request->rating,
            'comentario'      => $request->comentario,
        ]);

        return redirect()->signedRoute('resena.calificar', ['cita' => $cita->id]);
    }

    public function responder(Request $request, $resenaId)
    {
        $request->validate([
            'respuesta_negocio' => 'required|string|max:1000',
        ]);

        $resena = Resena::findOrFail($resenaId);
        $negocio = Negocio::findOrFail($resena->negocio_id);

        if ($negocio->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tenés permiso para responder esta reseña.');
        }

        $resena->update([
            'respuesta_negocio' => $request->respuesta_negocio,
            'respuesta_fecha'   => now(),
        ]);

        return redirect()->back()->with('success', 'Respuesta enviada correctamente.');
    }
}
