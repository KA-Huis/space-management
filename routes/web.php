<?php

declare(strict_types=1);

/** @var Router $router */

use Illuminate\Routing\Router;
use App\Http\Controllers\Auth\EmailVerificationController;

$router->get('/', function () {
        return "test";
    })
    ->name('home');

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
    ->middleware('verified')
    ->group(function (Router $router) {
        //
    });
