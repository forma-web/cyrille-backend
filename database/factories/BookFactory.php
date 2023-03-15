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
            'thumbnail' => json_encode([
                'type' => 'image',
                'url' => 'https://random.imagecdn.app/640/480',
            ]),
            'genre' => fake()->randomElement(['biography', 'poetry', 'drama', 'science', 'history']),
            'release_date' => fake()->dateTimeBetween('-10 years'),
        ];
    }
}
