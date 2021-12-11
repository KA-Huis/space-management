<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Translation\Translator;

class EmailVerificationController extends Controller
{
    private ViewFactory $viewFactory;
    private Redirector $redirector;
    private Translator $translator;

    public function __construct(
        ViewFactory $viewFactory,
        Redirector $redirector,
        Translator $translator
    ) {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
        $this->translator = $translator;
    }

    public function showNotice(): View
    {
        return $this->viewFactory->make('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return $this->redirector->route('home');
    }

    public function sendVerificationNotification(Request $request): RedirectResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return $this
            ->redirector
            ->back()
            ->with('message', $this->translator->get('auth/send_verification_notification.verification_link_sent'));
    }
}
