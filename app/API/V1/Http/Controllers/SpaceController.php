<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

final class SpaceController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function destroy(Space $space): JsonResponse
    {
        $this->authorize('delete', $space);

        $space->delete();

        return new JsonResponse();
    }
}
