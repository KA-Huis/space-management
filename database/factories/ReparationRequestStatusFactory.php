<?php

namespace Database\Factories;

use App\Models\Enums\ReparationRequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReparationRequestStatusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => ReparationRequestStatus::ALL_STATUSES[$this->faker->numberBetween(0, count(ReparationRequestStatus::ALL_STATUSES) - 1)],
        ];
    }
}
