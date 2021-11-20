<?php

namespace Database\Factories;

use App\Models\Enums\ReparationRequestPriority;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReparationRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
            'priority' => ReparationRequestPriority::ALL_PRIORITIES[$this->faker->numberBetween(0, count(ReparationRequestPriority::ALL_PRIORITIES) - 1)],
        ];
    }
}
