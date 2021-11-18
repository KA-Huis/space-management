<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class EmailVerificationPromptController extends Controller
{
    private Redirector $redirector;
    private ViewFactory $viewFactory;

    public function __construct(Redirector $redirector, ViewFactory $viewFactory)
    {
        $this->redirector = $redirector;
        $this->viewFactory = $viewFactory;
    }

    /**
     * Display the email verification prompt.
     *
     * @return RedirectResponse|View
     */
    public function __invoke(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirector->intended('admin.dashboard');
        }

        return $this->viewFactory->make('auth.verify-email');
    }
}
