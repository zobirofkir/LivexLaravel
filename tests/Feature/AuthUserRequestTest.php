<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthUserRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test updating the authenticated user's information.
     */
    public function test_update_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson(route('auth.user.update'), $payload); // Use route helper

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'name' => 'Updated Name',
                         'email' => 'updated@example.com',
                     ],
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test retrieving the authenticated user's information.
     */
    public function test_show_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->getJson(route('auth.user.current')); // Use route helper

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $user->id,
                         'name' => $user->name,
                         'email' => $user->email,
                     ],
                 ]);
    }
}
