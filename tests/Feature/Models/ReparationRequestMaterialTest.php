<?php

namespace Tests\Feature\Models;

use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\ReparationRequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReparationRequestMaterialTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_belong_to_a_reparation_request(): void
    {
        // Given
        $reparationRequestMaterial = ReparationRequestMaterial::factory()
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create();

        // Then
        self::assertInstanceOf(ReparationRequest::class, $reparationRequestMaterial->reparationRequest);
    }
}
