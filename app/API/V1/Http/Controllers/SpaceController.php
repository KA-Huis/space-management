<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\StoreReparationRequest;
use App\Authentication\Guards\RestApiGuard;
use App\Http\Controllers\Controller;
use App\Models\ReparationRequest;
use App\Models\Space;
use Illuminate\Auth\Access\AuthorizationException;

final class SpaceController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function store(StoreReparationRequest $request): SpaceResource
    {
        $this->authorize('create', ReparationRequest::class);

        $space = Space::make($request->safe()->all());
        $space->reporter()->associate($request->user((new RestApiGuard())->getName()));
        $space->save();

        return new SpaceResource($space);
    }
}
