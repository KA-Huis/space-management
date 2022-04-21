<?php

declare(strict_types=1);

/* @var Router $router */

use Illuminate\Routing\Router;
use App\API\V1\Http\Controllers\ReparationRequestController;

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

        $router
            ->prefix('reparation_requests_material')
            ->name('reparation-request-material.')
            ->group(function (Router $router) {
                $router->get('/', [\App\API\V1\Http\Controllers\ReparationRequestMaterialController::class, 'index'])->name('index');
            });
    });
