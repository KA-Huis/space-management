<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;

class VerifyEmailController extends Controller
{
    private Redirector $redirector;
    private UrlGenerator $urlGenerator;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        Redirector $redirector,
        UrlGenerator $urlGenerator,
        EventDispatcher $eventDispatcher
    ) {
        $this->redirector = $redirector;
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirector->intended(
                $this->urlGenerator->route('admin.dashboard', [
                    'verified' => true,
                ])
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            $this->eventDispatcher->dispatch((new Verified($request->user())));
        }

        return $this->redirector->intended(
            $this->urlGenerator->route('admin.dashboard', [
                'verified' => true,
            ])
        );
    }
}
