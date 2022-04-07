<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\AuthorizedUser;
use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\ReparationRequestStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReparationRequestTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanBeReportedByAUser(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(AuthorizedUser::factory(), 'reporter')
            ->create();

        // Then
        self::assertInstanceOf(AuthorizedUser::class, $reparationRequest->reporter);
    }

    public function testItCanHaveACurrentStatus(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(AuthorizedUser::factory(), 'reporter')
            ->has(ReparationRequestStatus::factory()->count(5), 'statuses')
            ->create();

        // Then
        self::assertInstanceOf(ReparationRequestStatus::class, $reparationRequest->currentStatus);
        self::assertEquals($reparationRequest->statuses()->latest()->first()->id, $reparationRequest->currentStatus->id);
    }

    public function testItCanHaveHaveManyStatuses(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(AuthorizedUser::factory(), 'reporter')
            ->has(ReparationRequestStatus::factory()->count(5), 'statuses')
            ->create();

        // Then
        self::assertEquals(5, $reparationRequest->statuses()->count());
    }

    public function testItCanHaveHaveManyMaterials(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(AuthorizedUser::factory(), 'reporter')
            ->has(ReparationRequestMaterial::factory()->count(5), 'materials')
            ->create();

        // Then
        self::assertEquals(5, $reparationRequest->materials()->count());
    }
}
