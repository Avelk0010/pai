<?php

namespace App\Http\Controllers;

use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeLevelController extends Controller
{
    /**
     * Display a listing of the grade levels.
     */
    public function index(): View
    {
        $gradeLevels = GradeLevel::withCount(['groups', 'academicPlans'])
            ->orderBy('grade_number')
            ->paginate(10);

        return view('grade-levels.index', compact('gradeLevels'));
    }

    /**
     * Show the form for creating a new grade level.
     */
    public function create(): View
    {
        return view('grade-levels.create');
    }

    /**
     * Store a newly created grade level in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grade_number' => 'required|integer|min:1|max:12|unique:grade_levels,grade_number',
            'grade_name' => 'required|string|max:50',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        GradeLevel::create($validated);

        return redirect()->route('grade-levels.index')
            ->with('success', 'Nivel de grado creado exitosamente.');
    }

    /**
     * Display the specified grade level.
     */
    public function show(GradeLevel $gradeLevel): View
    {
        $gradeLevel->load(['groups.enrollments', 'academicPlans.subjects']);
        
        return view('grade-levels.show', compact('gradeLevel'));
    }

    /**
     * Show the form for editing the specified grade level.
     */
    public function edit(GradeLevel $gradeLevel): View
    {
        return view('grade-levels.edit', compact('gradeLevel'));
    }

    /**
     * Update the specified grade level in storage.
     */
    public function update(Request $request, GradeLevel $gradeLevel): RedirectResponse
    {
        $validated = $request->validate([
            'grade_number' => 'required|integer|min:1|max:12|unique:grade_levels,grade_number,' . $gradeLevel->id,
            'grade_name' => 'required|string|max:50',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        $gradeLevel->update($validated);

        return redirect()->route('grade-levels.index')
            ->with('success', 'Nivel de grado actualizado exitosamente.');
    }

    /**
     * Remove the specified grade level from storage.
     */
    public function destroy(GradeLevel $gradeLevel): RedirectResponse
    {
        // Check if the grade level has associated groups or academic plans
        $groupsCount = $gradeLevel->groups()->count();
        $plansCount = $gradeLevel->academicPlans()->count();

        if ($groupsCount > 0 || $plansCount > 0) {
            return redirect()->route('grade-levels.index')
                ->with('error', "No se puede eliminar el grado porque tiene {$groupsCount} grupos y {$plansCount} planes académicos asociados.");
        }

        $gradeLevel->delete();

        return redirect()->route('grade-levels.index')
            ->with('success', 'Nivel de grado eliminado exitosamente.');
    }

    /**
     * Toggle the status of the grade level.
     */
    public function toggleStatus(GradeLevel $gradeLevel): RedirectResponse
    {
        $gradeLevel->status = !$gradeLevel->status;
        $gradeLevel->save();

        $statusText = $gradeLevel->status ? 'activado' : 'desactivado';
        
        return back()->with('success', "Nivel de grado {$statusText} exitosamente.");
    }
}
