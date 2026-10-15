<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($title),
            'icon' => fake()->randomElement(array_keys(Program::ICONS)),
            'title' => ['en' => Str::title($title), 'bn' => 'বাংলা '.$title],
            'summary' => ['en' => fake()->sentence(), 'bn' => null],
            'description' => ['en' => fake()->paragraph(), 'bn' => null],
            'overview' => null,
            'image' => null,
            'focus_areas' => [
                ['title' => ['en' => fake()->words(3, true), 'bn' => null], 'text' => ['en' => fake()->sentence(), 'bn' => null]],
            ],
            'wings' => null,
            'sort_order' => fake()->numberBetween(1, 10),
            'is_published' => true,
        ];
    }
}
