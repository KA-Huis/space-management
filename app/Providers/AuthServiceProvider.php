<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ReparationRequest;
use App\Policies\ReparationRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $policies = [
        ReparationRequest::class => ReparationRequestPolicy::class,
    ];

    /** @return void */
    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes(
                options: [
                    'prefix' => 'login/oauth',
                ]
            );
        }
    }
}
