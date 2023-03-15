<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraphs(1, true),
            'thumbnail_image' => $this->fakeThumbnail(),
            'thumbnail_component' => fake()->optional()->randomElement(['SherlockAnimation', 'HarryPotterAnimation']),
            'genre' => fake()->randomElement(['biography', 'poetry', 'drama', 'science', 'history']),
            'release_date' => fake()->dateTimeBetween('-10 years'),
        ];
    }

    public function fakeThumbnail(): string
    {
        return 'https://random.imagecdn.app/640/480';
    }
}
