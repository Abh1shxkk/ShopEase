<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class FlashSale extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'banner_image',
        'starts_at', 'ends_at', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sale) {
            if (empty($sale->slug)) {
                $sale->slug = Str::slug($sale->name);
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'flash_sale_products')
            ->withPivot(['sale_price', 'quantity_limit', 'quantity_sold', 'per_user_limit'])
            ->withTimestamps();
    }

    public function flashSaleProducts(): HasMany
    {
        return $this->hasMany(FlashSaleProduct::class);
    }

    /**
     * Check if sale is currently active
     */
    public function isLive(): bool
    {
        return $this->is_active 
            && $this->starts_at <= now() 
            && $this->ends_at > now();
    }

    /**
     * Check if sale is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->is_active && $this->starts_at > now();
    }

    /**
     * Check if sale has ended
     */
    public function hasEnded(): bool
    {
        return $this->ends_at <= now();
    }

    /**
     * Get time remaining in seconds
     */
    public function getTimeRemainingAttribute(): int
    {
        if ($this->hasEnded()) return 0;
        return max(0, $this->ends_at->diffInSeconds(now()));
    }

    /**
     * Get time until start in seconds
     */
    public function getTimeUntilStartAttribute(): int
    {
        if ($this->starts_at <= now()) return 0;
        return $this->starts_at->diffInSeconds(now());
    }

    /**
     * Scope for active sales
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for live sales (currently running)
     */
    public function scopeLive($query)
    {
        return $query->active()
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now());
    }

    /**
     * Scope for upcoming sales
     */
    public function scopeUpcoming($query)
    {
        return $query->active()->where('starts_at', '>', now());
    }

    /**
     * Get banner URL
     */
    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }
}
