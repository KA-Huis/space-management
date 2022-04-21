<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Resources\ReparationRequestCollection;
use App\API\V1\Http\Resources\ReparationRequestResource;
use App\Http\Controllers\Controller;
use App\Models\ReparationRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class ReparationRequestController extends Controller
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
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

    public function show(ReparationRequest $reparationRequest): ReparationRequestResource
    {
        return new ReparationRequestResource($reparationRequest);
    }
}
