<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group Feature
 * @group Models
 */
class SpaceTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanHaveReservations(): void
    {
        // Given
        $space = Space::factory()
            ->has(Reservation::factory()
                ->for(User::factory(), 'createdByUser')
                ->count(3) )
            ->create();

        // Then
        self::assertEquals(3, $space->reservations()->count());
        self::assertInstanceOf(Reservation::class, $space->reservations()->first());
    }
}
