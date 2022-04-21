<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Resources;

use App\API\V1\Http\Resources\ReparationRequestResource;
use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\ReparationRequestStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery;
use Tests\TestCase;

class ReparationRequestResourceTest extends TestCase
{
    public function testDefaultResponse(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();
        $reparationRequestResource = new ReparationRequestResource($reparationRequest);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reparationRequestResource->toResponse($request);

        // Then
        $castedReparationRequest = $reparationRequest->toArray();

        self::assertEquals(
            [
                'uuid' => Arr::get($castedReparationRequest, 'uuid'),
                'title' => Arr::get($castedReparationRequest, 'title'),
                'description' => Arr::get($castedReparationRequest, 'description'),
                'priority' => Arr::get($castedReparationRequest, 'priority'),
                'created_at' => Arr::get($castedReparationRequest, 'created_at'),
                'updated_at' => Arr::get($castedReparationRequest, 'updated_at'),
                'deleted_at' => Arr::get($castedReparationRequest, 'deleted_at'),
            ],
            Arr::get((array) $response->getData(true), 'data')
        );
    }

    public function testOptionalIncludedReporter(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();
        $reparationRequestResource = new ReparationRequestResource($reparationRequest);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reparationRequestResource->toArray($request);

        // Then
        $this->assertArrayHasKey('reporter', $response);
    }

    public function testOptionalIncludedStatuses(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestStatus::factory(), 'statuses')
            ->create();
        $reparationRequestResource = new ReparationRequestResource($reparationRequest);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reparationRequestResource->toArray($request);

        // Then
        $this->assertArrayHasKey('statuses', $response);
    }

    public function testOptionalIncludedMaterials(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestMaterial::factory(), 'materials')
            ->create();
        $reparationRequestResource = new ReparationRequestResource($reparationRequest);
        $request = Mockery::mock(Request::class);

        // When
        $response = $reparationRequestResource->toArray($request);

        // Then
        $this->assertArrayHasKey('materials', $response);
    }
}
