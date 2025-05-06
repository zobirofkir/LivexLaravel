<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhoneAuthRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test sending OTP.
     */
    public function test_send_otp(): void
    {
        $payload = [
            'phone_number' => '+212619920942',
        ];

        $response = $this->postJson('/api/auth/phone/send-otp', $payload);

        $response->assertStatus(200);
    }

}
