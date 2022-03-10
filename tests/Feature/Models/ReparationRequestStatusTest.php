<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\ReparationRequest;
use App\Models\ReparationRequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReparationRequestStatusTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanBelongToAReparationRequest(): void
    {
        // Given
        $reparationRequestStatus = ReparationRequestStatus::factory()
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create();

        // Then
        self::assertInstanceOf(ReparationRequest::class, $reparationRequestStatus->reparationRequest);
    }
}
