<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->orderBy('price')->get();
        return view('admin.plans.index', compact('plans') + ['activeMenu' => 'plans']);
    }

    public function create()
    {
        return view('admin.plans.create', ['activeMenu' => 'plans.create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                              => 'required|string|max:255',
            'description'                       => 'nullable|string',
            'price'                             => 'required|numeric|min:0',
            'currency'                          => 'required|string|size:3',
            'interval'                          => 'required|in:monthly,yearly',
            'max_professionals'                 => 'nullable|integer|min:1',
            'price_per_additional_professional' => 'nullable|numeric|min:0',
            'sort_order'                        => 'nullable|integer|min:0',
        ]);

        $data['slug']                   = Str::slug($data['name']);
        $data['sort_order']             = $data['sort_order'] ?? 0;
        $data['crm_ia_enabled']         = $request->boolean('crm_ia_enabled');

        $data['multi_branch_enabled']   = $request->boolean('multi_branch_enabled');
        $data['whatsapp_reminders']     = $request->boolean('whatsapp_reminders');
        $data['email_reminders']        = $request->boolean('email_reminders');
        $data['is_active']              = $request->boolean('is_active');

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan creado correctamente.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan') + ['activeMenu' => 'plans']);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name'                              => 'required|string|max:255',
            'description'                       => 'nullable|string',
            'price'                             => 'required|numeric|min:0',
            'currency'                          => 'required|string|size:3',
            'interval'                          => 'required|in:monthly,yearly',
            'max_professionals'                 => 'nullable|integer|min:1',
            'price_per_additional_professional' => 'nullable|numeric|min:0',
            'sort_order'                        => 'nullable|integer|min:0',
        ]);

        $data['slug']                   = Str::slug($data['name']);
        $data['sort_order']             = $data['sort_order'] ?? 0;
        $data['crm_ia_enabled']         = $request->boolean('crm_ia_enabled');

        $data['multi_branch_enabled']   = $request->boolean('multi_branch_enabled');
        $data['whatsapp_reminders']     = $request->boolean('whatsapp_reminders');
        $data['email_reminders']        = $request->boolean('email_reminders');
        $data['is_active']              = $request->boolean('is_active');

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan eliminado correctamente.');
    }

    public function toggleActive(Plan $plan)
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        return back()->with('success', 'Estado del plan actualizado.');
    }
}
