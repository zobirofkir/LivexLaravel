<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoogleTest extends TestCase
{
    /**
     * Refresh the database
     */
    use RefreshDatabase;

    /**
     * Test Create User
     */
    public function testCreateUser()
    {
        $user = User::factory()->create();
    
        $this->assertDatabaseHas('users', $user->toArray());
    }

    /**
     * Test Get Users
     */
    public function testGetUsers()
    {
        $users = User::factory()->count(5)->create();
        $this->assertCount(5, $users);
        $this->assertDatabaseCount('users', 5);
    }

    /**
     * Test Update User
     */
    public function testUpdateUser()
    {
        $user = User::factory()->create();
        $user->name = 'livex';
        $user->save();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'livex',
        ]);
    }

    /**
     * Test Delete User
     */
    public function testDeleteUser()
    {
        $user = User::factory()->create();
        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
