<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ReviewSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * View all reviews.
     */
    public function test_view_all_reviews(): void
    {
        $this->seed(ReviewSeeder::class);

        $response = $this->getJson(route('books.reviews.index', 30));

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
        $this->seed(ReviewSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route('books.reviews.store', 50), [
            'rating' => 5,
            'comment' => 'Test comment',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('rating', 5)
                    ->where('comment', 'Test comment')
                    ->etc()
            );
    }

    /**
     * Unauthenticated user can't send a review.
     */
    public function test_unauthenticated_user_cant_send_a_review(): void
    {
        $this->seed(ReviewSeeder::class);

        $response = $this->postJson(route('books.reviews.store', 70), [
            'rating' => 5,
            'comment' => 'Test comment',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('message')
                    ->etc()
            );
    }
}
