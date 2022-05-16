<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Resources\PrivateUserResource;
use App\API\V1\Http\Resources\PublicUserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

final class UserController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(User $user): PublicUserResource|PrivateUserResource
    {
        $this->authorize('view', $user);

        if ($this->authorize('viewPrivateProfile', $user)->allowed()) {
            return new PrivateUserResource($user);
        }

        return new PublicUserResource($user);
    }
}
