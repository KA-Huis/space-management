<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\AuthorizedUser;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        if ($this->emailIsNotVerified($request->user())) {
            return $request->expectsJson()
                ? $this->unverifiedErrorJsonResponse()
                : $this->redirectToNoticeResponse();
        }

        return $next($request);
    }

    private function redirectToNoticeResponse(): RedirectResponse
    {
        return Redirect::guest(URL::route('auth.verification.notice'));
    }

    private function unverifiedErrorJsonResponse(): void
    {
        throw new HttpException(403, 'Your email address is not verified.');
    }

    private function emailIsNotVerified(?AuthorizedUser $user): bool
    {
        return !$user instanceof AuthorizedUser
            || (
                $user instanceof MustVerifyEmail
                && !$user->hasVerifiedEmail()
            );
    }
}
