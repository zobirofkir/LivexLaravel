<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'source', 'notes', 'platform_fee_percentage'];

    protected $casts = [
        'amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include earnings from a specific source.
     */
    public function scopeFromSource($query, string $source)
    {
        return $query->where('source', $source);
    }
    
    /**
     * Scope a query to only include earnings within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
    
    /**
     * Get the platform fee amount.
     */
    public function getPlatformFeeAmountAttribute(): float
    {
        return $this->amount * ($this->platform_fee_percentage / 100);
    }
    
    /**
     * Get the net amount after platform fee.
     */
    public function getNetAmountAttribute(): float
    {
        return $this->amount - $this->platform_fee_amount;
    }
}