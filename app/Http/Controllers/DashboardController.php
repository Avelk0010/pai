<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Activity;
use App\Models\LibraryLoan;
use App\Models\ForumPost;
use App\Models\Enrollment;
use App\Models\StudentGrade;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(): View
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'teacher':
                return $this->teacherDashboard();
            case 'student':
                return $this->studentDashboard();
            case 'parent':
                return $this->parentDashboard();
            default:
                return $this->defaultDashboard();
        }
    }

    /**
     * Admin dashboard with system overview.
     */
    private function adminDashboard(): View
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_parents' => User::where('role', 'parent')->count(),
            'active_groups' => Group::where('status', true)->count(),
            'total_subjects' => Subject::where('status', true)->count(),
            'active_enrollments' => Enrollment::count(),
            'pending_activities' => Activity::where('status', true)
                ->where('due_date', '>=', Carbon::now())
                ->count(),
            'overdue_loans' => LibraryLoan::whereNull('actual_return_date')
                ->where('return_date', '<', Carbon::now())
                ->count(),
        ];

        // Recent activities
        $recentActivities = Activity::with(['subject.academicPlan.gradeLevel', 'period'])
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent forum posts
        $recentPosts = ForumPost::with(['author', 'category'])
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Groups with low enrollment (PostgreSQL compatible)
        $lowEnrollmentGroups = Group::with(['gradeLevel'])
            ->withCount('enrollments')
            ->where('status', true)
            ->get()
            ->filter(function ($group) {
                return $group->enrollments_count < 15;
            })
            ->sortBy('enrollments_count')
            ->take(5)
            ->values(); // Reset keys after filtering

        return view('dashboard.admin', compact('stats', 'recentActivities', 'recentPosts', 'lowEnrollmentGroups'));
    }

    /**
     * Teacher dashboard with class information.
     */
    private function teacherDashboard(): View
    {
        $teacherId = Auth::id();

        // Teacher's subjects through subject_assignments
        $subjectIds = SubjectAssignment::where('teacher_id', $teacherId)
            ->pluck('subject_id')
            ->unique();
            
        $subjects = Subject::with(['academicPlan.gradeLevel'])
            ->whereIn('id', $subjectIds)
            ->where('status', true)
            ->get();

        // Get groups where this teacher has subject assignments
        $teacherGroups = SubjectAssignment::with(['group.gradeLevel', 'group.enrollments.student'])
            ->where('teacher_id', $teacherId)
            ->get()
            ->pluck('group')
            ->unique('id');

        // Upcoming activities for teacher's subjects
        $upcomingActivities = Activity::with(['subject', 'period'])
            ->whereIn('subject_id', $subjectIds)
            ->where('status', true)
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recent activities that need grading for teacher's subjects
        $activitiesNeedingGrades = Activity::with(['subject'])
            ->whereIn('subject_id', $subjectIds)
            ->where('status', true)
            ->where('due_date', '<', Carbon::now())
            ->whereDoesntHave('studentGrades')
            ->orderBy('due_date', 'desc')
            ->take(5)
            ->get();

        // Calculate total students across all groups this teacher teaches
        $totalStudents = $teacherGroups->sum(function($group) {
            return $group->enrollments->count();
        });

        // Statistics
        $stats = [
            'total_subjects' => $subjects->count(),
            'total_activities' => Activity::whereIn('subject_id', $subjectIds)
                ->where('status', true)->count(),
            'pending_grades' => $activitiesNeedingGrades->count(),
            'total_students' => $totalStudents,
        ];

        return view('dashboard.teacher', compact('subjects', 'teacherGroups', 'upcomingActivities', 'activitiesNeedingGrades', 'stats'));
    }

    /**
     * Student dashboard with academic information.
     */
    private function studentDashboard(): View
    {
        $studentId = Auth::id();

        // Student's enrollment
        $enrollment = Enrollment::with(['group.gradeLevel', 'group.homeRoomTeacher'])
            ->where('student_id', $studentId)
            ->first();

        if (!$enrollment) {
            return view('dashboard.student-no-enrollment');
        }

        // Get subjects for the student's group through subject_assignments
        $subjectIds = SubjectAssignment::where('group_id', $enrollment->group_id)
            ->pluck('subject_id')
            ->unique();
            
        $subjects = Subject::whereIn('id', $subjectIds)
            ->where('status', true)
            ->get();

        // Upcoming activities for student's subjects
        $upcomingActivities = Activity::with(['subject'])
            ->whereIn('subject_id', $subjectIds)
            ->where('status', true)
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recent grades
        $recentGrades = StudentGrade::with(['activity.subject'])
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Active library loans for this student
        $activeLoans = LibraryLoan::with(['resource'])
            ->where('user_id', $studentId)
            ->whereNull('actual_return_date')
            ->orderBy('return_date', 'asc')
            ->take(5)
            ->get();

        // Statistics
        $stats = [
            'total_subjects' => $subjects->count(),
            'pending_activities' => $upcomingActivities->count(),
            'active_loans' => $activeLoans->count(),
            'overdue_loans' => $activeLoans->where('return_date', '<', Carbon::now())->count(),
        ];

        return view('dashboard.student', compact('enrollment', 'subjects', 'upcomingActivities', 'recentGrades', 'activeLoans', 'stats'));
    }

    /**
     * Parent dashboard with children's information.
     */
    private function parentDashboard(): View
    {
        $parentId = Auth::id();

        // Parent's children (assuming parent-student relationship exists)
        $children = User::where('role', 'student')
            ->whereHas('parentStudents', function ($query) use ($parentId) {
                $query->where('parent_id', $parentId);
            })
            ->with(['enrollments.group.gradeLevel'])
            ->get();

        $childrenData = [];

        foreach ($children as $child) {
            $enrollment = $child->enrollments->first();
            
            if ($enrollment) {
                // Get child's subjects
                $subjects = Subject::where('academic_plan_id', $enrollment->group->gradeLevel->academicPlans->first()?->id)
                    ->where('status', true)
                    ->get();

                // Get recent grades
                $recentGrades = StudentGrade::with(['activity.subject'])
                    ->where('student_id', $child->id)
                    ->orderBy('graded_at', 'desc')
                    ->take(3)
                    ->get();

                // Get upcoming activities
                $upcomingActivities = Activity::with(['subject'])
                    ->whereIn('subject_id', $subjects->pluck('id'))
                    ->where('status', true)
                    ->where('due_date', '>=', Carbon::now())
                    ->orderBy('due_date', 'asc')
                    ->take(3)
                    ->get();

                $childrenData[] = [
                    'student' => $child,
                    'enrollment' => $enrollment,
                    'subjects' => $subjects,
                    'recent_grades' => $recentGrades,
                    'upcoming_activities' => $upcomingActivities,
                ];
            }
        }

        return view('dashboard.parent', compact('childrenData'));
    }

    /**
     * Default dashboard for users without specific roles.
     */
    private function defaultDashboard(): View
    {
        return view('dashboard.default');
    }

    /**
     * Get dashboard statistics (AJAX endpoint).
     */
    public function getStats(): array
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return [
                    'users' => User::count(),
                    'active_groups' => Group::where('status', true)->count(),
                    'subjects' => Subject::where('status', true)->count(),
                    'activities' => Activity::where('status', true)->count(),
                ];

            case 'teacher':
                return [
                    'subjects' => Subject::where('teacher_id', $user->id)->where('status', true)->count(),
                    'activities' => Activity::whereHas('subject', function ($query) use ($user) {
                        $query->where('teacher_id', $user->id);
                    })->where('status', true)->count(),
                ];

            case 'student':
                $enrollment = Enrollment::where('student_id', $user->id)->first();
                if (!$enrollment) {
                    return ['enrolled' => false];
                }

                return [
                    'enrolled' => true,
                    'group' => $enrollment->group->name,
                    'active_loans' => LibraryLoan::where('user_id', $user->id)->whereNull('actual_return_date')->count(),
                ];

            default:
                return [];
        }
    }
}
