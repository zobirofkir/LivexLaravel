<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EarningResource extends JsonResource
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
            'amount' => $this->amount,
            'source' => $this->source,
            'platform_fee_percentage' => $this->platform_fee_percentage,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}