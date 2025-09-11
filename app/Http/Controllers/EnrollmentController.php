<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the enrollments.
     */
    public function index(): View
    {
        $enrollments = Enrollment::with(['student', 'group.gradeLevel'])
            ->orderBy('enrollment_date', 'desc')
            ->paginate(20);

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create(): View
    {
        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();
            
        $groups = Group::with('gradeLevel')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('enrollments.create', compact('students', 'groups'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
            'enrollment_date' => 'required|date',
            'academic_year' => 'required|integer|min:2000|max:' . (date('Y') + 10),
            'is_active' => 'boolean'
        ]);

        // Validate that student_id is actually a student
        $student = User::find($validated['student_id']);
        if ($student->role !== 'student') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_id' => 'El usuario seleccionado no es un estudiante.']);
        }

        // Check if student is already enrolled in an active group
        $existingEnrollment = Enrollment::where('student_id', $validated['student_id'])
            ->where('is_active', true)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_id' => 'El estudiante ya está inscrito en un grupo activo.']);
        }

        // Check if group has available capacity
        $group = Group::find($validated['group_id']);
        $currentEnrollment = $group->enrollments()->where('is_active', true)->count();
        
        if ($currentEnrollment >= $group->max_students) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['group_id' => 'El grupo ha alcanzado su capacidad máxima.']);
        }

        $validated['is_active'] = $request->has('is_active');

        Enrollment::create($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Inscripción creada exitosamente.');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment): View
    {
        $enrollment->load([
            'student',
            'group.gradeLevel',
            'group.homeRoomTeacher'
        ]);

        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit(Enrollment $enrollment): View
    {
        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();
            
        $groups = Group::with('gradeLevel')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('enrollments.edit', compact('enrollment', 'students', 'groups'));
    }

    /**
     * Update the specified enrollment in storage.
     */
    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
            'enrollment_date' => 'required|date',
            'is_active' => 'boolean'
        ]);

        // Validate that student_id is actually a student
        $student = User::find($validated['student_id']);
        if ($student->role !== 'student') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_id' => 'El usuario seleccionado no es un estudiante.']);
        }

        // Check if student is already enrolled in another active group (excluding current enrollment)
        $existingEnrollment = Enrollment::where('student_id', $validated['student_id'])
            ->where('is_active', true)
            ->where('id', '!=', $enrollment->id)
            ->first();

        if ($existingEnrollment && $request->has('is_active')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_id' => 'El estudiante ya está inscrito en otro grupo activo.']);
        }

        // Check if group has available capacity (if changing group)
        if ($validated['group_id'] != $enrollment->group_id) {
            $group = Group::find($validated['group_id']);
            $currentEnrollment = $group->enrollments()->where('is_active', true)->count();
            
            if ($currentEnrollment >= $group->max_students) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['group_id' => 'El grupo ha alcanzado su capacidad máxima.']);
            }
        }

        $validated['is_active'] = $request->has('is_active');

        $enrollment->update($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Inscripción actualizada exitosamente.');
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Inscripción eliminada exitosamente.');
    }

    /**
     * Toggle the active status of an enrollment.
     */
    public function toggleStatus(Enrollment $enrollment): RedirectResponse
    {
        // If activating, check if student is already enrolled in another active group
        if (!$enrollment->is_active) {
            $existingEnrollment = Enrollment::where('student_id', $enrollment->student_id)
                ->where('is_active', true)
                ->where('id', '!=', $enrollment->id)
                ->first();

            if ($existingEnrollment) {
                return redirect()->back()
                    ->with('error', 'El estudiante ya está inscrito en otro grupo activo.');
            }

            // Check if group has available capacity
            $currentEnrollment = $enrollment->group->enrollments()->where('is_active', true)->count();
            if ($currentEnrollment >= $enrollment->group->max_students) {
                return redirect()->back()
                    ->with('error', 'El grupo ha alcanzado su capacidad máxima.');
            }
        }

        $enrollment->update([
            'is_active' => !$enrollment->is_active
        ]);

        $status = $enrollment->is_active ? 'activada' : 'desactivada';

        return redirect()->back()
            ->with('success', "Inscripción {$status} exitosamente.");
    }

    /**
     * Enroll a student in a group.
     */
    public function enrollStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id'
        ]);

        // Validate that student_id is actually a student
        $student = User::find($validated['student_id']);
        if ($student->role !== 'student') {
            return redirect()->back()
                ->withErrors(['student_id' => 'El usuario seleccionado no es un estudiante.']);
        }

        // Check if student is already enrolled in an active group
        $existingEnrollment = Enrollment::where('student_id', $validated['student_id'])
            ->where('is_active', true)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->with('error', 'El estudiante ya está inscrito en un grupo activo.');
        }

        // Check if group has available capacity
        $group = Group::find($validated['group_id']);
        $currentEnrollment = $group->enrollments()->where('is_active', true)->count();
        
        if ($currentEnrollment >= $group->max_students) {
            return redirect()->back()
                ->with('error', 'El grupo ha alcanzado su capacidad máxima.');
        }

        Enrollment::create([
            'student_id' => $validated['student_id'],
            'group_id' => $validated['group_id'],
            'enrollment_date' => Carbon::now(),
            'academic_year' => date('Y'), // Año académico actual
            'is_active' => true
        ]);

        return redirect()->back()
            ->with('success', 'Estudiante inscrito exitosamente.');
    }

    /**
     * Unenroll a student from a group.
     */
    public function unenrollStudent(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update(['is_active' => false]);

        return redirect()->back()
            ->with('success', 'Estudiante desinscrito exitosamente.');
    }

    /**
     * Transfer a student to another group.
     */
    public function transferStudent(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $validated = $request->validate([
            'new_group_id' => 'required|exists:groups,id'
        ]);

        // Check if new group has available capacity
        $newGroup = Group::find($validated['new_group_id']);
        $currentEnrollment = $newGroup->enrollments()->where('is_active', true)->count();
        
        if ($currentEnrollment >= $newGroup->max_students) {
            return redirect()->back()
                ->with('error', 'El grupo de destino ha alcanzado su capacidad máxima.');
        }

        // Deactivate current enrollment
        $enrollment->update(['is_active' => false]);

        // Create new enrollment
        Enrollment::create([
            'student_id' => $enrollment->student_id,
            'group_id' => $validated['new_group_id'],
            'enrollment_date' => Carbon::now(),
            'academic_year' => date('Y'), // Año académico actual
            'is_active' => true
        ]);

        return redirect()->back()
            ->with('success', 'Estudiante transferido exitosamente.');
    }
}
