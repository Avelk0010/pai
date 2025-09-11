<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GradeLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * Display a listing of the groups.
     */
    public function index(): View
    {
        $groups = Group::with(['gradeLevel'])
            ->withCount('enrollments')
            ->orderBy('academic_year', 'desc')
            ->orderBy('grade_level_id')
            ->orderBy('group_letter')
            ->paginate(15);

        $stats = [
            'total' => Group::count(),
            'active' => Group::where('status', true)->count(),
            'inactive' => Group::where('status', false)->count(),
            'current_year' => Group::where('academic_year', date('Y'))->count()
        ];

        return view('groups.index', compact('groups', 'stats'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create(): View
    {
        $gradeLevels = GradeLevel::where('status', true)
            ->orderBy('grade_number')
            ->get();

        $currentYear = date('Y');
        
        return view('groups.create', compact('gradeLevels', 'currentYear'));
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grade_level_id' => 'required|exists:grade_levels,id',
            'group_letter' => 'required|string|max:5',
            'academic_year' => 'required|integer|min:2020|max:2030',
            'max_students' => 'required|integer|min:5|max:60',
            'status' => 'boolean'
        ]);

        // Check if combination already exists
        $exists = Group::where('grade_level_id', $validated['grade_level_id'])
            ->where('group_letter', $validated['group_letter'])
            ->where('academic_year', $validated['academic_year'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['group_letter' => 'Ya existe un grupo con esta letra para este grado y año académico.']);
        }

        // Check if grade level is active
        $gradeLevel = GradeLevel::find($validated['grade_level_id']);
        if (!$gradeLevel->status) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['grade_level_id' => 'No se puede crear un grupo para un nivel de grado inactivo.']);
        }

        $validated['status'] = $request->has('status');

        Group::create($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Grupo creado exitosamente.');
    }

    /**
     * Display the specified group.
     */
    public function show(Group $group): View
    {
        $group->load([
            'gradeLevel',
            'enrollments' => function($query) {
                $query->with('student');
            },
            'subjectAssignments.subject',
            'activities' => function($query) {
                $query->latest()->take(5);
            }
        ]);

        $stats = [
            'total_students' => $group->enrollments()->count(),
            'capacity_used' => round(($group->enrollments()->count() / $group->max_students) * 100, 1),
            'available_spots' => $group->max_students - $group->enrollments()->count(),
            'total_subjects' => $group->subjectAssignments()->count(),
            'recent_activities' => $group->activities()->count()
        ];

        return view('groups.show', compact('group', 'stats'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group): View
    {
        $gradeLevels = GradeLevel::where('status', true)
            ->orderBy('grade_number')
            ->get();

        $currentEnrollment = $group->enrollments()->count();

        return view('groups.edit', compact('group', 'gradeLevels', 'currentEnrollment'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group): RedirectResponse
    {
        $validated = $request->validate([
            'grade_level_id' => 'required|exists:grade_levels,id',
            'group_letter' => 'required|string|max:5',
            'academic_year' => 'required|integer|min:2020|max:2030',
            'max_students' => 'required|integer|min:5|max:60',
            'status' => 'boolean'
        ]);

        // Check if combination already exists (excluding current record)
        $exists = Group::where('grade_level_id', $validated['grade_level_id'])
            ->where('group_letter', $validated['group_letter'])
            ->where('academic_year', $validated['academic_year'])
            ->where('id', '!=', $group->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['group_letter' => 'Ya existe un grupo con esta letra para este grado y año académico.']);
        }

        // Check if reducing max_students would exceed current enrollment
        $currentEnrollment = $group->enrollments()->count();
        if ($validated['max_students'] < $currentEnrollment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['max_students' => "No se puede reducir el máximo de estudiantes por debajo del número actual de inscritos ({$currentEnrollment})."]);
        }

        // Check if grade level is active
        $gradeLevel = GradeLevel::find($validated['grade_level_id']);
        if (!$gradeLevel->status) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['grade_level_id' => 'No se puede asignar a un nivel de grado inactivo.']);
        }

        $validated['status'] = $request->has('status');

        $group->update($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Grupo actualizado exitosamente.');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(Group $group): RedirectResponse
    {
        // Check if the group has enrollments
        $enrollments = $group->enrollments()->count();
        if ($enrollments > 0) {
            return redirect()->route('groups.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene estudiantes inscritos.');
        }

        // Check if the group has subject assignments
        if ($group->subjectAssignments()->count() > 0) {
            return redirect()->route('groups.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene asignaciones de materias.');
        }

        // Check if the group has activities
        if ($group->activities()->count() > 0) {
            return redirect()->route('groups.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene actividades asociadas.');
        }

        $groupName = $group->full_name;
        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', "Grupo {$groupName} eliminado exitosamente.");
    }

    /**
     * Toggle the active status of a group.
     */
    public function toggleStatus(Group $group): RedirectResponse
    {
        // If deactivating, check for enrollments
        if ($group->status && $group->enrollments()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede desactivar el grupo porque tiene estudiantes inscritos.');
        }

        $group->update([
            'status' => !$group->status
        ]);

        $status = $group->status ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "Grupo {$status} exitosamente.");
    }

    /**
     * Get group statistics.
     */
    public function statistics(Group $group): View
    {
        $group->load([
            'gradeLevel',
            'enrollments.student'
        ]);

        $totalStudents = $group->enrollments()->count();
        
        $stats = [
            'total_students' => $totalStudents,
            'max_capacity' => $group->max_students,
            'capacity_percentage' => $totalStudents > 0 ? round(($totalStudents / $group->max_students) * 100, 1) : 0,
            'available_spots' => $group->max_students - $totalStudents,
            'male_students' => $group->enrollments()
                ->whereHas('student', function ($query) {
                    $query->where('gender', 'male');
                })->count(),
            'female_students' => $group->enrollments()
                ->whereHas('student', function ($query) {
                    $query->where('gender', 'female');
                })->count(),
            'subjects_count' => $group->subjectAssignments()->count(),
            'activities_count' => $group->activities()->count(),
        ];

        // Additional stats
        $stats['gender_distribution'] = [
            'male_percentage' => $totalStudents > 0 ? round(($stats['male_students'] / $totalStudents) * 100, 1) : 0,
            'female_percentage' => $totalStudents > 0 ? round(($stats['female_students'] / $totalStudents) * 100, 1) : 0,
        ];

        return view('groups.statistics', compact('group', 'stats'));
    }
}
