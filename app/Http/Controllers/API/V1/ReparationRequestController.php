<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReparationRequestCollection;
use App\Models\ReparationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class ReparationRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
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

        return (new ReparationRequestCollection($reparationRequests))
            ->toResponse($request);
    }
}
