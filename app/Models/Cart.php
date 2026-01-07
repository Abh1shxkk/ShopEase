<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'variant_id', 'quantity'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getSubtotalAttribute(): float
    {
        if (!$this->product) {
            return 0;
        }
        
        // Use variant price if available
        if ($this->variant) {
            $price = $this->variant->final_price;
        } else {
            $price = $this->product->discount_price ?? $this->product->price;
        }
        
        return $price * $this->quantity;
    }

    public function getAvailableStockAttribute(): int
    {
        if ($this->variant) {
            return $this->variant->stock;
        }
        return $this->product->stock ?? 0;
    }

    public function getItemNameAttribute(): string
    {
        $name = $this->product->name ?? 'Unknown Product';
        if ($this->variant) {
            $name .= ' - ' . $this->variant->variant_name;
        }
        return $name;
    }

    public function getItemImageAttribute(): string
    {
        if ($this->variant && $this->variant->image) {
            return $this->variant->image_url;
        }
        return $this->product->image_url ?? '';
    }
}
