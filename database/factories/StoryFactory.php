<?php

namespace Database\Factories;

use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Story>
 */
class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'slug' => Str::slug($title),
            'category' => ['en' => fake()->word(), 'bn' => null],
            'title' => ['en' => $title, 'bn' => null],
            'excerpt' => ['en' => fake()->sentence(), 'bn' => null],
            'body' => ['en' => fake()->paragraphs(3, true), 'bn' => null],
            'image' => null,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ];
    }
}
