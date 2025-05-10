<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "video_url" => asset('storage/' . $this->video_url),
            "thumbnail" => asset('storage/' . $this->thumbnail),
            "description" => $this->description,
            "duration" => $this->duration,
            "views" => $this->views,
        ];
    }
}
