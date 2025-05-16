<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'stream_key' => $this->stream_key,
            'title' => $this->title,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'viewer_count' => $this->viewer_count,
            'likes_count' => $this->likes_count
        ];
    }
}
