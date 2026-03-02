<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BambooPaymentLog;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SuscripcionAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan'])->latest();

        // Filtro por status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Busqueda por nombre/email del usuario
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suscripciones = $query->paginate(15)->withQueryString();

        // Conteos para tabs
        $counts = [
            'all'            => Subscription::count(),
            'active'         => Subscription::where('status', 'active')->count(),
            'trial'          => Subscription::where('status', 'trial')->count(),
            'cancelled'      => Subscription::where('status', 'cancelled')->count(),
            'payment_failed' => Subscription::where('status', 'payment_failed')->count(),
            'expired'        => Subscription::where('status', 'expired')->count(),
        ];

        return view('admin.suscripciones.index', [
            'suscripciones' => $suscripciones,
            'counts'        => $counts,
            'activeMenu'    => 'suscripciones',
            'currentStatus' => $request->status ?? 'all',
            'search'        => $request->search ?? '',
        ]);
    }

    public function show(Subscription $suscripcion)
    {
        $suscripcion->load(['user', 'plan']);

        $logs = BambooPaymentLog::where('subscription_id', $suscripcion->id)
            ->orWhere(function ($q) use ($suscripcion) {
                $q->where('user_id', $suscripcion->user_id)
                  ->whereNull('subscription_id');
            })
            ->latest()
            ->take(20)
            ->get();

        return view('admin.suscripciones.show', [
            'suscripcion' => $suscripcion,
            'logs'        => $logs,
            'activeMenu'  => 'suscripciones',
        ]);
    }

    public function updateStatus(Request $request, Subscription $suscripcion)
    {
        $request->validate([
            'status' => 'required|in:active,trial,cancelled,expired,payment_failed',
        ]);

        $oldStatus = $suscripcion->status;
        $suscripcion->update([
            'status' => $request->status,
            'cancelled_at' => $request->status === 'cancelled' ? now() : $suscripcion->cancelled_at,
        ]);

        return redirect()->back()
            ->with('success', "Estado cambiado de '{$oldStatus}' a '{$request->status}'.");
    }
}
