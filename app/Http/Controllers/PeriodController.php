<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\AcademicPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Period::with(['academicPlan']);

        // Apply filters
        if ($request->filled('academic_plan_id')) {
            $query->where('academic_plan_id', $request->academic_plan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $periods = $query->orderBy('academic_plan_id')
                        ->orderBy('period_order')
                        ->paginate(15);

        $academicPlans = AcademicPlan::where('status', true)->get();

        return view('periods.index', compact('periods', 'academicPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicPlans = AcademicPlan::where('status', true)->get();
        return view('periods.create', compact('academicPlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_plan_id' => 'required|exists:academic_plans,id',
            'name' => 'required|string|max:100',
            'period_order' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,finished',
        ]);

        Period::create($validated);

        return redirect()->route('periods.index')
                        ->with('success', 'Período creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Period $period): View
    {
        $period->load(['academicPlan', 'activities.subject', 'activities.teacher']);
        return view('periods.show', compact('period'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Period $period): View
    {
        $academicPlans = AcademicPlan::where('status', true)->get();
        return view('periods.edit', compact('period', 'academicPlans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Period $period): RedirectResponse
    {
        $validated = $request->validate([
            'academic_plan_id' => 'required|exists:academic_plans,id',
            'name' => 'required|string|max:100',
            'period_order' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,finished',
        ]);

        $period->update($validated);

        return redirect()->route('periods.index')
                        ->with('success', 'Período actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period): RedirectResponse
    {
        // Check if period has activities
        if ($period->activities()->count() > 0) {
            return redirect()->route('periods.index')
                            ->with('error', 'No se puede eliminar el período porque tiene actividades asociadas.');
        }

        $period->delete();

        return redirect()->route('periods.index')
                        ->with('success', 'Período eliminado exitosamente.');
    }

    /**
     * Toggle the status of a period.
     */
    public function toggleStatus(Request $request, Period $period): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:planned,active,finished'
        ]);

        $period->update(['status' => $validated['status']]);

        return redirect()->back()
                        ->with('success', 'Estado del período actualizado exitosamente.');
    }
}