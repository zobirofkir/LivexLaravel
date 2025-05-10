<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|string|max:255",
            "video_url" => "required|file|mimetypes:video/mp4|max:51200",
            "thumbnail" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "description" => "nullable|string|max:1000",
            "duration" => "nullable|integer|min:0",
            "views" => "nullable|integer|min:0",
        ];
    }
}
