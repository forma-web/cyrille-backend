<?php

namespace Database\Factories;

use App\Models\Book;
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
        $content = fake()->randomHtml();
        $content_length = strlen(strip_tags($content));

        return [
            'book_id' => Book::factory(),
            'order' => fake()->unique()->numberBetween(1, 100),
            'name' => fake()->sentence(),
            'content' => $content,
            'content_length' => $content_length,
            'language' => 'en',
        ];
    }
}
