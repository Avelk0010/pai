<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\AcademicPlan;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Activity;
use App\Models\Period;
use App\Models\StudentPeriodGrade;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index(): View
    {
        // If user is student, redirect to student subjects view
        if (Auth::user()->role === 'student') {
            return $this->studentSubjects();
        }
        
        // If user is teacher, only show assigned subjects
        if (Auth::user()->role === 'teacher') {
            $subjects = Subject::with(['academicPlan.gradeLevel'])
                ->withCount(['subjectAssignments', 'activities', 'periodGrades'])
                ->whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->orderBy('area')
                ->orderBy('name')
                ->paginate(15);

            $stats = [
                'total' => Subject::whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })->count(),
                'active' => Subject::where('status', true)
                    ->whereHas('subjectAssignments', function ($query) {
                        $query->where('teacher_id', Auth::id());
                    })->count(),
                'inactive' => Subject::where('status', false)
                    ->whereHas('subjectAssignments', function ($query) {
                        $query->where('teacher_id', Auth::id());
                    })->count(),
                'mandatory' => Subject::where('is_mandatory', true)
                    ->whereHas('subjectAssignments', function ($query) {
                        $query->where('teacher_id', Auth::id());
                    })->count()
            ];

            // Areas statistics for teacher's subjects
            $areas = Subject::selectRaw('area, COUNT(*) as count')
                ->whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->groupBy('area')
                ->get();
        } else {
            // Admin sees all subjects
            $subjects = Subject::with(['academicPlan.gradeLevel'])
                ->withCount(['subjectAssignments', 'activities', 'periodGrades'])
                ->orderBy('area')
                ->orderBy('name')
                ->paginate(15);

            $stats = [
                'total' => Subject::count(),
                'active' => Subject::where('status', true)->count(),
                'inactive' => Subject::where('status', false)->count(),
                'mandatory' => Subject::where('is_mandatory', true)->count()
            ];

            // Areas statistics
            $areas = Subject::selectRaw('area, COUNT(*) as count')
                ->groupBy('area')
                ->get();
        }

        return view('subjects.index', compact('subjects', 'stats', 'areas'));
    }

        /**
     * Show the form for creating a new subject.
     */
    public function create(): View
    {
        // Only admins can create subjects
        if (Auth::user()->role === 'teacher') {
            abort(403, 'No tienes permisos para crear materias.');
        }

        $academicPlans = AcademicPlan::with('gradeLevel')
            ->orderBy('name')
            ->get();

        $teachers = User::where('role', 'teacher')
            ->where('status', true)
            ->orderBy('first_name')
            ->get();

        return view('subjects.create', compact('academicPlans', 'teachers'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Only admins can create subjects
        if (Auth::user()->role === 'teacher') {
            abort(403, 'No tienes permisos para crear materias.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code',
            'area' => 'required|string|max:255',
            'academic_plan_id' => 'required|exists:academic_plans,id',
            'description' => 'nullable|string|max:1000',
            'curriculum_content' => 'nullable|string',
            'topics' => 'nullable|array',
            'objectives' => 'nullable|string',
            'methodology' => 'nullable|string',
            'evaluation_criteria' => 'nullable|string',
            'resources' => 'nullable|array',
            'prerequisites' => 'nullable|string',
            'hours_per_week' => 'required|integer|min:1|max:40',
            'credits' => 'nullable|integer|min:0|max:20',
            'is_mandatory' => 'boolean',
            'status' => 'boolean',
        ]);

        // Process JSON fields
        $validated['topics'] = $validated['topics'] ?? [];
        $validated['resources'] = $validated['resources'] ?? [];

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Materia creada exitosamente.');
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject): View
    {
        // Teachers can only view subjects they are assigned to
        if (Auth::user()->role === 'teacher') {
            $hasAssignment = $subject->subjectAssignments()
                ->where('teacher_id', Auth::id())
                ->exists();
            
            if (!$hasAssignment) {
                abort(403, 'No tienes permisos para ver esta materia.');
            }
        }

        $subject->load(['academicPlan.gradeLevel', 'subjectAssignments.teacher', 'activities', 'periodGrades']);

        // Statistics for the subject
        $stats = [
            'assignments' => $subject->subjectAssignments->count(),
            'activities' => $subject->activities->count(),
            'period_grades' => $subject->periodGrades->count(),
            'average_hours' => $subject->hours_per_week,
        ];

        return view('subjects.show', compact('subject', 'stats'));
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject): View
    {
        // Only admins can edit subjects
        if (Auth::user()->role === 'teacher') {
            abort(403, 'No tienes permisos para editar materias.');
        }

        $academicPlans = AcademicPlan::with('gradeLevel')
            ->where('status', true)
            ->orderBy('name')
            ->get();
            
        $teachers = User::where('role', 'teacher')
            ->where('status', true)
            ->orderBy('first_name')
            ->get();

        return view('subjects.edit', compact('subject', 'academicPlans', 'teachers'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject): RedirectResponse
    {
        // Only admins can update subjects
        if (Auth::user()->role === 'teacher') {
            abort(403, 'No tienes permisos para modificar materias.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'area' => 'required|string|max:255',
            'academic_plan_id' => 'required|exists:academic_plans,id',
            'description' => 'nullable|string|max:1000',
            'curriculum_content' => 'nullable|string',
            'topics' => 'nullable|array',
            'objectives' => 'nullable|string',
            'methodology' => 'nullable|string',
            'evaluation_criteria' => 'nullable|string',
            'resources' => 'nullable|array',
            'prerequisites' => 'nullable|string',
            'hours_per_week' => 'required|integer|min:1|max:40',
            'credits' => 'nullable|integer|min:0|max:20',
            'is_mandatory' => 'boolean',
            'status' => 'boolean',
        ]);

        // Process JSON fields
        $validated['topics'] = $validated['topics'] ?? [];
        $validated['resources'] = $validated['resources'] ?? [];

        $subject->update($validated);

        return redirect()->route('subjects.show', $subject)
            ->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        // Only admins can delete subjects
        if (Auth::user()->role === 'teacher') {
            abort(403, 'No tienes permisos para eliminar materias.');
        }

        // Check if the subject has associated activities or grades
        if ($subject->activities()->count() > 0 || $subject->studentGrades()->count() > 0) {
            return redirect()->route('subjects.index')
                ->with('error', 'No se puede eliminar la materia porque tiene actividades o calificaciones asociadas.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Materia eliminada exitosamente.');
    }

    /**
     * Toggle the active status of a subject.
     */
    public function toggleStatus(Subject $subject): RedirectResponse
    {
        // Only admins can change subject status
        if (Auth::user()->role === 'teacher') {
            abort(403, 'No tienes permisos para cambiar el estado de las materias.');
        }

        $subject->update([
            'status' => !$subject->status
        ]);

        $status = $subject->status ? 'activada' : 'desactivada';

        return redirect()->back()
            ->with('success', "Materia {$status} exitosamente.");
    }

    /**
     * Assign a teacher to a subject.
     */
    public function assignTeacher(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        $teacher = User::find($validated['teacher_id']);
        
        if ($teacher->role !== 'teacher') {
            return redirect()->back()
                ->withErrors(['teacher_id' => 'El usuario seleccionado no es un profesor.']);
        }

        // If group_id is provided, assign to that specific group
        // Otherwise, assign to all active groups
        if ($request->has('group_id') && $request->group_id) {
            \App\Models\SubjectAssignment::firstOrCreate([
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $subject->id,
                'group_id' => $validated['group_id'],
                'academic_year' => date('Y')
            ]);
        } else {
            // Assign to all active groups
            $groups = \App\Models\Group::where('status', true)->pluck('id');
            
            foreach ($groups as $groupId) {
                \App\Models\SubjectAssignment::firstOrCreate([
                    'teacher_id' => $validated['teacher_id'],
                    'subject_id' => $subject->id,
                    'group_id' => $groupId,
                    'academic_year' => date('Y')
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Profesor asignado exitosamente.');
    }

    /**
     * Remove teacher assignment from a subject.
     */
    public function removeTeacher(Subject $subject): RedirectResponse
    {
        // Remove all assignments for this subject in current academic year
        \App\Models\SubjectAssignment::where('subject_id', $subject->id)
            ->where('academic_year', date('Y'))
            ->delete();

        return redirect()->back()
            ->with('success', 'Asignación de profesor removida exitosamente.');
    }

    /**
     * Display student subjects view with periods and grades
     */
    private function studentSubjects(): View
    {
        $user = Auth::user();
        
        // Get student's enrollment to find their group and academic plan
        $enrollment = Enrollment::where('student_id', $user->id)
            ->where('academic_year', date('Y'))
            ->where('status', 'active')
            ->with(['group.gradeLevel'])
            ->first();
            
        if (!$enrollment) {
            return view('subjects.student-index', [
                'subjects' => collect([]),
                'message' => 'No tienes inscripción activa para este año académico.'
            ]);
        }
        
        // Get the academic plan for this grade level and current academic year
        $academicPlan = AcademicPlan::where('grade_level_id', $enrollment->group->grade_level_id)
            ->where('academic_year', date('Y'))
            ->where('status', true)
            ->first();
            
        if (!$academicPlan) {
            return view('subjects.student-index', [
                'subjects' => collect([]),
                'enrollment' => $enrollment,
                'message' => 'No hay un plan académico activo para tu nivel de grado este año.'
            ]);
        }
        
        // Get all subjects for this academic plan
        $subjects = Subject::where('academic_plan_id', $academicPlan->id)
            ->where('status', true)
            ->with([
                'academicPlan.periods' => function($query) {
                    $query->orderBy('start_date');
                }
            ])
            ->orderBy('area')
            ->orderBy('name')
            ->get();
            
        // Get all periods for this academic plan
        $periods = Period::where('academic_plan_id', $academicPlan->id)
            ->orderBy('start_date')
            ->get();
            
        // Organize data for each subject
        $subjectData = [];
        
        foreach ($subjects as $subject) {
            $subjectInfo = [
                'subject' => $subject,
                'periods' => [],
                'overall_grade' => null,
                'graded_periods' => 0,
                'total_periods' => $periods->count()
            ];
            
            foreach ($periods as $period) {
                $periodInfo = [
                    'period' => $period,
                    'activities' => [],
                    'final_grade' => null,
                    'status' => $period->status
                ];
                
                // Get activities for this subject and period
                $activities = Activity::where('subject_id', $subject->id)
                    ->where('period_id', $period->id)
                    ->whereHas('groups', function($query) use ($enrollment) {
                        $query->where('group_id', $enrollment->group_id);
                    })
                    ->with(['studentGrades' => function($query) use ($user) {
                        $query->where('student_id', $user->id);
                    }])
                    ->orderBy('created_at')
                    ->get();
                    
                foreach ($activities as $activity) {
                    $studentGrade = $activity->studentGrades->first();
                    $periodInfo['activities'][] = [
                        'activity' => $activity,
                        'grade' => $studentGrade ? $studentGrade->score : null,
                        'max_score' => $activity->max_score,
                        'percentage' => $activity->percentage,
                        'status' => $activity->status
                    ];
                }
                
                // Get final grade for this period if exists
                if ($period->status === 'finished') {
                    $finalGrade = StudentPeriodGrade::where('student_id', $user->id)
                        ->where('period_id', $period->id)
                        ->where('subject_id', $subject->id)
                        ->first();
                        
                    if ($finalGrade) {
                        $periodInfo['final_grade'] = $finalGrade->final_grade;
                        $subjectInfo['graded_periods']++;
                    }
                }
                
                $subjectInfo['periods'][] = $periodInfo;
            }
            
            // Calculate overall grade if there are finished periods
            if ($subjectInfo['graded_periods'] > 0) {
                $totalGrade = StudentPeriodGrade::where('student_id', $user->id)
                    ->where('subject_id', $subject->id)
                    ->whereHas('period', function($query) {
                        $query->where('status', 'finished');
                    })
                    ->avg('final_grade');
                    
                $subjectInfo['overall_grade'] = round($totalGrade, 2);
            }
            
            $subjectData[] = $subjectInfo;
        }
        
        return view('subjects.student-index', [
            'subjects' => $subjectData,
            'enrollment' => $enrollment,
            'academicPlan' => $academicPlan
        ]);
    }
}
