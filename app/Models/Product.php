<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'discount_price',
        'category_old',
        'gender',
        'stock',
        'low_stock_threshold',
        'hide_when_out_of_stock',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'hide_when_out_of_stock' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function stockNotifications(): HasMany
    {
        return $this->hasMany(StockNotification::class);
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('is_approved', true)->latest();
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category ? $this->category->name : ($this->category_old ?? 'Uncategorized');
    }

    public function getImageUrlAttribute(): string
    {
        // Default placeholder image
        $placeholder = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600';
        
        if (!$this->image) {
            return $placeholder;
        }
        
        // If it's already a full URL (external image)
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        
        // Otherwise, it's a local storage path
        return asset('storage/' . $this->image);
    }
}
