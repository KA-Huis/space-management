<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Auth\Access\AuthorizationException;

final class UserController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(Space $space): UserResource
    {
        $this->authorize('view', $space);

        return new UserResource($space);
    }
}
