<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Authentication\GuardsInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\Factory as ViewFactory;

class RegisteredUserController extends Controller
{
    private ViewFactory $viewFactory;
    private EventDispatcher $eventDispatcher;
    private AuthFactory $authFactory;
    private Redirector $redirector;
    private Hasher $hasher;

    public function __construct(
        ViewFactory $viewFactory,
        EventDispatcher $eventDispatcher,
        AuthFactory $authFactory,
        Redirector $redirector,
        Hasher $hasher
    ) {
        $this->viewFactory = $viewFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->authFactory = $authFactory;
        $this->redirector = $redirector;
        $this->hasher = $hasher;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return $this->viewFactory->make('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => $this->hasher->make($request->get('password')),
        ]);

        $this->eventDispatcher->dispatch(new Registered($user));

        $this->authFactory->guard(GuardsInterface::WEB)->login($user);

        return $this->redirector->route('admin.dashboard');
    }
}
