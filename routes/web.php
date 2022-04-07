<?php

declare(strict_types=1);

/** @var Router $router */

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SpaceController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Models\AuthorizedUser;
use Illuminate\Routing\Router;

$router->get('/', [HomeController::class, 'index'])
    ->name('home.index');

// Email verification
$router
    ->middleware([
        sprintf('auth:%s', AuthorizedUser::AUTHENTICATION_GUARD),
    ])
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
        sprintf('auth:%s', AuthorizedUser::AUTHENTICATION_GUARD),
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
