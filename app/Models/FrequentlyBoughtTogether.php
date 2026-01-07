<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrequentlyBoughtTogether extends Model
{
    protected $table = 'frequently_bought_together';

    protected $fillable = [
        'product_id', 'related_product_id', 'purchase_count', 'discount_percentage', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function relatedProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'related_product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public static function recordPurchase(array $productIds): void
    {
        if (count($productIds) < 2) return;

        foreach ($productIds as $productId) {
            foreach ($productIds as $relatedId) {
                if ($productId === $relatedId) continue;

                self::updateOrCreate(
                    ['product_id' => $productId, 'related_product_id' => $relatedId],
                    ['purchase_count' => \DB::raw('purchase_count + 1')]
                );
            }
        }
    }
}
