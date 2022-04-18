<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Resources;

use App\API\V1\Http\Resources\ReparationRequestResource;
use App\Models\ReparationRequest;
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
        self::assertEquals(
            [
                'uuid' => $reparationRequest->uuid,
                'title' => $reparationRequest->title,
                'description' => $reparationRequest->description,
                'priority' => $reparationRequest->priority,
                'created_at' => (string) $reparationRequest->created_at,
                'updated_at' => (string) $reparationRequest->updated_at,
                'deleted_at' => $reparationRequest->deleted_at,
            ],
            collect(Arr::get((array) $response->getData(true), 'data'))->toArray()
        );
    }

    public function testOptionalLoadedReporter(): void
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
}
