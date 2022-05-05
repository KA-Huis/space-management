<?php

declare(strict_types=1);

namespace App\API\V1\Http\Controllers;

use App\API\V1\Http\Requests\StoreReservationRequest;
use App\API\V1\Http\Requests\UpdateReservationRequest;
use App\API\V1\Http\Resources\ReservationCollection;
use App\API\V1\Http\Resources\ReservationResource;
use App\Authentication\Guards\RestApiGuard;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ReservationController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): ReservationCollection
    {
        $this->authorize('viewAny', Reservation::class);

        $reservations = QueryBuilder::for(Reservation::class)
            ->allowedFilters([
                AllowedFilter::exact('space_id'),
                AllowedFilter::exact('group_id'),
                AllowedFilter::exact('created_by_user_id'),
            ])
            ->allowedSorts([
                'starts_at',
                'ends_at',
                'created_at',
                'updated_at',
            ])
            ->jsonPaginate();

        return new ReservationCollection($reservations);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Reservation $reservation): ReservationResource
    {
        $this->authorize('view', $reservation);

        return new ReservationResource($reservation);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreReservationRequest $request): ReservationResource
    {
        $this->authorize('create', Reservation::class);

        $reservation = Reservation::make($request->safe()->all());
        $reservation->createdByUser()->associate($request->user((new RestApiGuard())->getName()));
        $reservation->save();

        return new ReservationResource($reservation);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation): ReservationResource
    {
        $this->authorize('update', $reservation);

        $reservation->fill($request->safe()->all());
        $reservation->save();

        return new ReservationResource($reservation);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return new JsonResponse();
    }
}
