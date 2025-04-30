<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 6 characters long.',
        ];
    }
} 