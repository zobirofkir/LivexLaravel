<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->price,
            'price_sale' => $this->price_sale,
            'discount_type' => $this->discount_type,
            'discount_percentage' => $this->discount_percentage,
            'valid_until' => $this->valid_until,
            'is_active' => $this->is_active,
            'enabled' => $this->enabled,
            'is_available' => $this->isAvailable(),
            'additional_info' => $this->additional_info,
            'force_refresh_at' => $this->force_refresh_at,
            'status_changed_at' => $this->status_changed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'view_offer_text' => $this->view_offer_text ?? 'View Offer',
            'posted_by' => $this->getPostedByName(),
            'discounted_price' => $this->getDiscountedPrice(),
            'discount_amount' => $this->getDiscountAmount(),
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}