<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->status && in_array($user->role, ['admin', 'teacher', 'student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        if (!$user->status || !$activity->status) {
            return false;
        }

        // Admin can view all activities
        if ($user->role === 'admin') {
            return true;
        }

        // Teacher can view activities for their subjects
        if ($user->role === 'teacher') {
            return $activity->subject->teacher_id === $user->id;
        }

        // Students can view activities for their enrolled subjects
        if ($user->role === 'student') {
            $enrollment = $user->enrollments()->where('status', 'active')->first();
            if (!$enrollment) {
                return false;
            }

            // Check if the activity is for one of the student's group subjects
            $subjectIds = \App\Models\SubjectAssignment::where('group_id', $enrollment->group_id)
                ->pluck('subject_id')
                ->toArray();
                
            return in_array($activity->subject_id, $subjectIds);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->status && in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        if (!$user->status) {
            return false;
        }

        // Admin can update all activities
        if ($user->role === 'admin') {
            return true;
        }

        // Teacher can update activities for their subjects
        if ($user->role === 'teacher') {
            return $activity->subject->teacher_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        if (!$user->status) {
            return false;
        }

        // Admin can delete all activities
        if ($user->role === 'admin') {
            return true;
        }

        // Teacher can delete activities for their subjects (if no grades exist)
        if ($user->role === 'teacher') {
            return $activity->subject->teacher_id === $user->id && $activity->studentGrades()->count() === 0;
        }

        return false;
    }

    /**
     * Determine whether the user can grade students for this activity.
     */
    public function grade(User $user, Activity $activity): bool
    {
        if (!$user->status) {
            return false;
        }

        // Admin can grade all activities
        if ($user->role === 'admin') {
            return true;
        }

        // Teacher can grade activities for their subjects
        if ($user->role === 'teacher') {
            return $activity->subject->teacher_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can export grades for this activity.
     */
    public function exportGrades(User $user, Activity $activity): bool
    {
        return $this->grade($user, $activity);
    }
}
