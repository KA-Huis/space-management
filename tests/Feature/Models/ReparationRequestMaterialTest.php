<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReparationRequestMaterialTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanBelongToAReparationRequest(): void
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
