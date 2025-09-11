<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademicPlanController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SubjectAssignmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\LibraryController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Quick login route for testing (remove in production)
Route::get('/quick-login/{role?}', function ($role = 'admin') {
    $user = \App\Models\User::where('role', $role)->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('dashboard');
    }
    return redirect()->route('login')->with('error', 'No se encontró un usuario con ese rol.');
})->name('quick-login');

// Dashboard routes
Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('dashboard/stats', [DashboardController::class, 'getStats'])
    ->middleware(['auth'])
    ->name('dashboard.stats');

Route::middleware(['auth'])->group(function () {
    // Settings routes - Available to all authenticated users
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Forum routes - Available to all authenticated users
    Route::get('forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('forum/search', [ForumController::class, 'search'])->name('forum.search');
    Route::get('forum/my-activity', [ForumController::class, 'userActivity'])->name('forum.user-activity');
    Route::get('forum/category/{category}', [ForumController::class, 'category'])->name('forum.category');
    Route::get('forum/post/{post}', [ForumController::class, 'post'])->name('forum.post');
    Route::get('forum/create-post', [ForumController::class, 'createPost'])->name('forum.create-post');
    Route::post('forum/store-post', [ForumController::class, 'storePost'])->name('forum.store-post');
    Route::get('forum/edit-post/{post}', [ForumController::class, 'editPost'])->name('forum.edit-post');
    Route::put('forum/update-post/{post}', [ForumController::class, 'updatePost'])->name('forum.update-post');
    Route::delete('forum/delete-post/{post}', [ForumController::class, 'deletePost'])->name('forum.delete-post');
    Route::post('forum/post/{post}/comment', [ForumController::class, 'storeComment'])->name('forum.store-comment');

    // Library routes - Available to all authenticated users
    Route::get('library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('library/search', [LibraryController::class, 'search'])->name('library.search');
    Route::get('library/resource/{resource}', [LibraryController::class, 'resource'])->name('library.resource');
    Route::get('library/my-loans', [LibraryController::class, 'myLoans'])->name('library.my-loans');
    Route::post('library/resource/{resource}/request-loan', [LibraryController::class, 'requestLoan'])->name('library.request-loan');
    Route::patch('library/loan/{loan}/return', [LibraryController::class, 'returnLoan'])->name('library.return-loan');
    Route::patch('library/loan/{loan}/renew', [LibraryController::class, 'renewLoan'])->name('library.renew-loan');

});

// Admin only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // User management routes - specific routes first
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/role/{role}', [UserController::class, 'byRole'])->name('users.by-role');
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('users/statistics', [UserController::class, 'statistics'])->name('users.statistics');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');

    // Forum moderation routes
    Route::get('forum/moderation', [ForumController::class, 'moderationDashboard'])->name('forum.moderation.dashboard');
    Route::patch('forum/post/{post}/approve', [ForumController::class, 'approvePost'])->name('forum.post.approve');
    Route::delete('forum/post/{post}/reject', [ForumController::class, 'rejectPost'])->name('forum.post.reject');
    Route::patch('forum/comment/{comment}/approve', [ForumController::class, 'approveComment'])->name('forum.comment.approve');
    Route::delete('forum/comment/{comment}/reject', [ForumController::class, 'rejectComment'])->name('forum.comment.reject');
});

// Routes available to authenticated users (including user profile routes)
Route::middleware(['auth'])->group(function () {
    // User profile routes - Users can view/edit their own profile (after specific routes)
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');

    // Academic Plans routes
    Route::resource('academic-plans', AcademicPlanController::class);
    Route::patch('academic-plans/{academicPlan}/toggle-status', [AcademicPlanController::class, 'toggleStatus'])->name('academic-plans.toggle-status');
    Route::patch('academic-plans/{academicPlan}/update-periods-status', [AcademicPlanController::class, 'updatePeriodsStatus'])->name('academic-plans.update-periods-status');

    // Grade Levels routes
    Route::resource('grade-levels', GradeLevelController::class);
    Route::patch('grade-levels/{gradeLevel}/toggle-status', [GradeLevelController::class, 'toggleStatus'])->name('grade-levels.toggle-status');

    // Groups routes
    Route::resource('groups', GroupController::class);
    Route::get('groups/{group}/statistics', [GroupController::class, 'statistics'])->name('groups.statistics');
    Route::patch('groups/{group}/toggle-status', [GroupController::class, 'toggleStatus'])->name('groups.toggle-status');

    // Enrollments routes
    Route::resource('enrollments', EnrollmentController::class);
    Route::patch('enrollments/{enrollment}/toggle-status', [EnrollmentController::class, 'toggleStatus'])->name('enrollments.toggle-status');
    Route::post('enrollments/enroll-student', [EnrollmentController::class, 'enrollStudent'])->name('enrollments.enroll-student');
    Route::patch('enrollments/{enrollment}/unenroll', [EnrollmentController::class, 'unenrollStudent'])->name('enrollments.unenroll');
    Route::patch('enrollments/{enrollment}/transfer', [EnrollmentController::class, 'transferStudent'])->name('enrollments.transfer');

    // Forum admin routes
    Route::patch('forum/post/{post}/toggle-pin', [ForumController::class, 'togglePin'])->name('forum.toggle-pin');

    // Library admin routes
    Route::prefix('library/admin')->name('library.admin.')->group(function () {
        Route::get('loans', [LibraryController::class, 'adminLoans'])->name('loans');
        Route::patch('loans/{loan}/approve', [LibraryController::class, 'approveLoan'])->name('approve-loan');
        Route::patch('loans/{loan}/reject', [LibraryController::class, 'rejectLoan'])->name('reject-loan');
        Route::get('resources', [LibraryController::class, 'adminResources'])->name('resources');
        Route::get('resources/create', [LibraryController::class, 'createResource'])->name('create-resource');
        Route::post('resources', [LibraryController::class, 'storeResource'])->name('store-resource');
        Route::get('resources/{resource}/edit', [LibraryController::class, 'editResource'])->name('edit-resource');
        Route::patch('resources/{resource}', [LibraryController::class, 'updateResource'])->name('update-resource');
        Route::delete('resources/{resource}', [LibraryController::class, 'deleteResource'])->name('delete-resource');
    });
});

// Admin and Teacher routes
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    // Activities routes
    Route::resource('activities', ActivityController::class);
    Route::patch('activities/{activity}/toggle-status', [ActivityController::class, 'toggleStatus'])->name('activities.toggle-status');
    Route::put('activities/{activity}/grade/{student}', [ActivityController::class, 'gradeStudent'])->name('activities.grade');
    Route::post('activities/{activity}/grade-students', [ActivityController::class, 'gradeStudents'])->name('activities.grade-students');
    Route::get('activities/{activity}/export-grades', [ActivityController::class, 'exportGrades'])->name('activities.export-grades');
    Route::get('subjects/{subject}/activities', [ActivityController::class, 'getBySubject'])->name('activities.by-subject');
    Route::get('api/activities/available-percentage', [ActivityController::class, 'getAvailablePercentage'])->name('activities.available-percentage');

    // Subject Assignments routes
    Route::resource('assignments', SubjectAssignmentController::class)->except(['show']);
    Route::get('assignments/teacher/{user}', [SubjectAssignmentController::class, 'teacher'])->name('assignments.teacher');
    Route::get('assignments/subject/{subject}', [SubjectAssignmentController::class, 'subject'])->name('assignments.subject');
    Route::patch('assignments/teacher/{user}/update', [SubjectAssignmentController::class, 'updateTeacherAssignments'])->name('assignments.update-teacher');
});

// Subjects routes - Available to all authenticated users (with role-based permissions in controller)
Route::middleware(['auth'])->group(function () {
    Route::resource('subjects', SubjectController::class);
    Route::patch('subjects/{subject}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('subjects.toggle-status');
    Route::patch('subjects/{subject}/assign-teacher', [SubjectController::class, 'assignTeacher'])->name('subjects.assign-teacher');
    Route::patch('subjects/{subject}/remove-teacher', [SubjectController::class, 'removeTeacher'])->name('subjects.remove-teacher');
});

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    // Student routes
    Route::get('my-activities', [StudentController::class, 'activities'])->name('student.activities');
    Route::get('my-grades', [StudentController::class, 'grades'])->name('student.grades');
    Route::get('student/activities/{activity}', [StudentController::class, 'showActivity'])->name('student.activity-detail');
});

require __DIR__.'/auth.php';
