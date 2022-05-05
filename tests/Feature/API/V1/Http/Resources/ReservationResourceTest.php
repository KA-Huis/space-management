<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Resources;

use App\API\V1\Http\Resources\ReservationResource;
use App\Models\Group;
use App\Models\GroupType;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery;
use Tests\TestCase;

class ReservationResourceTest extends TestCase
{
    public function testDefaultResponse(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(Space::factory())
            ->for(User::factory(), 'createdByUser')
            ->create();
        $reservationResource = new ReservationResource($reservation);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reservationResource->toResponse($request);

        // Then
        $castedReservation = $reservation->toArray();

        self::assertEquals(
            [
                'id'                 => Arr::get($castedReservation, 'id'),
                'starts_at'          => Arr::get($castedReservation, 'starts_at'),
                'ends_at'            => Arr::get($castedReservation, 'ends_at'),
                'space_id'           => Arr::get($castedReservation, 'space_id'),
                'created_by_user_id' => Arr::get($castedReservation, 'created_by_user_id'),
                'group_id'           => Arr::get($castedReservation, 'group_id'),
                'created_at'         => Arr::get($castedReservation, 'created_at'),
                'updated_at'         => Arr::get($castedReservation, 'updated_at'),
                'deleted_at'         => Arr::get($castedReservation, 'deleted_at'),
            ],
            Arr::get((array) $response->getData(true), 'data')
        );
    }

    public function testOptionalIncludedCreatedByUser(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(Space::factory())
            ->for(User::factory(), 'createdByUser')
            ->create();
        $reservationResource = new ReservationResource($reservation);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reservationResource->toArray($request);

        // Then
        $this->assertArrayHasKey('created_by_user', $response);
    }

    public function testOptionalIncludedSpace(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(Space::factory())
            ->for(User::factory(), 'createdByUser')
            ->create();
        $reservationResource = new ReservationResource($reservation);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reservationResource->toArray($request);

        // Then
        $this->assertArrayHasKey('space', $response);
    }

    public function testOptionalIncludedGroup(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(Space::factory())
            ->for(Group::factory()
                ->for(GroupType::factory())
            )
            ->for(User::factory(), 'createdByUser')
            ->create();
        $reservationResource = new ReservationResource($reservation);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reservationResource->toArray($request);

        // Then
        $this->assertArrayHasKey('group', $response);
    }
}
