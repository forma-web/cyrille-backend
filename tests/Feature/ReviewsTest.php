<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReviewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * View all reviews.
     */
    public function test_view_all_reviews(): void
    {
        $book = Book::factory()
            ->has(Review::factory()->count(5))
            ->create();

        $response = $this->getJson(route('books.reviews.index', $book->id));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'rating',
                        'comment',
                        'user' => [
                            'id',
                            'name',
                            'avatar',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * User can send a review.
     */
    public function test_user_can_send_a_review(): void
    {
        $book = Book::factory()->create();

        $this->actingAs(User::factory()->create());

        $response = $this->postJson(route('books.reviews.store', $book->id), [
            'rating' => 5,
            'comment' => 'Test comment',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('data.rating', 5)
                    ->where('data.comment', 'Test comment')
                    ->etc()
            );
    }

    /**
     * User can send a review only with rating.
     */
    public function test_user_can_send_a_review_only_with_rating(): void
    {
        $book = Book::factory()->create();

        $this->actingAs(User::factory()->create());

        $response = $this->postJson(route('books.reviews.store', $book->id), [
            'rating' => 5,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('data.rating', 5)
                    ->where('data.comment', null)
                    ->etc()
            );
    }

    /**
     * Unauthenticated user can't send a review.
     */
    public function test_unauthenticated_user_cant_send_a_review(): void
    {
        $book = Book::factory()->create();

        $response = $this->postJson(route('books.reviews.store', $book->id), [
            'rating' => 5,
            'comment' => 'Test comment',
        ]);

        $this->assertGuest();
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
