<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order' => fake()->unique()->numberBetween(1, 100),
            'name' => fake()->sentence(),
            'content' => fake()->paragraphs(10, true),
            'language' => 'en',
        ];
    }
}
