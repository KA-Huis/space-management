<?php

declare(strict_types=1);

/** @var Router $router */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

$router->get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('auth.register');

$router->post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

$router->get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('auth.login');

$router->post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

$router->get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('auth.password.request');

$router->post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('auth.password.email');

$router->get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('auth.password.reset');

$router->post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('auth.password.update');

$router->get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('auth.verification.notice');

$router->get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('auth.verification.verify');

$router->post('/email/send-verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('auth.verification.send');

$router->get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('auth.password.confirm');

$router->post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

$router->post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('auth.logout');
