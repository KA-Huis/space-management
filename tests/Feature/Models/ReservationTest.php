<?php

namespace Tests\Feature\Models;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_be_created_by_user(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        // Then
        self::assertInstanceOf(User::class, $reservation->createdByUser);
    }

    public function test_it_can_have_reservation_participants(): void
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

    public function test_it_can_be_made_for_a_room(): void
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
