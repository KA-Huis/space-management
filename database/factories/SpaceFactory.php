<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->boolean ? $this->faker->text : null,
            'open_for_reservations' => $this->faker->boolean,
        ];
    }
}
