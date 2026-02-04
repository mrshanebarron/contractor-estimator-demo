<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EstimateController extends Controller
{
    public function index()
    {
        $estimates = Auth::user()->estimates()
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('estimates.index', compact('estimates'));
    }

    public function create()
    {
        return view('estimates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_type' => 'nullable|string|max:50',
            'labor_hours' => 'required|numeric|min:0',
            'labor_rate' => 'required|numeric|min:0',
            'materials' => 'nullable|array',
            'materials.*.name' => 'required|string|max:255',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.unit_cost' => 'required|numeric|min:0',
            'overhead_percent' => 'required|numeric|min:0|max:100',
            'profit_percent' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['materials'] = $validated['materials'] ?? [];

        Estimate::create($validated);

        return redirect()->route('estimates.index')
            ->with('success', 'Estimate created successfully.');
    }

    public function show(Estimate $estimate)
    {
        Gate::authorize('view', $estimate);

        return view('estimates.show', compact('estimate'));
    }

    public function edit(Estimate $estimate)
    {
        Gate::authorize('update', $estimate);

        return view('estimates.edit', compact('estimate'));
    }

    public function update(Request $request, Estimate $estimate)
    {
        Gate::authorize('update', $estimate);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_type' => 'nullable|string|max:50',
            'labor_hours' => 'required|numeric|min:0',
            'labor_rate' => 'required|numeric|min:0',
            'materials' => 'nullable|array',
            'materials.*.name' => 'required|string|max:255',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.unit_cost' => 'required|numeric|min:0',
            'overhead_percent' => 'required|numeric|min:0|max:100',
            'profit_percent' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['materials'] = $validated['materials'] ?? [];

        $estimate->update($validated);

        return redirect()->route('estimates.show', $estimate)
            ->with('success', 'Estimate updated successfully.');
    }

    public function destroy(Estimate $estimate)
    {
        Gate::authorize('delete', $estimate);

        $estimate->delete();

        return redirect()->route('estimates.index')
            ->with('success', 'Estimate deleted.');
    }

    public function duplicate(Estimate $estimate)
    {
        Gate::authorize('view', $estimate);

        $new = $estimate->replicate();
        $new->name = $estimate->name . ' (Copy)';
        $new->save();

        return redirect()->route('estimates.edit', $new)
            ->with('success', 'Estimate duplicated.');
    }

    public function compare(Request $request)
    {
        $ids = $request->input('ids', []);

        if (count($ids) < 2) {
            return redirect()->route('estimates.index')
                ->with('error', 'Select at least 2 estimates to compare.');
        }

        $estimates = Estimate::whereIn('id', $ids)
            ->where('user_id', Auth::id())
            ->get();

        return view('estimates.compare', compact('estimates'));
    }
}
