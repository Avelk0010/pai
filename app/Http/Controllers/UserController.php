<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        // Only admins can view all users
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $users = User::orderBy('first_name')->orderBy('last_name')->paginate(20);

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', true)->count(),
            'inactive_users' => User::where('status', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'students' => User::where('role', 'student')->count(),
            'parents' => User::where('role', 'parent')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Display users filtered by role.
     */
    public function byRole(Request $request): View
    {
        // Only admins can view users by role
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $role = $request->get('role');
        $validRoles = ['admin', 'teacher', 'student', 'parent'];

        if (!in_array($role, $validRoles)) {
            abort(404, 'Rol no válido.');
        }

        $users = User::where('role', $role)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(20);

        return view('users.by-role', compact('users', 'role'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        // Only admins can create users
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        // Load grade levels and groups for student assignments
        $gradeLevels = \App\Models\GradeLevel::where('status', true)
            ->orderBy('grade_name')
            ->get();
        
        $groups = \App\Models\Group::with('gradeLevel')
            ->where('status', true)
            ->orderBy('group_letter')
            ->get();

        return view('users.create', compact('gradeLevels', 'groups'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Only admins can create users
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'document' => 'required|string|max:20|unique:users',
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:admin,teacher,student,parent',
            'status' => 'required|in:0,1'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = (bool) $request->input('status', 0);
        $validated['email_verified_at'] = now(); // Auto-verify admin created accounts

        $user = User::create($validated);

        // Handle student enrollment if creating a student
        if ($validated['role'] === 'student') {
            $gradeLevel = $request->input('grade_level_id');
            $group = $request->input('group_id');
            
            if ($gradeLevel && $group) {
                \App\Models\Enrollment::create([
                    'student_id' => $user->id,
                    'grade_level_id' => $gradeLevel,
                    'group_id' => $group,
                    'academic_year' => date('Y'), // Año académico actual
                    'enrollment_date' => now(),
                    'status' => 'active',
                ]);
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        // Users can only view their own profile, admins can view any profile
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para ver este perfil.');
        }

        // Load different relationships based on user role
        $relationships = [];
        
        if ($user->isStudent()) {
            $relationships = [
                'enrollments.group',
                'studentGrades.activity.subject',
                'parents'
            ];
        } elseif ($user->isTeacher()) {
            $relationships = [
                'subjectAssignments.subject',
                'activities'
            ];
        } elseif ($user->isParent()) {
            $relationships = [
                'children.enrollments.group'
            ];
        }
        
        // Common relationships for all users
        $relationships = array_merge($relationships, [
            'libraryLoans.resource',
            'forumPosts',
            'notifications'
        ]);

        $user->load($relationships);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        // Users can only edit their own profile, admins can edit any profile
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para editar este perfil.');
        }

        // Load grade levels and groups for student assignments
        $gradeLevels = collect();
        $groups = collect();
        
        if (Auth::user()->role === 'admin') {
            $gradeLevels = \App\Models\GradeLevel::where('status', true)
                ->orderBy('grade_name')
                ->get();
            
            $groups = \App\Models\Group::with('gradeLevel')
                ->where('status', true)
                ->orderBy('group_letter')
                ->get();
        }

        return view('users.edit', compact('user', 'gradeLevels', 'groups'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Users can only update their own profile, admins can update any profile
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para editar este perfil.');
        }

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'document' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:15',
        ];

        // Only admins can change role and active status
        if (Auth::user()->role === 'admin') {
            $rules['role'] = 'required|in:admin,teacher,student,parent';
            $rules['status'] = 'required|in:0,1';
        }

        // Only include password validation if password is being changed
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle admin-only fields
        if (Auth::user()->role === 'admin') {
            $validated['status'] = (bool) $request->input('status', 0);
        }

        $user->update($validated);

        // Handle student enrollment if admin is updating a student
        if (Auth::user()->role === 'admin' && $request->input('role') === 'student') {
            $gradeLevel = $request->input('grade_level_id');
            $group = $request->input('group_id');
            
            if ($gradeLevel && $group) {
                // Check if the student has an active enrollment
                $enrollment = \App\Models\Enrollment::where('student_id', $user->id)
                    ->where('status', 'active')
                    ->first();
                
                if ($enrollment) {
                    // Update existing enrollment
                    $enrollment->update([
                        'grade_level_id' => $gradeLevel,
                        'group_id' => $group,
                    ]);
                } else {
                    // Create new enrollment
                    \App\Models\Enrollment::create([
                        'student_id' => $user->id,
                        'grade_level_id' => $gradeLevel,
                        'group_id' => $group,
                        'academic_year' => date('Y'), // Año académico actual
                        'enrollment_date' => now(),
                        'status' => 'active',
                    ]);
                }
            }
        }

        return redirect()->route('users.show', $user)
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Only admins can delete users, and they can't delete themselves
        if (Auth::user()->role !== 'admin' || Auth::id() === $user->id) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Check if user has dependencies
        $dependencies = [];

        if ($user->role === 'student') {
            if ($user->enrollments()->count() > 0) {
                $dependencies[] = 'inscripciones activas';
            }
            if ($user->studentGrades()->count() > 0) {
                $dependencies[] = 'calificaciones';
            }
            if ($user->libraryLoans()->whereNull('actual_return_date')->count() > 0) {
                $dependencies[] = 'préstamos activos de biblioteca';
            }
        }

        if ($user->role === 'teacher') {
            if ($user->teachingSubjects()->count() > 0) {
                $dependencies[] = 'materias asignadas';
            }
            if ($user->homeroomGroups()->count() > 0) {
                $dependencies[] = 'grupos como director';
            }
        }

        if ($user->forumPosts()->where('is_approved', true)->count() > 0) {
            $dependencies[] = 'publicaciones en el foro';
        }

        if (!empty($dependencies)) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el usuario porque tiene: ' . implode(', ', $dependencies) . '.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleStatus(User $user)
    {
        // Only admins can toggle user status, and they can't deactivate themselves
        if (Auth::user()->role !== 'admin' || Auth::id() === $user->id) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
            }
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $user->update([
            'status' => !$user->status
        ]);

        $status = $user->status ? 'activado' : 'desactivado';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Usuario {$status} exitosamente.",
                'status' => $user->status
            ]);
        }

        return redirect()->back()
            ->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Search users.
     */
    public function search(Request $request): View
    {
        // Only admins can search users
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $validated = $request->validate([
            'q' => 'required|string|min:2|max:255',
            'role' => 'nullable|in:admin,teacher,student,parent'
        ]);

        $query = $validated['q'];
        $role = $validated['role'] ?? null;

        $users = User::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->when($role, function ($q, $role) {
                return $q->where('role', $role);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(20);

        return view('users.search', compact('users', 'query', 'role'));
    }

    /**
     * Display user statistics.
     */
    public function statistics(): View
    {
        // Only admins can view user statistics
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', true)->count(),
            'inactive_users' => User::where('status', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'students' => User::where('role', 'student')->count(),
            'parents' => User::where('role', 'parent')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];

        // Recent registrations
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        return view('users.statistics', compact('stats', 'recentUsers'));
    }

    /**
     * Bulk actions on users.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        // Only admins can perform bulk actions
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $validated['user_ids'];
        $action = $validated['action'];

        // Prevent admin from affecting their own account
        $userIds = array_filter($userIds, function ($id) {
            return $id != Auth::id();
        });

        if (empty($userIds)) {
            return redirect()->back()
                ->with('error', 'No se puede realizar la acción en tu propia cuenta.');
        }

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => true]);
                $message = 'Users activated successfully.';
                break;

            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => false]);
                $message = 'Users deactivated successfully.';
                break;

            case 'delete':
                // Only delete users without dependencies
                $usersToDelete = User::whereIn('id', $userIds)
                    ->whereDoesntHave('enrollments')
                    ->whereDoesntHave('teachingSubjects')
                    ->whereDoesntHave('homeroomGroups')
                    ->whereDoesntHave('studentGrades')
                    ->whereDoesntHave('libraryLoans')
                    ->get();

                $deletedCount = $usersToDelete->count();
                $usersToDelete->each->delete();

                $message = "Se eliminaron {$deletedCount} usuarios exitosamente.";
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
