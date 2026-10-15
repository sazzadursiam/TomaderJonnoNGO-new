<?php

namespace Database\Factories;

use App\Models\Stat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stat>
 */
class StatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => ['en' => fake()->words(2, true), 'bn' => null],
            'value' => fake()->numberBetween(1, 5000),
            'suffix' => '+',
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
