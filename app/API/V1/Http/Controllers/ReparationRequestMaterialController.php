<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\API\V1\Http\Requests\UpdateReparationRequest;
use App\API\V1\Http\Resources\ReparationRequestMaterialCollection;
use App\API\V1\Http\Resources\ReparationRequestMaterialResource;
use App\Http\Controllers\Controller;
use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;
use App\API\V1\Http\Requests\StoreReparationRequestMaterialRequest;
use App\API\V1\Http\Requests\UpdateReparationRequestMaterialRequest;

class ReparationRequestMaterialController extends Controller
{
    public function index(): ReparationRequestMaterialCollection
    {
        $reparationRequestMaterial = QueryBuilder::for(ReparationRequestMaterial::class)
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

        return new ReparationRequestMaterialCollection($reparationRequestMaterial);
    }

    public function show(ReparationRequestMaterial $reparationRequestMaterial): ReparationRequestMaterialResource
    {

        return new ReparationRequestMaterialResource($reparationRequestMaterial);
    }

    public function store(StoreReparationRequestMaterialRequest $request): ReparationRequestMaterialResource
    {
        $reparationRequest = ReparationRequest::find((int) $request->get('reparation_request_id'));

        $reparationRequestMaterial = ReparationRequestMaterial::make($request->except(['reparation_request_id']));
        $reparationRequestMaterial->reparationRequest()->associate($reparationRequest);
        $reparationRequestMaterial->save();

        return new ReparationRequestMaterialResource($reparationRequestMaterial);
    }

    public function update(UpdateReparationRequestMaterialRequest $request, ReparationRequestMaterial $reparationRequestMaterial): ReparationRequestMaterialResource
    {
        $reparationRequest = ReparationRequest::find((int) $request->get('reparation_request_id'));

        $reparationRequestMaterial->fill($request->safe()->all());
        $reparationRequestMaterial->reparationRequest()->associate($reparationRequest);
        $reparationRequestMaterial->save();

        return new ReparationRequestMaterialResource($reparationRequestMaterial);
    }
}
