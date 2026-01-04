<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_order_amount', 'max_discount', 'usage_limit',
        'usage_limit_per_user', 'used_count', 'starts_at',
        'expires_at', 'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function canBeUsedByUser(int $userId): bool
    {
        if (!$this->isValid()) return false;
        
        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        return $userUsageCount < $this->usage_limit_per_user;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order_amount) return 0;

        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            $discount = min($this->value, $subtotal);
        }

        return round($discount, 2);
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'inactive';
        if ($this->starts_at && $this->starts_at->isFuture()) return 'scheduled';
        if ($this->expires_at && $this->expires_at->isPast()) return 'expired';
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return 'exhausted';
        return 'active';
    }

    public function getFormattedValueAttribute(): string
    {
        return $this->type === 'percentage' 
            ? $this->value . '%' 
            : '₹' . number_format($this->value, 0);
    }
}
