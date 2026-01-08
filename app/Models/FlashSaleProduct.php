<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSaleProduct extends Model
{
    protected $fillable = [
        'flash_sale_id', 'product_id', 'sale_price',
        'quantity_limit', 'quantity_sold', 'per_user_limit'
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
    ];

    public function flashSale(): BelongsTo
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if stock is available for this flash sale item
     */
    public function hasStock(): bool
    {
        if ($this->quantity_limit === null) {
            return $this->product->stock > 0;
        }
        return $this->quantity_sold < $this->quantity_limit && $this->product->stock > 0;
    }

    /**
     * Get remaining quantity
     */
    public function getRemainingQuantityAttribute(): ?int
    {
        if ($this->quantity_limit === null) return null;
        return max(0, $this->quantity_limit - $this->quantity_sold);
    }

    /**
     * Get sold percentage
     */
    public function getSoldPercentageAttribute(): int
    {
        if ($this->quantity_limit === null || $this->quantity_limit === 0) return 0;
        return min(100, (int) (($this->quantity_sold / $this->quantity_limit) * 100));
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): int
    {
        $originalPrice = $this->product->price;
        if ($originalPrice <= 0) return 0;
        return (int) ((($originalPrice - $this->sale_price) / $originalPrice) * 100);
    }

    /**
     * Check if user can purchase
     */
    public function canUserPurchase(int $userId, int $quantity = 1): array
    {
        // Check if sale is live
        if (!$this->flashSale->isLive()) {
            return ['can' => false, 'reason' => 'Sale is not active'];
        }

        // Check stock
        if (!$this->hasStock()) {
            return ['can' => false, 'reason' => 'Out of stock'];
        }

        // Check quantity limit
        if ($this->quantity_limit !== null && ($this->quantity_sold + $quantity) > $this->quantity_limit) {
            return ['can' => false, 'reason' => 'Not enough stock in flash sale'];
        }

        // Check per user limit
        $userPurchased = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $userId))
            ->where('product_id', $this->product_id)
            ->whereHas('order', fn($q) => $q->where('created_at', '>=', $this->flashSale->starts_at))
            ->sum('quantity');

        if (($userPurchased + $quantity) > $this->per_user_limit) {
            return ['can' => false, 'reason' => "Limit {$this->per_user_limit} per customer"];
        }

        return ['can' => true, 'reason' => null];
    }

    /**
     * Increment sold quantity
     */
    public function incrementSold(int $quantity = 1): void
    {
        $this->increment('quantity_sold', $quantity);
    }
}
