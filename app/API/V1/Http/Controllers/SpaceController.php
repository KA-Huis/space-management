<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

final class SpaceController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(Space $space): SpaceResource
    {
        $this->authorize('view', $space);

        return new SpaceResource($space);
    }
}
