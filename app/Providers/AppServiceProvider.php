<?php

declare(strict_types=1);

namespace App\Providers;

use App\ACL\ACLService;
use App\ACL\Contracts\ACLService as ACLServiceContract;
use App\ACL\Contracts\RolesProvider;
use App\ACL\Roles\Providers\DefaultRolesProvider;
use App\Authentication\Contracts\GuardService as GuardServiceContract;
use App\Authentication\GuardService;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\ResponseFactory as InertiaResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /** @return void */
    public function register()
    {
        $this->registerServices();
    }

    /** @return void */
    public function boot()
    {
        $this->bootInertia();
    }

    private function registerServices(): void
    {
        $this->app->singleton(RolesProvider::class, DefaultRolesProvider::class);
        $this->app->singleton(ACLServiceContract::class, ACLService::class);
        $this->app->singleton(GuardServiceContract::class, GuardService::class);
    }

    private function bootInertia(): void
    {
        $responseFactory = $this->app->get(InertiaResponseFactory::class);
        $session = $this->app->get(Session::class);

        $responseFactory->version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        $responseFactory->setRootView('layouts.app');

        $responseFactory->share([
            'flash' => function () use ($session) {
                return [
                    'success' => $session->get('success'),
                    'error'   => $session->get('error'),
                    'status'  => $session->get('status'),
                ];
            },
            'errors' => function () use ($session) {
                return $session->get('errors')
                    ? $session->get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
        ]);
    }
}
