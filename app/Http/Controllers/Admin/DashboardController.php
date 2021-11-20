<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Inertia\ResponseFactory as InertiaResponseFactory;
use Inertia\Response as InertiaResponse;

class DashboardController extends Controller
{
    private InertiaResponseFactory $inertiaResponseFactory;

    public function __construct(InertiaResponseFactory $inertiaResponseFactory)
    {
        $this->inertiaResponseFactory = $inertiaResponseFactory;
    }

    public function index(Request $request): InertiaResponse
    {
        $user = $request->user();

        return $this->inertiaResponseFactory->render('Admin/Dashboard', [
            'user' => [
                'full_name' => $user->getFullName(),
            ],
        ]);
    }
}
