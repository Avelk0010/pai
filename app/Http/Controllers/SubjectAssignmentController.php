<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Group;
use App\Models\SubjectAssignment;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SubjectAssignmentController extends Controller
{
    /**
     * Display a listing of subject assignments.
     */
    public function index(): View
    {
        $assignments = SubjectAssignment::with(['teacher', 'subject.academicPlan.gradeLevel', 'group'])
            ->where('academic_year', date('Y'))
            ->orderBy('subject_id')
            ->orderBy('group_id')
            ->paginate(20);

        $stats = [
            'total_assignments' => SubjectAssignment::where('academic_year', date('Y'))->count(),
            'teachers_with_assignments' => SubjectAssignment::where('academic_year', date('Y'))
                ->distinct('teacher_id')->count(),
            'subjects_assigned' => SubjectAssignment::where('academic_year', date('Y'))
                ->distinct('subject_id')->count(),
        ];

        return view('assignments.index', compact('assignments', 'stats'));
    }

    /**
     * Show the form for creating new assignments.
     */
    public function create(): View
    {
        $teachers = User::where('role', 'teacher')
            ->where('status', true)
            ->with('subjectAssignments')
            ->orderBy('first_name')
            ->get();

        $subjects = Subject::with(['academicPlan.gradeLevel'])
            ->where('status', true)
            ->orderBy('name')
            ->get()
            ->groupBy(function($subject) {
                return $subject->academicPlan->gradeLevel->grade_name ?? 'Sin Grado';
            });

        $groups = Group::with('gradeLevel')
            ->where('status', true)
            ->orderBy('grade_level_id')
            ->orderBy('group_letter')
            ->get()
            ->groupBy(function($group) {
                return $group->gradeLevel->grade_name ?? 'Sin Grado';
            });

        return view('assignments.create', compact('teachers', 'subjects', 'groups'));
    }

    /**
     * Store newly created assignments.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_enabled' => 'array',
            'assignments' => 'array',
        ]);

        $teacher = User::find($validated['teacher_id']);
        if ($teacher->role !== 'teacher') {
            return redirect()->back()->withErrors(['teacher_id' => 'El usuario seleccionado no es un profesor.']);
        }

        $assignmentsCreated = 0;

        DB::transaction(function () use ($request, $validated, &$assignmentsCreated) {
            // Get enabled subjects
            $enabledSubjects = $request->input('subject_enabled', []);
            
            foreach ($enabledSubjects as $subjectId => $enabled) {
                if ($enabled) {
                    // Get the subject to validate grade level
                    $subject = Subject::with('academicPlan.gradeLevel')->find($subjectId);
                    if (!$subject) continue;
                    
                    $subjectGradeId = $subject->academicPlan->gradeLevel->id;
                    
                    // Get groups for this subject
                    $groups = $request->input("assignments.{$subjectId}.groups", []);
                    
                    foreach ($groups as $groupId) {
                        // Validate that the group belongs to the same grade level as the subject
                        $group = Group::find($groupId);
                        if (!$group || $group->grade_level_id !== $subjectGradeId) {
                            continue; // Skip invalid group-subject combinations
                        }
                        
                        SubjectAssignment::firstOrCreate([
                            'teacher_id' => $validated['teacher_id'],
                            'subject_id' => $subjectId,
                            'group_id' => $groupId,
                            'grade_level_id' => $subjectGradeId,
                            'academic_year' => date('Y')
                        ]);
                        $assignmentsCreated++;
                    }
                }
            }
        });

        if ($assignmentsCreated > 0) {
            return redirect()->route('assignments.teacher', $teacher)
                ->with('success', "Se crearon {$assignmentsCreated} asignaciones exitosamente.");
        } else {
            return redirect()->back()
                ->with('warning', 'No se crearon asignaciones. Asegúrate de seleccionar materias y grupos.');
        }
    }

    /**
     * Show assignments for a specific teacher.
     */
    public function teacher(User $user): View
    {
        if ($user->role !== 'teacher') {
            abort(404, 'Usuario no es un profesor.');
        }

        $assignments = SubjectAssignment::with(['subject.academicPlan.gradeLevel', 'group'])
            ->where('teacher_id', $user->id)
            ->where('academic_year', date('Y'))
            ->get()
            ->groupBy(function($assignment) {
                return $assignment->subject->academicPlan->gradeLevel->grade_name ?? 'Sin Grado';
            });

        return view('assignments.teacher', compact('user', 'assignments'));
    }

    /**
     * Show assignments for a specific subject.
     */
    public function subject(Subject $subject): View
    {
        $assignments = SubjectAssignment::with(['teacher', 'group'])
            ->where('subject_id', $subject->id)
            ->where('academic_year', date('Y'))
            ->get()
            ->groupBy('group.group_letter');

        return view('assignments.subject', compact('subject', 'assignments'));
    }

    /**
     * Remove an assignment.
     */
    public function destroy(SubjectAssignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->back()
            ->with('success', 'Asignación eliminada exitosamente.');
    }

    /**
     * Bulk update assignments for a teacher.
     */
    public function updateTeacherAssignments(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'teacher') {
            abort(404, 'Usuario no es un profesor.');
        }

        $validated = $request->validate([
            'assignments' => 'array',
            'assignments.*.subject_id' => 'exists:subjects,id',
            'assignments.*.groups' => 'array',
            'assignments.*.groups.*' => 'exists:groups,id',
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Remove existing assignments for this academic year
            SubjectAssignment::where('teacher_id', $user->id)
                ->where('academic_year', date('Y'))
                ->delete();

            // Create new assignments
            if (isset($validated['assignments'])) {
                foreach ($validated['assignments'] as $assignment) {
                    foreach ($assignment['groups'] as $groupId) {
                        SubjectAssignment::create([
                            'teacher_id' => $user->id,
                            'subject_id' => $assignment['subject_id'],
                            'group_id' => $groupId,
                            'academic_year' => date('Y')
                        ]);
                    }
                }
            }
        });

        return redirect()->back()
            ->with('success', 'Asignaciones actualizadas exitosamente.');
    }
}
