<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ['en' => fake()->name(), 'bn' => null],
            'role' => ['en' => fake()->jobTitle(), 'bn' => null],
            'bio' => ['en' => fake()->sentence(), 'bn' => null],
            'photo' => null,
            'sort_order' => fake()->numberBetween(1, 10),
            'is_published' => true,
        ];
    }
}
