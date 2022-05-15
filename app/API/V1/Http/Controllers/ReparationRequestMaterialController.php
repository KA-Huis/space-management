<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\StoreReparationRequestMaterialRequest;
use App\API\V1\Http\Requests\UpdateReparationRequestMaterialRequest;
use App\API\V1\Http\Resources\ReparationRequestMaterialCollection;
use App\API\V1\Http\Resources\ReparationRequestMaterialResource;
use App\Http\Controllers\Controller;
use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class ReparationRequestMaterialController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): ReparationRequestMaterialCollection
    {
        $this->authorize('viewAny', ReparationRequestMaterial::class);

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

    /**
     * @throws AuthorizationException
     */
    public function show(ReparationRequestMaterial $reparationRequestMaterial): ReparationRequestMaterialResource
    {
        $this->authorize('view', $reparationRequestMaterial);

        return new ReparationRequestMaterialResource($reparationRequestMaterial);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreReparationRequestMaterialRequest $request): ReparationRequestMaterialResource
    {
        $this->authorize('create', ReparationRequestMaterial::class);

        $reparationRequest = ReparationRequest::find((int) $request->safe()->collect()->get('reparation_request_id'));

        $reparationRequestMaterial = ReparationRequestMaterial::make($request->safe()
            ->except(['reparation_request_id']));
        $reparationRequestMaterial->reparationRequest()->associate($reparationRequest);
        $reparationRequestMaterial->save();

        return new ReparationRequestMaterialResource($reparationRequestMaterial);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(
        UpdateReparationRequestMaterialRequest $request,
        ReparationRequestMaterial $reparationRequestMaterial
    ): ReparationRequestMaterialResource {
        $this->authorize('update', $reparationRequestMaterial);

        $reparationRequest = ReparationRequest::find((int) $request->get('reparation_request_id'));

        $reparationRequestMaterial->fill($request->safe()->all());
        $reparationRequestMaterial->reparationRequest()->associate($reparationRequest);
        $reparationRequestMaterial->save();

        return new ReparationRequestMaterialResource($reparationRequestMaterial);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(ReparationRequestMaterial $reparationRequestMaterial): JsonResponse
    {
        $this->authorize('delete', $reparationRequestMaterial);

        $reparationRequestMaterial->delete();

        return new JsonResponse();
    }
}
