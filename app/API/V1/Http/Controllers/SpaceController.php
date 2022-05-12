<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\StoreSpaceRequest;
use App\API\V1\Http\Requests\UpdateSpaceRequest;
use App\API\V1\Http\Resources\SpaceCollection;
use App\API\V1\Http\Resources\SpaceResource;
use App\Http\Controllers\Controller;
use App\Models\ReparationRequest;
use App\Models\Space;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SpaceController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): SpaceCollection
    {
        $this->authorize('viewAny', Space::class);

        $spaces = QueryBuilder::for(Space::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
                AllowedFilter::exact('is_open_for_reservations'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->jsonPaginate();

        return new SpaceCollection($spaces);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Space $space): SpaceResource
    {
        $this->authorize('view', $space);

        return new SpaceResource($space);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreSpaceRequest $request): SpaceResource
    {
        $this->authorize('create', ReparationRequest::class);

        $space = Space::make($request->safe()->all());
        $space->save();

        return new SpaceResource($space);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateSpaceRequest $request, Space $space): SpaceResource
    {
        $this->authorize('update', $space);

        $space->fill($request->safe()->all());
        $space->save();

        return new SpaceResource($space);
    }

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
