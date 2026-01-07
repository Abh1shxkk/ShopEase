<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'size',
        'color',
        'color_code',
        'material',
        'price',
        'discount_price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    public function getEffectiveDiscountPriceAttribute(): ?float
    {
        if ($this->discount_price !== null) {
            return $this->discount_price;
        }
        return $this->product->discount_price;
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->effective_discount_price ?? $this->effective_price;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }
        return $this->product->image_url;
    }

    public function getVariantNameAttribute(): string
    {
        $parts = [];
        if ($this->size) $parts[] = $this->size;
        if ($this->color) $parts[] = $this->color;
        if ($this->material) $parts[] = $this->material;
        return implode(' / ', $parts) ?: 'Default';
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= ($this->product->low_stock_threshold ?? 5);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public static function generateSku(Product $product, ?string $size, ?string $color, ?string $material): string
    {
        $parts = [
            strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $product->name), 0, 4)),
            $product->id,
        ];
        
        if ($size) $parts[] = strtoupper(substr($size, 0, 2));
        if ($color) $parts[] = strtoupper(substr($color, 0, 3));
        if ($material) $parts[] = strtoupper(substr($material, 0, 3));
        
        $sku = implode('-', $parts);
        
        // Ensure uniqueness
        $count = self::where('sku', 'like', $sku . '%')->count();
        if ($count > 0) {
            $sku .= '-' . ($count + 1);
        }
        
        return $sku;
    }
}
