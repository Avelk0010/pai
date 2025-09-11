<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\SubjectAssignment;
use App\Models\PeriodGrade;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display student's activities
     */
    public function activities(Request $request): View
    {
        $studentId = Auth::id();
        
        // Get student's enrollment
        $enrollment = Enrollment::with(['group.gradeLevel'])
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return view('student.no-enrollment');
        }

        // Get subjects for the student's group
        $subjectIds = SubjectAssignment::where('group_id', $enrollment->group_id)
            ->pluck('subject_id')
            ->unique();
            
        $subjects = Subject::whereIn('id', $subjectIds)
            ->where('status', true)
            ->get();

        // Build query for activities
        $query = Activity::with(['subject', 'groups', 'period', 'teacher', 'studentGrades' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])
            ->whereHas('groups', function($q) use ($enrollment) {
                $q->where('group_id', $enrollment->group_id);
            })
            ->where('status', 'published');

        // Apply filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('status')) {
            $currentDate = Carbon::now();
            switch ($request->status) {
                case 'pending':
                    $query->whereDoesntHave('studentGrades', function($q) use ($studentId) {
                        $q->where('student_id', $studentId);
                    });
                    break;
                case 'completed':
                    $query->whereHas('studentGrades', function($q) use ($studentId) {
                        $q->where('student_id', $studentId);
                    });
                    break;
                case 'overdue':
                    $query->where('due_date', '<', $currentDate)
                          ->whereDoesntHave('studentGrades', function($q) use ($studentId) {
                              $q->where('student_id', $studentId);
                          });
                    break;
            }
        }

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        $activities = $query->orderBy('due_date', 'desc')->paginate(12);

        // Get available periods
        $periods = Period::whereHas('academicPlan.subjects', function($q) use ($subjectIds) {
            $q->whereIn('subjects.id', $subjectIds);
        })->orderBy('start_date', 'desc')->get();

        // Statistics
        $stats = [
            'total' => Activity::whereHas('groups', function($q) use ($enrollment) {
                $q->where('group_id', $enrollment->group_id);
            })->where('status', 'published')->count(),
            
            'pending' => Activity::whereHas('groups', function($q) use ($enrollment) {
                $q->where('group_id', $enrollment->group_id);
            })->where('status', 'published')
            ->whereDoesntHave('studentGrades', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })->count(),
            
            'completed' => StudentGrade::where('student_id', $studentId)->count(),
            
            'overdue' => Activity::whereHas('groups', function($q) use ($enrollment) {
                $q->where('group_id', $enrollment->group_id);
            })->where('status', 'published')
            ->where('due_date', '<', Carbon::now())
            ->whereDoesntHave('studentGrades', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })->count(),
        ];

        return view('student.activities', compact('activities', 'subjects', 'periods', 'stats', 'enrollment'));
    }

    /**
     * Display student's grades
     */
    public function grades(Request $request): View
    {
        $studentId = Auth::id();
        
        // Get student's enrollment
        $enrollment = Enrollment::with(['group.gradeLevel'])
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return view('student.no-enrollment');
        }

        // Get subjects for the student's group
        $subjectIds = SubjectAssignment::where('group_id', $enrollment->group_id)
            ->pluck('subject_id')
            ->unique();
            
        $subjects = Subject::whereIn('id', $subjectIds)
            ->where('status', true)
            ->get();

        // Build query for grades
        $query = StudentGrade::with(['activity.subject', 'activity.period'])
            ->where('student_id', $studentId)
            ->whereHas('activity', function($q) use ($subjectIds) {
                $q->whereIn('subject_id', $subjectIds);
            });

        // Apply filters
        if ($request->filled('subject_id')) {
            $query->whereHas('activity', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('period_id')) {
            $query->whereHas('activity', function($q) use ($request) {
                $q->where('period_id', $request->period_id);
            });
        }

        $grades = $query->orderBy('graded_at', 'desc')->paginate(15);

        // Get period grades (final grades per period)
        $periodGrades = PeriodGrade::with(['period'])
            ->where('student_id', $studentId)
            ->where('group_id', $enrollment->group_id)
            ->orderBy('calculated_at', 'desc')
            ->get();

        // Get available periods
        $periods = Period::whereHas('academicPlan.subjects', function($q) use ($subjectIds) {
            $q->whereIn('subjects.id', $subjectIds);
        })->orderBy('start_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_grades' => StudentGrade::where('student_id', $studentId)->count(),
            'average_grade' => StudentGrade::where('student_id', $studentId)->avg('score'),
            'passed_activities' => StudentGrade::where('student_id', $studentId)->where('score', '>=', 3.0)->count(),
            'failed_activities' => StudentGrade::where('student_id', $studentId)->where('score', '<', 3.0)->count(),
        ];

        return view('student.grades', compact('grades', 'periodGrades', 'subjects', 'periods', 'stats', 'enrollment'));
    }

    /**
     * Show activity details for student
     */
    public function showActivity(Activity $activity): View
    {
        $studentId = Auth::id();
        
        // Check if student can view this activity
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            abort(404, 'No estás inscrito en ningún grupo.');
        }

        // Check if activity is assigned to student's group
        $hasAccess = $activity->groups()->where('group_id', $enrollment->group_id)->exists();
        
        if (!$hasAccess || $activity->status !== 'published') {
            abort(403, 'No tienes acceso a esta actividad.');
        }

        // Load relationships
        $activity->load(['subject', 'groups', 'period', 'teacher']);

        // Get student's grade for this activity
        $studentGrade = StudentGrade::where('student_id', $studentId)
            ->where('activity_id', $activity->id)
            ->first();

        // Check if activity is overdue
        $isOverdue = $activity->due_date && $activity->due_date->isPast() && !$studentGrade;

        // Get activity statistics (for context)
        $activityStats = [
            'total_students' => $activity->groups->sum(function($group) {
                return $group->enrollments()->where('status', 'active')->count();
            }),
            'graded_count' => $activity->studentGrades()->count(),
            'average_grade' => $activity->studentGrades()->avg('score'),
        ];

        return view('student.activity-detail', compact('activity', 'studentGrade', 'isOverdue', 'activityStats'));
    }
}
