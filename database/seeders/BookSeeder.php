<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::factory()
            ->count(10)
            ->hasAttached(
                Artist::factory()->count(3),
                fn () => [
                    'role' => fake()->randomElement(['author', 'illustrator']),
                    'notes' => fake()->sentence(),
                ],
            )
            ->has(Chapter::factory()->count(4))
            ->create();
    }
}
