<?php

namespace Tests\Feature;

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
        $requestUser = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('auth.register'), $requestUser);

        $response
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('data.name', $requestUser['name'])
                    ->where('data.email', $requestUser['email'])
                    ->missing('data.password')
                    ->hasAll('meta.token', 'meta.token_type', 'meta.ttl')
                    ->etc()
            );
    }

    /**
     * Validation error test.
     */
    public function test_validation_error(): void
    {
        $requestUser = [
            'name' => 'John Doe',
            'email' => 'john',
            'password' => 'password',
        ];

        $this->postJson(route('auth.register'), $requestUser);

        $response = $this->postJson(route('auth.register'), $requestUser);

        $response
            ->assertStatus(422)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('errors.email')
                    ->etc()
            );
    }

    public function test_user_can_login(): void
    {
        $requestUser = [
            'name' => 'Alan Turing',
            'email' => 'turing@gmail.com',
            'password' => 'password',
        ];

        $this->postJson(route('auth.register'), $requestUser);

        $this->postJson(route('auth.login'), [
            'email' => $requestUser['email'],
            'password' => $requestUser['password'],
        ]);

        $this->assertAuthenticated();
    }
}
