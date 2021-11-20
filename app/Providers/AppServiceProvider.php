<?php

namespace App\Providers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\ResponseFactory as InertiaResponseFactory;


class AppServiceProvider extends ServiceProvider
{
    /** @return void */
    public function register()
    {
       //
    }

    /** @return void */
    public function boot()
    {
        $this->bootInertia();
    }

    public function bootInertia(): void
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
                    'error' => $session->get('error'),
                    'status' => $session->get('status'),
                ];
            },
            'errors' => function () use ($session) {
                return $session->get('errors')
                    ? $session->get('errors')->getBag('default')->getMessages()
                    : (object)[];
            },
        ]);
    }
}
