<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Resources\SpaceCollection;
use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Auth\Access\AuthorizationException;
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
}
