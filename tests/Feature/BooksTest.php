<?php

namespace Tests\Feature;

use Database\Seeders\BookSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that books can be listed.
     */
    public function test_books_can_be_listed()
    {
        $this->seed(BookSeeder::class);

        $response = $this->getJson(route('books.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'language',
                        'thumbnail_image',
                        'thumbnail_component',
                        'genre',
                        'pages',
                        'published',
                        'release_date',
                        'authors' => [
                            '*' => [
                                'id',
                                'name',
                                'avatar',
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test that a book can be shown.
     */
    public function test_a_book_can_be_shown()
    {
        $this->seed(BookSeeder::class);

        $response = $this->getJson(route('books.show', 11));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'language',
                    'thumbnail_image',
                    'thumbnail_component',
                    'genre',
                    'pages',
                    'published',
                    'release_date',
                    'reviews_avg_rating',
                    'reviews_count',
                    'authors' => [
                        '*' => [
                            'id',
                            'name',
                            'avatar',
                        ],
                    ],
                    'artists' => [
                        '*' => [
                            'id',
                            'name',
                            'avatar',
                            'project' => [
                                'role',
                                'notes',
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test that a book with an invalid ID cannot be shown.
     */
    public function test_a_book_with_an_invalid_id_cannot_be_shown()
    {
        $response = $this->getJson(route('books.show', 999));

        $response->assertStatus(404);
    }
}
