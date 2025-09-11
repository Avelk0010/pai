<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->status && $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if (!$user->status) {
            return false;
        }

        // Users can view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Admin can view any profile
        if ($user->role === 'admin') {
            return true;
        }

        // Teachers can view their students' profiles
        if ($user->role === 'teacher' && $model->role === 'student') {
            // Check if teacher has the student in any of their subjects or homeroom
            $hasStudent = $user->teachingSubjects()
                ->whereHas('academicPlan.gradeLevel.groups.enrollments', function ($query) use ($model) {
                    $query->where('student_id', $model->id);
                })
                ->exists();

            $isHomeroomTeacher = $user->homeroomGroups()
                ->whereHas('enrollments', function ($query) use ($model) {
                    $query->where('student_id', $model->id);
                })
                ->exists();

            return $hasStudent || $isHomeroomTeacher;
        }

        // Parents can view their children's profiles
        if ($user->role === 'parent' && $model->role === 'student') {
            return $user->parentStudents()->where('student_id', $model->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->status && $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if (!$user->status) {
            return false;
        }

        // Users can update their own profile (limited fields)
        if ($user->id === $model->id) {
            return true;
        }

        // Admin can update any profile
        if ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if (!$user->status || $user->role !== 'admin') {
            return false;
        }

        // Admin cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can change the role of the model.
     */
    public function changeRole(User $user, User $model): bool
    {
        return $user->status && $user->role === 'admin' && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can toggle the active status of the model.
     */
    public function toggleStatus(User $user, User $model): bool
    {
        if (!$user->status || $user->role !== 'admin') {
            return false;
        }

        // Admin cannot deactivate themselves
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can perform bulk actions.
     */
    public function bulkAction(User $user): bool
    {
        return $user->status && $user->role === 'admin';
    }

    /**
     * Determine whether the user can view user statistics.
     */
    public function viewStatistics(User $user): bool
    {
        return $user->status && $user->role === 'admin';
    }

    /**
     * Determine whether the user can search users.
     */
    public function search(User $user): bool
    {
        return $user->status && $user->role === 'admin';
    }
}
