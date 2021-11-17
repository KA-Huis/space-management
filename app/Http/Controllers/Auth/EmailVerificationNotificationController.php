<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;

class EmailVerificationNotificationController extends Controller
{
    private Redirector $redirector;
    private UrlGenerator $urlGenerator;

    public function __construct(
        Redirector $redirector,
        UrlGenerator $urlGenerator
    )
    {
        $this->redirector = $redirector;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirector->intended(
                $this->urlGenerator->route('admin.dashboard')
            );
        }

        $request->user()->sendEmailVerificationNotification();

        return $this
            ->redirector
            ->back()
            ->with('status', 'verification-link-sent');
    }
}
