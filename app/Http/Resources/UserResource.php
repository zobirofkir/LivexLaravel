<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'profile_image' => asset('storage/' . $this->profile_image),
            'is_live' => $this->is_live,

            'followers_count' => $this->followers()->count(),
            'following_count' => $this->following()->count(),
            'total_likes' => $this->totalLikes()->count(),
            'total_earnings' => $this->totalEarnings(),
            'comments_count' => $this->comments()->count(),
            'videos' => VideoResource::collection($this->videos), // Use VideoResource for videos
            'live_streams' => LiveStreamResource::collection($this->whenLoaded('liveStreams')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}