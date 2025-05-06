<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $this->user()->id,
            'phone_number' => 'string|max:15|nullable',
            'first_name' => 'string|max:255|nullable',
            'last_name' => 'string|max:255|nullable',
            'bio' => 'string|nullable',
            'phone' => 'string|max:15|nullable',
            'address' => 'string|nullable',
        ];
    }
}
