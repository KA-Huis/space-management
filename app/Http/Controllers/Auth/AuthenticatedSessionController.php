<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Authentication\GuardsInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Factory as ViewFactory;

class AuthenticatedSessionController extends Controller
{
    private ViewFactory $viewFactory;
    private AuthFactory $authFactory;
    private Redirector $redirector;

    public function __construct(ViewFactory $viewFactory, AuthFactory $authFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->authFactory = $authFactory;
        $this->redirector = $redirector;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return $this->viewFactory->make('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authFactory->guard(GuardsInterface::WEB)->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->redirector->route('home.index');
    }
}
