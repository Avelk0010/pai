<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\ForumPost;
use App\Models\Activity;
use App\Policies\UserPolicy;
use App\Policies\ForumPostPolicy;
use App\Policies\ActivityPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        ForumPost::class => ForumPostPolicy::class,
        Activity::class => ActivityPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Define additional gates
        Gate::define('manage-academic-plans', function (User $user) {
            return $user->status && $user->role === 'admin';
        });

        Gate::define('manage-groups', function (User $user) {
            return $user->status && $user->role === 'admin';
        });

        Gate::define('manage-enrollments', function (User $user) {
            return $user->status && $user->role === 'admin';
        });

        Gate::define('manage-subjects', function (User $user) {
            return $user->status && in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('manage-library-resources', function (User $user) {
            return $user->status && $user->role === 'admin';
        });

        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->status && $user->role === 'admin';
        });

        Gate::define('view-teacher-dashboard', function (User $user) {
            return $user->status && $user->role === 'teacher';
        });

        Gate::define('view-student-dashboard', function (User $user) {
            return $user->status && $user->role === 'student';
        });

        Gate::define('view-parent-dashboard', function (User $user) {
            return $user->status && $user->role === 'parent';
        });
    }
}
