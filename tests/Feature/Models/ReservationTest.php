<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanBeCreatedByUser(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        // Then
        self::assertInstanceOf(User::class, $reservation->createdByUser);
    }

    public function testItCanHaveReservationParticipants(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->has(User::factory()->count(3), 'reservationParticipants')
            ->create();

        // Then
        self::assertEquals(3, $reservation->reservationParticipants()->count());
        self::assertInstanceOf(User::class, $reservation->reservationParticipants()->first());
    }

    public function testItCanBeMadeForARoom(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        // Then
        self::assertInstanceOf(Space::class, $reservation->space);
    }
}
