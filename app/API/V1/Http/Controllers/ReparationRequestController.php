<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\StoreReparationRequest;
use App\API\V1\Http\Requests\UpdateReparationRequest;
use App\API\V1\Http\Resources\ReparationRequestCollection;
use App\API\V1\Http\Resources\ReparationRequestResource;
use App\Authentication\Guards\RestApiGuard;
use App\Http\Controllers\Controller;
use App\Models\ReparationRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class ReparationRequestController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): ReparationRequestCollection
    {
        $this->authorize('viewAny', ReparationRequest::class);

        $reparationRequests = QueryBuilder::for(ReparationRequest::class)
            ->allowedFilters([
                AllowedFilter::exact('uuid'),
                AllowedFilter::partial('title'),
                AllowedFilter::partial('description'),
                AllowedFilter::exact('priority'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                AllowedInclude::relationship('reporter'),
                AllowedInclude::relationship('statuses'),
                AllowedInclude::relationship('materials'),
            ])
            ->jsonPaginate();

        return new ReparationRequestCollection($reparationRequests);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(ReparationRequest $reparationRequest): ReparationRequestResource
    {
        $this->authorize('view', $reparationRequest);

        return new ReparationRequestResource($reparationRequest);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreReparationRequest $request): ReparationRequestResource
    {
        $this->authorize('create', ReparationRequest::class);

        $reparationRequest = ReparationRequest::make($request->safe()->all());
        $reparationRequest->reporter()->associate($request->user((new RestApiGuard())->getName()));
        $reparationRequest->save();

        return new ReparationRequestResource($reparationRequest);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateReparationRequest $request, ReparationRequest $reparationRequest): ReparationRequestResource
    {
        $this->authorize('update', $reparationRequest);

        $reparationRequest->fill($request->safe()->all());
        $reparationRequest->save();

        return new ReparationRequestResource($reparationRequest);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(ReparationRequest $reparationRequest): JsonResponse
    {
        $this->authorize('delete', $reparationRequest);

        $reparationRequest->delete();

        return new JsonResponse();
    }
}
