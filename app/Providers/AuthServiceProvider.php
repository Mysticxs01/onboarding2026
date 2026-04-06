<?php

namespace App\Providers;

use App\Models\CheckInAcceso;
use App\Models\AuditoriaOnboarding;
use App\Policies\CheckInAccesoPolicy;
use App\Policies\AuditoriaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CheckInAcceso::class => CheckInAccesoPolicy::class,
        AuditoriaOnboarding::class => AuditoriaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Definir gates para las autorizaciones del check-in
        Gate::define('viewCheckInAdmin', function ($user) {
            return $user->hasAnyRole(['Admin', 'Root']);
        });

        Gate::define('exportCheckIn', function ($user) {
            return $user->hasAnyRole(['Admin', 'Root']);
        });

        Gate::define('viewAreaStatistics', function ($user) {
            return $user->hasAnyRole(['Admin', 'Root']);
        });
    }
}
