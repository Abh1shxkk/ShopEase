<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ProductBundle extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'discount_type', 'discount_value',
        'original_price', 'bundle_price', 'is_active', 'starts_at', 'ends_at', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'discount_value' => 'decimal:2',
        'original_price' => 'decimal:2',
        'bundle_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($bundle) {
            if (empty($bundle->slug)) {
                $bundle->slug = Str::slug($bundle->name);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class, 'bundle_id')->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bundle_items', 'bundle_id', 'product_id')
            ->withPivot('quantity', 'sort_order')
            ->orderBy('bundle_items.sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function calculatePrices(): void
    {
        $originalPrice = $this->items->sum(fn($item) => $item->product->price * $item->quantity);
        $this->original_price = $originalPrice;

        if ($this->discount_type === 'percentage') {
            $this->bundle_price = $originalPrice * (1 - $this->discount_value / 100);
        } else {
            $this->bundle_price = max(0, $originalPrice - $this->discount_value);
        }
    }

    public function getSavingsAttribute(): float
    {
        return $this->original_price - $this->bundle_price;
    }

    public function getSavingsPercentageAttribute(): float
    {
        if ($this->original_price <= 0) return 0;
        return round(($this->savings / $this->original_price) * 100, 1);
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->ends_at && $this->ends_at->isPast()) return false;
        
        // Check if all products are in stock
        foreach ($this->items as $item) {
            if ($item->product->stock < $item->quantity) return false;
        }
        return true;
    }
}
