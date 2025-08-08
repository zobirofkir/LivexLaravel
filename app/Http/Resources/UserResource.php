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
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'is_live' => $this->is_live,
            
            // Profile fields
            'first_name' => $this->profile?->first_name,
            'last_name' => $this->profile?->last_name,
            'bio' => $this->profile?->bio,
            'phone' => $this->profile?->phone,
            'address' => $this->profile?->address,

            'followers_count' => $this->whenLoaded('followers', function() {
                return $this->followers->count();
            }, $this->followers()->count()),
            'following_count' => $this->whenLoaded('following', function() {
                return $this->following->count();
            }, $this->following()->count()),
            'total_likes' => $this->totalLikes()->count(),
            'total_earnings' => $this->totalEarnings(),
            'comments_count' => $this->whenLoaded('comments', function() {
                return $this->comments->count();
            }, $this->comments()->count()),
            'videos' => VideoResource::collection($this->videos),
            'live_streams' => LiveStreamResource::collection($this->whenLoaded('liveStreams')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}