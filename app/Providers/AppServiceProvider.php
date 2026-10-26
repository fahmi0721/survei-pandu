<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
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
        Gate::define('access-dashboard', function (User $user) {
            return in_array($user->role, ['admin', 'hrd'], true);
        });

        Gate::define('view-respondents', function (User $user) {
            return in_array($user->role, ['admin', 'hrd'], true);
        });

        Gate::define('view-survey-results', function (User $user) {
            return in_array($user->role, ['admin', 'hrd'], true);
        });

        Gate::define('manage-surveys', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-work-areas', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
