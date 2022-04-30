<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\UpdateSpaceRequest;
use App\API\V1\Http\Resources\ReparationRequestResource;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

final class SpaceController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function update(UpdateSpaceRequest $request, Space $space): ReparationRequestResource
    {
        $this->authorize('update', $space);

        $space->fill($request->safe()->all());
        $space->save();

        return new SpaceResource($space);
    }
}
