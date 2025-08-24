<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coin extends Model
{
    protected $fillable = [
        "user_id",
        "price",
        "old_price",
        "is_best_offer",
    ];

    /**
     * Create Relationship with User Model
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
