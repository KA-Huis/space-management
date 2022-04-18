<?php

declare(strict_types=1);

/* @var \Illuminate\Routing\Router $router */

use Illuminate\Routing\Router;
use App\Http\Controllers\API\V1\ReparationRequestMaterialController;

$router
    ->prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::resource('reparationRequestMaterial',ReparationRequestMaterialController::class);
    });
