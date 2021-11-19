<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'starts_at' => $startsAt = $this->faker->dateTimeThisMonth,
            'ends_at' => $this->faker->dateTimeBetween($startsAt, '+2 weeks'),
        ];
    }
}
