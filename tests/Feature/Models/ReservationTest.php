<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\AuthorizedUser;
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
            ->for(AuthorizedUser::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        // Then
        self::assertInstanceOf(AuthorizedUser::class, $reservation->createdByUser);
    }

    public function testItCanHaveReservationParticipants(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(AuthorizedUser::factory(), 'createdByUser')
            ->for(Space::factory())
            ->has(AuthorizedUser::factory()->count(3), 'reservationParticipants')
            ->create();

        // Then
        self::assertEquals(3, $reservation->reservationParticipants()->count());
        self::assertInstanceOf(AuthorizedUser::class, $reservation->reservationParticipants()->first());
    }

    public function testItCanBeMadeForARoom(): void
    {
        // Given
        $reservation = Reservation::factory()
            ->for(AuthorizedUser::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        // Then
        self::assertInstanceOf(Space::class, $reservation->space);
    }
}
