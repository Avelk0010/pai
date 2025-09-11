<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Period;
use App\Models\StudentGrade;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\PeriodGrade;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(): View
    {
        $query = Activity::with(['subject', 'groups', 'period', 'teacher'])
            ->withCount(['studentGrades']);

        // Filter by teacher if user is a teacher
        if (Auth::user()->role === 'teacher') {
            $query->where('teacher_id', Auth::id());
        }

        $activities = $query->orderBy('due_date', 'desc')->paginate(15);

        $stats = [
            'total' => Activity::count(),
            'published' => Activity::where('status', 'published')->count(),
            'draft' => Activity::where('status', 'draft')->count(),
            'finished' => Activity::where('status', 'finished')->count(),
        ];

        // Activities by type
        $activitiesByType = Activity::selectRaw('activity_type, COUNT(*) as count')
            ->groupBy('activity_type')
            ->get();

        return view('activities.index', compact('activities', 'stats', 'activitiesByType'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): View
    {
        // Filter subjects and groups based on user role
        if (Auth::user()->role === 'teacher') {
            $subjects = Subject::with('academicPlan.gradeLevel')
                ->where('status', true)
                ->whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->orderBy('name')
                ->get();

            // Get groups that the teacher is assigned to
            $groups = Group::with('gradeLevel')
                ->where('status', true)
                ->whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->get()
                ->sortBy('name');
        } else {
            $subjects = Subject::with('academicPlan.gradeLevel')
                ->where('status', true)
                ->orderBy('name')
                ->get();

            $groups = Group::with('gradeLevel')
                ->where('status', true)
                ->get()
                ->sortBy('name');
        }

        // Filter periods based on user role and assigned subjects
        if (Auth::user()->role === 'teacher') {
            // Get periods from academic plans where teacher has subject assignments
            $periods = Period::where('status', 'active')
                ->whereHas('academicPlan.subjects.subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->orderBy('start_date')
                ->get();
        } else {
            $periods = Period::where('status', 'active')
                ->orderBy('start_date')
                ->get();
        }

        $teachers = User::where('role', 'teacher')
            ->where('status', true)
            ->orderBy('first_name')
            ->get();

        // Get pre-selected subject if coming from subject page
        $selectedSubject = request()->get('subject_id');

        return view('activities.create', compact('subjects', 'groups', 'periods', 'teachers', 'selectedSubject'));
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'exists:groups,id',
            'period_id' => 'required|exists:periods,id',
            'activity_type' => 'required|in:exam,quiz,assignment,project,participation',
            'due_date' => 'nullable|date|after:now',
            'max_score' => 'required|numeric|min:0|max:5',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,finished',
        ]);

        // Validate that the selected period is active
        $period = \App\Models\Period::find($validated['period_id']);
        if ($period->status !== 'active') {
            return back()->withErrors([
                'period_id' => 'Solo se pueden crear actividades en períodos activos.'
            ])->withInput();
        }

        // Validate that teacher is assigned to the selected subject (if teacher)
        if (Auth::user()->role === 'teacher') {
            $subject = \App\Models\Subject::find($validated['subject_id']);
            $hasAssignment = $subject->subjectAssignments()
                ->where('teacher_id', Auth::id())
                ->exists();
            
            if (!$hasAssignment) {
                return back()->withErrors([
                    'subject_id' => 'No tienes permisos para crear actividades en esta materia.'
                ])->withInput();
            }

        // Validate that teacher is assigned to all selected groups for this subject
        foreach ($validated['group_ids'] as $groupId) {
            $hasGroupAssignment = $subject->subjectAssignments()
                ->where('teacher_id', Auth::id())
                ->where('group_id', $groupId)
                ->exists();

            if (!$hasGroupAssignment) {
                return back()->withErrors([
                    'group_ids' => 'No tienes asignación para uno o más grupos seleccionados.'
                ])->withInput();
            }
        }
    }

    // Validate percentage doesn't exceed 100% for the period
    $currentPercentageSum = Activity::where('period_id', $validated['period_id'])
        ->where('subject_id', $validated['subject_id'])
        ->whereIn('status', ['published', 'finished'])
        ->sum('percentage');

    if (($currentPercentageSum + $validated['percentage']) > 100) {
        $availablePercentage = 100 - $currentPercentageSum;
        return back()->withErrors([
            'percentage' => "El porcentaje excede el límite del período. Porcentaje disponible: {$availablePercentage}%"
        ])->withInput();
    }

        // Remove group_ids from validated data for activity creation
        $activityData = collect($validated)->except('group_ids')->toArray();

        // Set teacher_id to current user if teacher, or allow admin to select
        if (Auth::user()->role === 'teacher') {
            $activityData['teacher_id'] = Auth::id();
        } else {
            $activityData['teacher_id'] = $request->input('teacher_id', Auth::id());
        }

        // Create the activity
        $activity = Activity::create($activityData);

        // Attach the selected groups
        $activity->groups()->attach($validated['group_ids']);

        return redirect()->route('activities.index')
            ->with('success', 'Actividad creada exitosamente para ' . count($validated['group_ids']) . ' grupo(s).');
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity): View
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para ver esta actividad.');
        }

        $activity->load(['subject', 'groups.gradeLevel', 'period', 'teacher', 'studentGrades.student']);

        // Get enrolled students from all groups assigned to this activity
        $students = User::where('role', 'student')
            ->whereHas('enrollments', function ($query) use ($activity) {
                $query->whereIn('group_id', $activity->groups->pluck('id'))
                      ->where('status', 'active');
            })
            ->with(['studentGrades' => function ($query) use ($activity) {
                $query->where('activity_id', $activity->id);
            }])
            ->orderBy('first_name')
            ->get();

        // Calculate statistics
        $gradedCount = $activity->studentGrades->count();
        $averageGrade = $gradedCount > 0 ? $activity->studentGrades->avg('score') : 0;
        $passedCount = $activity->studentGrades->where('score', '>=', 3.0)->count();
        $failedCount = $activity->studentGrades->where('score', '<', 3.0)->count();

        return view('activities.show', compact(
            'activity', 
            'students', 
            'gradedCount', 
            'averageGrade', 
            'passedCount', 
            'failedCount'
        ));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity): View
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para editar esta actividad.');
        }

        // Filter subjects and groups based on user role
        if (Auth::user()->role === 'teacher') {
            $subjects = Subject::with('academicPlan.gradeLevel')
                ->where('status', true)
                ->whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->orderBy('name')
                ->get();

            // Get groups that the teacher is assigned to
            $groups = Group::with('gradeLevel')
                ->where('status', true)
                ->whereHas('subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->get()
                ->sortBy('name');
        } else {
            $subjects = Subject::with('academicPlan.gradeLevel')
                ->where('status', true)
                ->orderBy('name')
                ->get();

            $groups = Group::with('gradeLevel')
                ->where('status', true)
                ->get()
                ->sortBy('name');
        }

        // Filter periods based on user role and assigned subjects
        if (Auth::user()->role === 'teacher') {
            // Get periods from academic plans where teacher has subject assignments
            $periods = Period::where('status', 'active')
                ->whereHas('academicPlan.subjects.subjectAssignments', function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
                ->orderBy('start_date')
                ->get();
        } else {
            $periods = Period::orderBy('start_date')
                ->get();
        }

        $activePeriods = Period::where('status', 'active')->get();

        $teachers = User::where('role', 'teacher')
            ->where('status', true)
            ->orderBy('first_name')
            ->get();

        return view('activities.edit', compact('activity', 'subjects', 'groups', 'periods', 'activePeriods', 'teachers'));
    }

    /**
     * Update the specified activity in storage.
     */
    public function update(Request $request, Activity $activity): RedirectResponse
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para editar esta actividad.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'exists:groups,id',
            'period_id' => 'required|exists:periods,id',
            'activity_type' => 'required|in:exam,quiz,assignment,project,participation',
            'due_date' => 'nullable|date',
            'max_score' => 'required|numeric|min:0|max:5',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,finished',
        ]);

        // Validate that the selected period is active (only if period is being changed)
        if ($validated['period_id'] != $activity->period_id) {
            $period = \App\Models\Period::find($validated['period_id']);
            if ($period->status !== 'active') {
                return back()->withErrors([
                    'period_id' => 'Solo se pueden asignar actividades a períodos activos.'
                ])->withInput();
            }
        }

        // Validate that teacher is assigned to the selected subject (if teacher and subject is being changed)
        if (Auth::user()->role === 'teacher' && $validated['subject_id'] != $activity->subject_id) {
            $subject = \App\Models\Subject::find($validated['subject_id']);
            $hasAssignment = $subject->subjectAssignments()
                ->where('teacher_id', Auth::id())
                ->exists();
            
            if (!$hasAssignment) {
                return back()->withErrors([
                    'subject_id' => 'No tienes permisos para asignar actividades a esta materia.'
                ])->withInput();
            }
        }

        // Validate that teacher is assigned to all selected groups for this subject
        if (Auth::user()->role === 'teacher') {
            $subject = \App\Models\Subject::find($validated['subject_id']);
            foreach ($validated['group_ids'] as $groupId) {
                $hasGroupAssignment = $subject->subjectAssignments()
                    ->where('teacher_id', Auth::id())
                    ->where('group_id', $groupId)
                    ->exists();

                if (!$hasGroupAssignment) {
                    return back()->withErrors([
                        'group_ids' => 'No tienes asignación para uno o más grupos seleccionados.'
                    ])->withInput();
                }
            }
        }

        // Validate percentage doesn't exceed 100% for the period (exclude current activity)
        $currentPercentageSum = Activity::where('period_id', $validated['period_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('id', '!=', $activity->id) // Exclude current activity from sum
            ->whereIn('status', ['published', 'finished'])
            ->sum('percentage');

        if (($currentPercentageSum + $validated['percentage']) > 100) {
            $availablePercentage = 100 - $currentPercentageSum;
            return back()->withErrors([
                'percentage' => "El porcentaje excede el límite del período. Porcentaje disponible: {$availablePercentage}%"
            ])->withInput();
        }

        // Remove group_ids from validated data for activity update
        $activityData = collect($validated)->except('group_ids')->toArray();

        // Don't allow changing teacher_id if current user is teacher
        if (Auth::user()->role === 'teacher') {
            unset($activityData['teacher_id']);
        } else {
            $activityData['teacher_id'] = $request->input('teacher_id');
        }

        // Update the activity
        $activity->update($activityData);

        // Sync the selected groups (this will add/remove groups as needed)
        $activity->groups()->sync($validated['group_ids']);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Actividad actualizada exitosamente.');
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para eliminar esta actividad.');
        }

        // Check if activity has grades
        if ($activity->studentGrades()->count() > 0) {
            return redirect()->route('activities.index')
                ->with('error', 'No se puede eliminar la actividad porque tiene calificaciones asociadas.');
        }

        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Actividad eliminada exitosamente.');
    }

    /**
     * Toggle activity status.
     */
    public function toggleStatus(Activity $activity): RedirectResponse
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para modificar esta actividad.');
        }

        $newStatus = $activity->status === 'published' ? 'draft' : 'published';
        $activity->update(['status' => $newStatus]);

        $statusText = $newStatus === 'published' ? 'publicada' : 'guardada como borrador';

        return redirect()->back()
            ->with('success', "Actividad {$statusText} exitosamente.");
    }

    /**
     * Grade students for an activity.
     */
    public function gradeStudents(Request $request, Activity $activity): RedirectResponse
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para calificar esta actividad.');
        }

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.score' => 'required|numeric|min:0|max:' . $activity->max_score,
            'grades.*.feedback' => 'nullable|string|max:500'
        ]);

        foreach ($validated['grades'] as $gradeData) {
            if (empty($gradeData['score']) && $gradeData['score'] !== '0') {
                continue;
            }

            StudentGrade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'activity_id' => $activity->id
                ],
                [
                    'score' => $gradeData['score'],
                    'feedback' => $gradeData['feedback'] ?? null,
                    'graded_at' => now()
                ]
            );
        }

        return redirect()->back()
            ->with('success', 'Calificaciones guardadas exitosamente.');
    }

    /**
     * Export activity grades.
     */
    public function exportGrades(Activity $activity)
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para exportar calificaciones.');
        }

        $activity->load([
            'subject',
            'group.gradeLevel',
            'period',
            'studentGrades.student'
        ]);

        return view('activities.export-grades', compact('activity'));
    }

    /**
     * Grade a specific student for an activity
     */
    public function gradeStudent(Request $request, Activity $activity, User $student)
    {
        // Check permissions
        if (Auth::user()->role === 'teacher' && $activity->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permisos para calificar esta actividad.');
        }

        $request->validate([
            'score' => 'required|numeric|min:1.0|max:' . $activity->max_score,
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Verify the student is enrolled in one of the activity's groups
        $enrollment = Enrollment::where('student_id', $student->id)
            ->whereIn('group_id', $activity->groups->pluck('id'))
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return redirect()->back()->with('error', 'El estudiante no está inscrito en ninguno de los grupos de esta actividad.');
        }

        // Create or update the grade
        StudentGrade::updateOrCreate(
            [
                'student_id' => $student->id,
                'activity_id' => $activity->id,
            ],
            [
                'score' => $request->score,
                'feedback' => $request->feedback,
                'graded_at' => now(),
            ]
        );

        // Update period grades if necessary
        $this->updatePeriodGrades($student, $activity->period, $activity->subject_id, $enrollment->group_id);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Calificación guardada correctamente.');
    }

    /**
     * Update period grades for a student
     */
    private function updatePeriodGrades(User $student, Period $period, int $subjectId, int $groupId)
    {
        // Get all activities for this student in this period, subject and group
        $activities = Activity::where('period_id', $period->id)
            ->where('subject_id', $subjectId)
            ->whereHas('groups', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            })
            ->whereHas('studentGrades', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->with(['studentGrades' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->get();

        if ($activities->isEmpty()) {
            return;
        }

        $totalScore = 0;
        $totalPercentage = 0;

        foreach ($activities as $activity) {
            $grade = $activity->studentGrades->first();
            if ($grade) {
                // Weight the score by the activity percentage
                $weightedScore = ($grade->score * $activity->percentage) / 100;
                $totalScore += $weightedScore;
                $totalPercentage += $activity->percentage;
            }
        }

        // Calculate final grade (only if we have completed activities)
        if ($totalPercentage > 0) {
            $finalGrade = ($totalScore / $totalPercentage) * 100;
            
            // Ensure the grade stays within 1.0-5.0 range
            $finalGrade = min(5.0, max(1.0, $finalGrade));

            PeriodGrade::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'period_id' => $period->id,
                    'subject_id' => $subjectId,
                ],
                [
                    'final_grade' => $finalGrade,
                    'status' => $finalGrade >= 3.0 ? 'passed' : 'failed',
                    'calculated_at' => now(),
                ]
            );
        }
    }

    /**
     * Get available percentage for a period and subject
     */
    public function getAvailablePercentage(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'subject_id' => 'required|exists:subjects,id',
            'activity_id' => 'nullable|exists:activities,id'
        ]);

        $query = Activity::where('period_id', $request->period_id)
            ->where('subject_id', $request->subject_id)
            ->whereIn('status', ['published', 'finished']);

        // Exclude current activity if editing
        if ($request->activity_id) {
            $query->where('id', '!=', $request->activity_id);
        }

        $usedPercentage = $query->sum('percentage');
        $availablePercentage = 100 - $usedPercentage;

        return response()->json([
            'available_percentage' => $availablePercentage,
            'used_percentage' => $usedPercentage,
            'activities_count' => $query->count()
        ]);
    }
}
