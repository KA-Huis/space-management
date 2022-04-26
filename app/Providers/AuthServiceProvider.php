<?php

declare(strict_types=1);

namespace App\Providers;

use App\ACL\Roles\AdminRole;
use App\Models\ReparationRequest;
use App\Models\User;
use App\Policies\ReparationRequestPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $policies = [
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
