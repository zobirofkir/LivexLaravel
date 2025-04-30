<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ];
    }

    public function messages()
    {
        return [
            'otp.size' => 'OTP must be exactly 6 characters long.',
        ];
    }
} 