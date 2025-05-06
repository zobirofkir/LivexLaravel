<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
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
}
