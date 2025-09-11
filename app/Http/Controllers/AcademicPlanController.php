<?php

namespace App\Http\Controllers;

use App\Models\AcademicPlan;
use App\Models\GradeLevel;
use App\Models\StudentPeriodGrade;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\SubjectAssignment;
use App\Models\Subject;
use App\Models\Period;
use App\Models\Group;
use App\Models\User;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicPlanController extends Controller
{
    /**
     * Display a listing of the academic plans.
     */
    public function index(): View
    {
        $academicPlans = AcademicPlan::with(['gradeLevel', 'subjects'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('name')
            ->paginate(10);

        return view('academic-plans.index', compact('academicPlans'));
    }

    /**
     * Show the form for creating a new academic plan.
     */
    public function create(): View
    {
        $gradeLevels = GradeLevel::orderBy('grade_name')->get();
        
        return view('academic-plans.create', compact('gradeLevels'));
    }

    /**
     * Store a newly created academic plan in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'academic_year' => 'required|integer|min:2020|max:2030',
            'periods_count' => 'required|integer|min:1|max:10',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        $academicPlan = AcademicPlan::create($validated);

        // Create periods automatically
        $academicPlan->createPeriods();

        return redirect()->route('academic-plans.index')
            ->with('success', 'Plan académico creado exitosamente con ' . $validated['periods_count'] . ' períodos.');
    }

    /**
     * Display the specified academic plan.
     */
    public function show(AcademicPlan $academicPlan): View
    {
        // Cargar relaciones necesarias
        $academicPlan->load(['gradeLevel', 'subjects.subjectAssignments.teacher', 'periods']);
        
        return view('academic-plans.show', compact('academicPlan'));
    }

    /**
     * Show the form for editing the specified academic plan.
     */
    public function edit(AcademicPlan $academicPlan): View
    {
        $gradeLevels = GradeLevel::orderBy('grade_name')->get();
        
        return view('academic-plans.edit', compact('academicPlan', 'gradeLevels'));
    }

    /**
     * Update the specified academic plan in storage.
     */
    public function update(Request $request, AcademicPlan $academicPlan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'academic_year' => 'required|integer|min:2020|max:2030',
            'periods_count' => 'required|integer|min:1|max:10',
            'status' => 'boolean'
        ]);

        $validated['status'] = $request->has('status');

        $oldPeriodsCount = $academicPlan->periods_count;
        $academicPlan->update($validated);

        // If periods count changed, recreate periods
        if ($oldPeriodsCount !== $validated['periods_count']) {
            $academicPlan->createPeriods();
        }

        return redirect()->route('academic-plans.index')
            ->with('success', 'Plan académico actualizado exitosamente.');
    }

    /**
     * Remove the specified academic plan from storage.
     */
    public function destroy(AcademicPlan $academicPlan): RedirectResponse
    {
        // Check if the academic plan has associated subjects
        if ($academicPlan->subjects()->count() > 0) {
            return redirect()->route('academic-plans.index')
                ->with('error', 'No se puede eliminar el plan académico porque tiene materias asociadas.');
        }

        // Check if the academic plan has periods with activities
        if ($academicPlan->periods()->whereHas('activities')->count() > 0) {
            return redirect()->route('academic-plans.index')
                ->with('error', 'No se puede eliminar el plan académico porque tiene períodos con actividades.');
        }

        $academicPlan->delete();

        return redirect()->route('academic-plans.index')
            ->with('success', 'Plan académico eliminado exitosamente.');
    }

    /**
     * Toggle the status of the academic plan.
     */
    public function toggleStatus(AcademicPlan $academicPlan): RedirectResponse
    {
        $academicPlan->status = !$academicPlan->status;
        $academicPlan->save();

        $statusText = $academicPlan->status ? 'activado' : 'desactivado';
        
        return back()->with('success', "Plan académico {$statusText} exitosamente.");
    }

    /**
     * Update periods status for the academic plan.
     * Only allows one active period at a time.
     */
    public function updatePeriodsStatus(Request $request, AcademicPlan $academicPlan): RedirectResponse
    {
        $validated = $request->validate([
            'periods' => 'required|array',
            'periods.*' => 'required|in:planned,active,finished'
        ]);

        // Count how many periods will be set as active
        $activeCount = collect($validated['periods'])->filter(fn($status) => $status === 'active')->count();
        
        if ($activeCount > 1) {
            return back()->withErrors([
                'periods' => 'Solo se puede tener un período activo a la vez.'
            ]);
        }

        // Validate that periods marked as "finished" have 100% activities in all subjects
        foreach ($validated['periods'] as $periodId => $status) {
            if ($status === 'finished') {
                $period = $academicPlan->periods()->where('id', $periodId)->first();
                $validation = $this->validatePeriodCanBeFinished($period, $academicPlan);
                
                if (!$validation['canFinish']) {
                    return back()->withErrors([
                        'periods' => $validation['message']
                    ]);
                }
            }
        }

        // Update periods status and calculate final grades when marked as finished
        foreach ($validated['periods'] as $periodId => $status) {
            $period = $academicPlan->periods()->where('id', $periodId)->first();
            $oldStatus = $period->status;
            
            // Update period status
            $period->update(['status' => $status]);
            
            // If period is being marked as finished, calculate final grades
            if ($status === 'finished' && $oldStatus !== 'finished') {
                $this->calculatePeriodFinalGrades($period);
            }
        }

        return back()->with('success', 'Estados de los períodos actualizados exitosamente.');
    }

    /**
     * Calculate final grades for all students when a period is marked as finished
     */
    private function calculatePeriodFinalGrades($period)
    {
        // Get all students enrolled in groups that have subjects in this period
        $enrollments = Enrollment::with(['student', 'group'])
            ->where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            $group = $enrollment->group;

            // Get all subjects assigned to this group
            $subjectAssignments = SubjectAssignment::with('subject')
                ->where('group_id', $group->id)
                ->get();

            foreach ($subjectAssignments as $assignment) {
                $subject = $assignment->subject;

                // Calculate final grade for this student in this subject for this period
                $finalGrade = $this->calculateSubjectFinalGrade($student, $subject, $period, $group);

                if ($finalGrade !== null) {
                    // Create or update the student period grade
                    StudentPeriodGrade::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'period_id' => $period->id,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'final_grade' => $finalGrade,
                            'weighted_grade' => $finalGrade * $subject->credits,
                            'status' => $finalGrade >= 3.0 ? 'passed' : 'failed',
                            'calculated_at' => now(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Calculate the final grade for a student in a specific subject and period
     */
    private function calculateSubjectFinalGrade($student, $subject, $period, $group)
    {
        // Get all activities for this subject in this period for this group
        $activities = Activity::where('subject_id', $subject->id)
            ->where('period_id', $period->id)
            ->whereHas('groups', function ($query) use ($group) {
                $query->where('group_id', $group->id);
            })
            ->where('status', 'published')
            ->with(['studentGrades' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->get();

        if ($activities->isEmpty()) {
            return null; // No activities for this subject in this period
        }

        $totalScore = 0;
        $totalPercentage = 0;

        foreach ($activities as $activity) {
            $grade = $activity->studentGrades->first();
            if ($grade) {
                // Weight the score by the activity percentage
                $weightedScore = ($grade->score / $activity->max_score) * $activity->percentage;
                $totalScore += $weightedScore;
                $totalPercentage += $activity->percentage;
            }
        }

        // Only calculate if we have some activities graded
        if ($totalPercentage > 0) {
            // Convert percentage to 1.0-5.0 scale
            $finalGrade = ($totalScore / 100) * 5.0;
            
            // Ensure the grade stays within 1.0-5.0 range
            return min(5.0, max(1.0, $finalGrade));
        }

        return null;
    }

    /**
     * Validate that a period can be marked as finished
     * Checks that all subjects have exactly 100% in activities
     */
    private function validatePeriodCanBeFinished($period, $academicPlan)
    {
        // Get all subjects in this academic plan
        $subjects = $academicPlan->subjects()->where('status', true)->get();
        
        $incompleteSubjects = [];
        $ungradedActivities = [];
        
        foreach ($subjects as $subject) {
            // Get all groups that have this subject assigned
            $groupsWithSubject = SubjectAssignment::where('subject_id', $subject->id)->pluck('group_id')->unique();
            
            foreach ($groupsWithSubject as $groupId) {
                $group = \App\Models\Group::find($groupId);
                
                // 1. Check activity percentage validation
                $totalPercentage = Activity::where('subject_id', $subject->id)
                    ->where('period_id', $period->id)
                    ->whereHas('groups', function ($query) use ($groupId) {
                        $query->where('group_id', $groupId);
                    })
                    ->whereIn('status', ['published', 'finished'])
                    ->sum('percentage');
                
                if ($totalPercentage != 100) {
                    $incompleteSubjects[] = [
                        'subject' => $subject->name,
                        'group' => $group->name ?? "Grupo ID: $groupId",
                        'percentage' => $totalPercentage
                    ];
                }
                
                // 2. Check if all activities are graded for all students
                $activities = Activity::where('subject_id', $subject->id)
                    ->where('period_id', $period->id)
                    ->whereHas('groups', function ($query) use ($groupId) {
                        $query->where('group_id', $groupId);
                    })
                    ->whereIn('status', ['published', 'finished'])
                    ->get();
                
                // Get all enrolled students in this group
                $students = User::where('role', 'student')
                    ->whereHas('enrollments', function ($query) use ($groupId) {
                        $query->where('group_id', $groupId)
                              ->where('status', 'active');
                    })
                    ->get();
                
                foreach ($activities as $activity) {
                    foreach ($students as $student) {
                        // Check if student has a grade for this activity
                        $studentGrade = StudentGrade::where('student_id', $student->id)
                            ->where('activity_id', $activity->id)
                            ->where('graded_at', '!=', null)
                            ->first();
                        
                        if (!$studentGrade) {
                            $ungradedActivities[] = [
                                'subject' => $subject->name,
                                'group' => $group->name ?? "Grupo ID: $groupId",
                                'activity' => $activity->name,
                                'student' => $student->name,
                                'student_code' => $student->student_code ?? $student->email
                            ];
                        }
                    }
                }
            }
        }
        
        // Build error message
        $errors = [];
        
        if (!empty($incompleteSubjects)) {
            $errors[] = "Las siguientes materias no tienen 100% en actividades:";
            foreach ($incompleteSubjects as $incomplete) {
                $errors[] = "• {$incomplete['subject']} - {$incomplete['group']}: {$incomplete['percentage']}%";
            }
        }
        
        if (!empty($ungradedActivities)) {
            if (!empty($errors)) {
                $errors[] = ""; // Add empty line for separation
            }
            $errors[] = "Las siguientes actividades no están calificadas para todos los estudiantes:";
            foreach ($ungradedActivities as $ungraded) {
                $errors[] = "• {$ungraded['subject']} - {$ungraded['group']} - {$ungraded['activity']}: {$ungraded['student']} ({$ungraded['student_code']})";
            }
        }
        
        if (!empty($errors)) {
            $message = "No se puede finalizar el período '{$period->name}'.\n\n" . implode("\n", $errors);
            $message .= "\n\nTodas las materias deben tener exactamente 100% en actividades y todas las actividades deben estar calificadas para todos los estudiantes.";
            
            return [
                'canFinish' => false,
                'message' => $message
            ];
        }
        
        return [
            'canFinish' => true,
            'message' => 'El período puede ser finalizado.'
        ];
    }
}
