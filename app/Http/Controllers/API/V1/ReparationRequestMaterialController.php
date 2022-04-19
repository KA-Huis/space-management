<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ReparationRequestMaterialStoreRequest;
use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class ReparationRequestMaterialController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        $materialRequest = QueryBuilder::for(ReparationRequestMaterial::class)
            ->allowedFilters([
                AllowedFilter::partial('uuid'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
                AllowedFilter::exact('is_mandatory'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                AllowedInclude::relationship('reparationRequest'),
            ])
            ->jsonPaginate();

        return $materialRequest;
    }

    public function store(ReparationRequestMaterialStoreRequest $reparationRequestMaterialStoreRequest): ReparationRequestMaterial
    {
        $reparationRequest = ReparationRequest::find((int) $reparationRequestMaterialStoreRequest->get('reparation_request_id'));

        $reparationRequestMaterial = ReparationRequestMaterial::make($reparationRequestMaterialStoreRequest->except(['reparation_request_id']));
        $reparationRequestMaterial->reparationRequest()->associate($reparationRequest);
        $reparationRequestMaterial->save();

        return $reparationRequestMaterial;
    }
}
