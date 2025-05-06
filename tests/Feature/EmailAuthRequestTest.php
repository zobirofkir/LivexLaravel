<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailAuthRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test sending OTP.
     */
    public function test_send_otp(): void
    {
        $payload = [
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->postJson('/api/auth/email/send-otp', $payload);

        $response->assertStatus(200);
    }

    /**
     * Test verifying OTP.
     */
    // public function test_verify_otp(): void
    // {
    //     $payload = [
    //         'email' => $this->faker->safeEmail,
    //         'otp' => '123456',
    //     ];

    //     $response = $this->postJson('/api/auth/verify-otp', $payload);

    //     $response->assertStatus(200)
    //              ->assertJsonStructure(['data' => ['message']]);
    // }

    /**
     * Test logging in.
     */
    // public function test_login(): void
    // {
    //     $payload = [
    //         'email' => $this->faker->safeEmail,
    //         'password' => 'password',
    //     ];

    //     $response = $this->postJson('/api/auth/login', $payload);

    //     $response->assertStatus(200)
    //              ->assertJsonStructure(['data' => ['token']]);
    // }
}
