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
        $castedSpace = $space->toArray();

        self::assertEquals(
            [
                'id'                      => Arr::get($castedSpace, 'id'),
                'name'                    => Arr::get($castedSpace, 'name'),
                'created_at'              => Arr::get($castedSpace, 'created_at'),
                'updated_at'              => Arr::get($castedSpace, 'updated_at'),
                'deleted_at'              => Arr::get($castedSpace, 'deleted_at'),
                'is_open_for_reservations' => Arr::get($castedSpace, 'is_open_for_reservations'),
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
