<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Successful registration test.
     */
    public function test_new_users_can_register(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('data.name', $user->name)
                    ->where('data.email', $user->email)
                    ->missing('data.password')
                    ->hasAll('meta.token', 'meta.token_type', 'meta.ttl')
                    ->etc()
            );

        $this->assertAuthenticated();
    }

    /**
     * Validation error test.
     */
    public function test_register_validation_error(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'pass',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('message')
                    ->etc()
            );

        $this->assertGuest();
    }

    /**
     * Successful login test.
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('data.name', $user->name)
                    ->where('data.email', $user->email)
                    ->missing('data.password')
                    ->hasAll('meta.token', 'meta.token_type', 'meta.ttl')
                    ->etc()
            );

        $this->assertAuthenticated();
    }

    /**
     * Validation error test.
     */
    public function test_login_validation_error(): void
    {
        $user = User::factory()->create();

        $wrongPasswordResponse = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $wrongPasswordResponse
            ->assertStatus(401)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('message')
                    ->etc()
            );

        $this->assertGuest();

        $wrongEmailResponse = $this->postJson(route('auth.login'), [
            'email' => 'wrong-email',
            'password' => 'password',
        ]);

        $wrongEmailResponse
            ->assertStatus(422)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('message')
                    ->etc()
            );

        $this->assertGuest();
    }

    /**
     * Successful logout test.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->make();

        $registerResponse = $this->postJson(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $registerResponse->json('meta.token');

        $this->assertAuthenticated();

        $logoutResponse = $this->postJson(route('auth.logout'), headers: [
            'Authorization' => "Bearer {$token}",
        ]);

        $logoutResponse->assertNoContent();
        $logoutResponse->assertStatus(204);
    }
}
