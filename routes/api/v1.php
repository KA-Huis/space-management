<?php

declare(strict_types=1);

/* @var Router $router */

use App\API\V1\Http\Controllers\GroupController;
use App\API\V1\Http\Controllers\ReparationRequestController;
use App\API\V1\Http\Controllers\ReparationRequestMaterialController;
use App\API\V1\Http\Controllers\ReservationController;
use App\API\V1\Http\Controllers\SpaceController;
use Illuminate\Routing\Router;

$router
    ->prefix('v1')
    ->name('api.v1.')
    ->group(static function (Router $router) {
        $router
            ->prefix('reparation_requests')
            ->name('reparation-request.')
            ->group(static function (Router $router) {
                $router->get('/', [ReparationRequestController::class, 'index'])->name('index');
                $router->post('/', [ReparationRequestController::class, 'store'])->name('store');
                $router->put('/{reparationRequest}', [ReparationRequestController::class, 'update'])->name('update');
                $router->get('/{reparationRequest}', [ReparationRequestController::class, 'show'])->name('show');
                $router->delete('/{reparationRequest}', [ReparationRequestController::class, 'destroy'])->name('destroy');
            });

        $router
            ->prefix('reparation_request_materials')
            ->name('reparation-request-material.')
            ->group(static function (Router $router) {
                $router->get('/', [ReparationRequestMaterialController::class, 'index'])->name('index');
                $router->post('/', [ReparationRequestMaterialController::class, 'store'])->name('store');
                $router->put('/{reparationRequestMaterial}', [ReparationRequestMaterialController::class, 'update'])->name('update');
                $router->get('/{reparationRequestMaterial}', [ReparationRequestMaterialController::class, 'show'])->name('show');
                $router->delete('/{reparationRequestMaterial}', [ReparationRequestMaterialController::class, 'destroy'])->name('destroy');
            });

        $router
            ->prefix('spaces')
            ->name('space.')
            ->group(static function (Router $router) {
                $router->get('/', [SpaceController::class, 'index'])->name('index');
                $router->post('/', [SpaceController::class, 'store'])->name('store');
                $router->get('/{space}', [SpaceController::class, 'show'])->name('show');
                $router->put('/{space}', [SpaceController::class, 'update'])->name('update');
                $router->delete('/{space}', [SpaceController::class, 'destroy'])->name('destroy');
            });

        $router
            ->prefix('reservations')
            ->name('reservation.')
            ->group(static function (Router $router) {
                $router->get('/', [ReservationController::class, 'index'])->name('index');
                $router->post('/', [ReservationController::class, 'store'])->name('store');
                $router->put('/{reservation}', [ReservationController::class, 'update'])->name('update');
                $router->get('/{reservation}', [ReservationController::class, 'show'])->name('show');
                $router->delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
            });

        $router
            ->prefix('groups')
            ->name('group.')
            ->group(static function (Router $router) {
                $router->get('/', [GroupController::class, 'index'])->name('index');
                $router->post('/', [GroupController::class, 'store'])->name('store');
                $router->put('/{group}', [GroupController::class, 'update'])->name('update');
                $router->get('/{group}', [GroupController::class, 'show'])->name('show');
                $router->delete('/{group}', [GroupController::class, 'destroy'])->name('destroy');
            });
    });
