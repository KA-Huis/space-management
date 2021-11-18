<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class NewPasswordController extends Controller
{
    private ViewFactory $viewFactory;
    private Redirector $redirector;
    private PasswordBroker $passwordBroker;
    private Hasher $hasher;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        ViewFactory $viewFactory,
        Redirector $redirector,
        PasswordBroker $passwordBroker,
        Hasher $hasher,
        EventDispatcher $eventDispatcher
    )
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
        $this->passwordBroker = $passwordBroker;
        $this->hasher = $hasher;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return $this->viewFactory->make('auth.reset-password', [
                'request' => $request,
            ],
        );
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(NewPasswordRequest $request): RedirectResponse
    {
        $credentials = $request->only([
            'email',
            'password',
            'password_confirmation',
            'token'
        ]);

        $status = $this->passwordBroker->reset(
            $credentials,
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => $this->hasher->make($request->get('password')),
                    'remember_token' => Str::random(60),
                ])->save();

                $this->eventDispatcher->dispatch(new PasswordReset($user));
            }
        );

        return $status == PasswordBroker::PASSWORD_RESET
            ? $this->redirector->route('auth.login')->with('status', __($status))
            : $this->redirector->back()->withInput($request->only(['email']))
                ->withErrors(['email' => __($status)]);
    }
}
