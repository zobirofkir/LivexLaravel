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
            'profile' => [
                'first_name' => $this->profile->first_name,
                'last_name' => $this->profile->last_name,
                'bio' => $this->profile->bio,
                'phone' => $this->profile->phone,
                'address' => $this->profile->address,
            ],
            'videos' => VideoResource::collection($this->videos),
            'offers' => OfferResource::collection($this->offers),
            'followers_count' => $this->followers()->count(),
            'following_count' => $this->following()->count(),
            'total_likes' => $this->totalLikes()->count(),
            'total_earnings' => $this->totalEarnings(),
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
