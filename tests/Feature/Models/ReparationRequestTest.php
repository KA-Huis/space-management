<?php

namespace Tests\Feature\Models;

use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\ReparationRequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReparationRequestTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_be_reported_by_a_user(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();

        // Then
        self::assertInstanceOf(User::class, $reparationRequest->reporter);
    }

    public function test_it_can_have_a_current_status(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestStatus::factory()->count(5), 'statuses')
            ->create();

        // Then
        self::assertInstanceOf(ReparationRequestStatus::class, $reparationRequest->currentStatus);
        self::assertEquals($reparationRequest->statuses()->latest()->first()->id, $reparationRequest->currentStatus->id);
    }

    public function test_it_can_have_have_many_statuses(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestStatus::factory()->count(5), 'statuses')
            ->create();

        // Then
        self::assertEquals(5, $reparationRequest->statuses()->count());
    }

    public function test_it_can_have_have_many_materials(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestMaterial::factory()->count(5), 'materials')
            ->create();

        // Then
        self::assertEquals(5, $reparationRequest->materials()->count());
    }
}