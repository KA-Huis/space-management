<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Space\StoreSpaceRequest;
use App\Models\Space;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\ResponseFactory as InertiaResponseFactory;
use Inertia\Response as InertiaResponse;

class SpaceController extends Controller
{
    private InertiaResponseFactory $inertiaResponseFactory;
    private Redirector $redirector;
    private Translator $translator;

    public function __construct(
        InertiaResponseFactory $inertiaResponseFactory,
        Redirector $redirector,
        Translator $translator
    )
    {
        $this->inertiaResponseFactory = $inertiaResponseFactory;
        $this->redirector = $redirector;
        $this->translator = $translator;
    }

    public function create(Request $request): InertiaResponse
    {
        $user = $request->user();

        return $this->inertiaResponseFactory->render('Admin/Space/Create', [
            'user' => [
                'full_name' => $user->getFullName(),
            ],
        ]);
    }

    public function store(StoreSpaceRequest $request): RedirectResponse
    {
        $space = new Space();
        $space->name = $request->get('name');
        $space->description = $request->get('description');
        $space->is_open_for_reservations = $request->get('is_open_for_reservations');
        $space->save();

        return $this
            ->redirector
            ->back()
            ->with(
                'success',
                $this->translator->get('pages/space/create.messages.successfully_created_space')
            );
    }
}
