<?php

declare(strict_types=1);

/** @var Router $router */

use App\Http\Controllers\Admin\SpaceController;
use Illuminate\Routing\Router;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\EmailVerificationController;

$router->get('/', [HomeController::class, 'index'])
    ->name('home.index');

// Email verification
$router
    ->middleware('auth')
    ->name('email-verification.')
    ->group(function (Router $router) {
        $router->get('/email/verify', [EmailVerificationController::class, 'showNotice'])
            ->name('notice');

        $router->get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
            ->name('verify')
            ->middleware('signed');

        $router->post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationNotification'])
            ->name('send')
            ->middleware('throttle:6,1');
    });

// Admin portal
$router
    ->name('admin.')
    ->prefix('admin')
    ->middleware([
        'auth',
//        'verified', Not required
    ])
    ->group(function (Router $router) {

        $router->get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Spaces
        $router
            ->name('space.')
            ->prefix('spaces')->group(function (Router $router) {
                $router->get('/create', [SpaceController::class, 'create'])->name('create');
                $router->post('/', [SpaceController::class, 'store'])->name('store');
            });

    });
