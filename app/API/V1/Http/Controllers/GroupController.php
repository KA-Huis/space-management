<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\StoreGroupRequest;
use App\API\V1\Http\Requests\UpdateGroupRequest;
use App\API\V1\Http\Resources\GroupCollection;
use App\API\V1\Http\Resources\GroupResource;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class GroupController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): GroupCollection
    {
        $this->authorize('viewAny', Group::class);

        $groups = QueryBuilder::for(Group::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('group_type_id'),
            ])
            ->allowedIncludes([
                AllowedInclude::relationship('groupType'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->jsonPaginate();

        return new GroupCollection($groups);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Group $group): GroupResource
    {
        $this->authorize('view', $group);

        return new GroupResource($group);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreGroupRequest $request): GroupResource
    {
        $this->authorize('create', Group::class);

        $group = Group::make($request->safe()->all());
        $group->save();

        return new GroupResource($group);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateGroupRequest $request, Group $group): GroupResource
    {
        $this->authorize('update', $group);

        $group->fill($request->safe()->all());
        $group->save();

        return new GroupResource($group);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Group $group): JsonResponse
    {
        $this->authorize('delete', $group);

        $group->delete();

        return new JsonResponse();
    }
}
