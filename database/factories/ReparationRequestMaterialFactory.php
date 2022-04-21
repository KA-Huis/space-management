<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReparationRequestMaterialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'         => $this->faker->name,
            'is_mandatory' => $this->faker->boolean,
        ];
    }
}
