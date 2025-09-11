<?php

namespace App\Policies;

use App\Models\ForumPost;
use App\Models\User;

class ForumPostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->status === true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ForumPost $forumPost): bool
    {
        return $user->status === true && $forumPost->is_approved;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->status === true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ForumPost $forumPost): bool
    {
        return $user->status === true && ($user->id === $forumPost->author_id || $user->role === 'admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ForumPost $forumPost): bool
    {
        return $user->status === true && ($user->id === $forumPost->author_id || $user->role === 'admin');
    }

    /**
     * Determine whether the user can pin/unpin the model.
     */
    public function pin(User $user, ForumPost $forumPost): bool
    {
        return $user->status === true && $user->role === 'admin';
    }
}
