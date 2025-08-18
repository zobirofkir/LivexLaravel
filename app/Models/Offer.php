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
        'posted_by',
        'title',
        'image',
        'description',
        'price',
        'price_sale',
        'discount_type',
        'discount_percentage',
        'is_active',
        'enabled',
        'valid_until',
        'view_offer_text',
        'additional_info',
        'force_refresh_at',
        'status_changed_at',
        'activation_type'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'enabled' => 'boolean',
        'valid_until' => 'datetime', // Changed from 'date' to 'datetime'
        'price' => 'decimal:2',
        'price_sale' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'additional_info' => 'array',
        'force_refresh_at' => 'datetime',
        'status_changed_at' => 'datetime',
    ];
    
    /**
     * Boot the model and add event listeners.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($offer) {
            // Automatically calculate price_sale for percentage discounts
            if ($offer->discount_type === 'percentage' && $offer->discount_percentage && $offer->price) {
                $offer->price_sale = round($offer->price * (1 - $offer->discount_percentage / 100), 2);
            }
            
            // Set posted_by to user's name if not provided
            if (!$offer->posted_by && $offer->user) {
                $offer->posted_by = $offer->user->name;
            }
            
            // Track when enabled status changes
            if ($offer->isDirty('enabled')) {
                $offer->status_changed_at = now();
                $offer->force_refresh_at = now();
            }
        });
    }
    
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
                     ->where('enabled', true)
                     ->where(function ($query) {
                         $query->whereNull('valid_until')
                               ->orWhere('valid_until', '>=', now()); // Changed from now()->toDateString()
                     });
    }
    
    /**
     * Scope a query to only include enabled offers.
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
    
    /**
     * Check if the offer is expired.
     */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until < now();
    }
    
    /**
     * Check if the offer is available (active, enabled, and not expired).
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->enabled && !$this->isExpired();
    }
    
    /**
     * Get the final discounted price.
     */
    public function getDiscountedPrice(): float
    {
        // Always return price_sale if it exists, regardless of discount type
        if ($this->price_sale) {
            return (float) $this->price_sale;
        }
        
        return (float) $this->price;
    }
    
    /**
     * Get the discount amount.
     */
    public function getDiscountAmount(): float
    {
        return round($this->price - $this->getDiscountedPrice(), 2);
    }
    
    /**
     * Get the display name for who posted this offer.
     */
    public function getPostedByName(): string
    {
        return $this->posted_by ?: $this->user->name;
    }
    
    /**
     * Force refresh the offer for frontend applications.
     */
    public function forceRefresh(): void
    {
        $this->update(['force_refresh_at' => now()]);
    }
    
    /**
     * Enable the offer.
     */
    public function enable(): void
    {
        $this->update([
            'enabled' => true,
            'status_changed_at' => now(),
            'force_refresh_at' => now()
        ]);
    }
    
    /**
     * Disable the offer.
     */
    public function disable(): void
    {
        $this->update([
            'enabled' => false,
            'status_changed_at' => now(),
            'force_refresh_at' => now()
        ]);
    }
}