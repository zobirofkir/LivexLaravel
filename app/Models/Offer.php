<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'image',
        'description',
        'price',
        'price_sale',
        'is_active',
        'valid_until',
        'additional_info',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'valid_until' => 'date',
        'additional_info' => 'array',
        'price' => 'decimal:2',
        'price_sale' => 'decimal:2',
    ];
    
    /**
     * Get the user that owns the offer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include active offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($query) {
                         $query->whereNull('valid_until')
                               ->orWhere('valid_until', '>=', now()->toDateString());
                     });
    }
}
