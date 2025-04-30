<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class FirebaseAuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'email' => $this->email,
            'name' => $this->name,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 