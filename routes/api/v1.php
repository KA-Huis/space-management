<?php

declare(strict_types=1);

/* @var Router $router */

use App\API\V1\Http\Controllers\ReparationRequestController;
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
                $router->get('/{reparationRequest}', [ReparationRequestController::class, 'show'])->name('show');
            });
    });
