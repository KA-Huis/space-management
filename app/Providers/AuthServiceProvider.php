<?php

declare(strict_types=1);

namespace App\Providers;

use App\ACL\Roles\AdminRole;
use App\Models\Group;
use App\Models\ReparationRequest;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use App\Policies\GroupPolicy;
use App\Policies\ReparationRequestPolicy;
use App\Policies\ReservationPolicy;
use App\Policies\SpacePolicy;
use App\Policies\UserPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $policies = [
        Group::class => GroupPolicy::class,
        Reservation::class => ReservationPolicy::class,
        Space::class => SpacePolicy::class,
        User::class => UserPolicy::class,
        ReparationRequest::class => ReparationRequestPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        $this->setupGateHooks();

        if (!$this->app->routesAreCached()) {
            Passport::routes(
                options: [
                    'prefix' => 'login/oauth',
                ],
            );
        }
    }

    private function setupGateHooks(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        $this->app->get(Gate::class)->before(function (User $user, $ability): bool|null {
            if ($user->hasRole((new AdminRole())->getName())) {
                return true;
            }

            return null;
        });
    }
}
