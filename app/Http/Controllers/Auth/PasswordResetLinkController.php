<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\Factory as ViewFactory;

class PasswordResetLinkController extends Controller
{
    private ViewFactory $viewFactory;
    private Redirector $redirector;
    private PasswordBrokerContract $passwordBroker;

    public function __construct(
        ViewFactory $viewFactory,
        Redirector $redirector,
        PasswordBrokerContract $passwordBroker
    ) {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
        $this->passwordBroker = $passwordBroker;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return $this->viewFactory->make('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PasswordResetRequest $request): RedirectResponse
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $this->passwordBroker->sendResetLink(
            $request->only('email')
        );

        return $status == PasswordBrokerContract::RESET_LINK_SENT
                    ? $this->redirector->back()->with('status', __($status))
                    : $this->redirector->back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
