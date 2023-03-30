<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Database\Seeders\ChapterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ChaptersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated user can view chapters
     *
     * @return void
     */
    public function test_user_can_view_chapters(): void
    {
        $book = Book::factory()
            ->has(Chapter::factory()->count(3))
            ->create();

        $this->actingAs(User::factory()->create());

        $response = $this->getJson(route('books.chapters.index', $book->id));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'order',
                        'name',
                    ]
                ]
            ]);
    }

    /**
     * Test that authenticated user can view chapter
     *
     * @return void
     */
    public function test_user_can_view_chapter(): void
    {
        $book = Book::factory()
            ->has(Chapter::factory()->count(3))
            ->create();

        $this->actingAs(User::factory()->create());

        $response = $this->getJson(route('books.chapters.show', [$book->id, $book->chapters->first()->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'order',
                    'name',
                    'content',
                    'content_length',
                    'language',
                ]
            ]);
    }

    /**
     * Test that unauthenticated user can't view chapters
     *
     * @return void
     */
    public function test_unauthenticated_user_cant_view_chapters(): void
    {
        $book = Book::factory()
            ->has(Chapter::factory()->count(3))
            ->create();

        $response = $this->getJson(route('books.chapters.index', $book->id));

        $this->assertGuest();
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    /**
     * Test that unauthenticated user can't view chapter
     *
     * @return void
     */
    public function test_unauthenticated_user_cant_view_chapter(): void
    {
        $book = Book::factory()
            ->has(Chapter::factory()->count(3))
            ->create();

        $response = $this->getJson(route('books.chapters.show', [$book->id, $book->chapters->first()->id]));

        $this->assertGuest();
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
