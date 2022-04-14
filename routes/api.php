<?php

declare(strict_types=1);

/* @var \Illuminate\Routing\Router $router */

use App\Http\Controllers\API\V1\ReparationRequestController;
use Illuminate\Routing\Router;

$router
    ->prefix('v1')
    ->name('api.v1.')
    ->group(function (Router $router) {
        $router
            ->prefix('reparation-requests')
            ->name('reparation-request.')
            ->group(function (Router $router) {
                $router->get('/', [ReparationRequestController::class, 'index'])->name('index');
            });
    });
