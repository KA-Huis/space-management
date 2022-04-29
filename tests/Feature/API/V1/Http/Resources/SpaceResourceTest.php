<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Resources;

use App\API\V1\Http\Resources\SpaceResource;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery;
use Tests\TestCase;

class SpaceResourceTest extends TestCase
{
    public function testDefaultResponse(): void
    {
        // Given
        $space = Space::factory()->create();
        $spaceResource = new SpaceResource($space);
        $request = Mockery::mock(Request::class);

        // When
        $response = $spaceResource->toResponse($request);

        // Then
        $castedReparationRequest = $space->toArray();

        self::assertEquals(
            [
                'id'                      => Arr::get($castedReparationRequest, 'id'),
                'name'                    => Arr::get($castedReparationRequest, 'name'),
                'is_open_for_reservation' => Arr::get($castedReparationRequest, 'is_open_for_reservation'),
                'created_at'              => Arr::get($castedReparationRequest, 'created_at'),
                'updated_at'              => Arr::get($castedReparationRequest, 'updated_at'),
                'deleted_at'              => Arr::get($castedReparationRequest, 'deleted_at'),
            ],
            Arr::get((array) $response->getData(true), 'data')
        );
    }

    public function testOptionalIncludedReservations(): void
    {
        // Given
        $space = Space::factory()
            ->has(Reservation::factory()
                ->for(User::factory(), 'createdByUser')
                ->count(3)
            )
            ->create();
        $spaceResource = new SpaceResource($space);
        $request = Mockery::mock(Request::class);

        // When
        $response = $spaceResource->toArray($request);

        // Then
        $this->assertArrayHasKey('reservations', $response);
    }
}
