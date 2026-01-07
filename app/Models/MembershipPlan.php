<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'duration_days',
        'free_shipping',
        'discount_percentage',
        'early_access_days',
        'priority_support',
        'exclusive_products',
        'features',
        'sort_order',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'free_shipping' => 'boolean',
        'priority_support' => 'boolean',
        'exclusive_products' => 'boolean',
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(UserSubscription::class)->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function getMonthlyPriceAttribute()
    {
        return match($this->billing_cycle) {
            'monthly' => $this->price,
            'quarterly' => round($this->price / 3, 2),
            'yearly' => round($this->price / 12, 2),
            default => $this->price,
        };
    }

    public function getSavingsPercentageAttribute()
    {
        if ($this->billing_cycle === 'monthly') return 0;
        
        // Assume monthly plan exists with same features
        $monthlyEquivalent = $this->duration_days / 30 * $this->monthly_price;
        if ($monthlyEquivalent <= 0) return 0;
        
        return round((1 - ($this->price / $monthlyEquivalent)) * 100);
    }

    public function getBillingCycleLabelAttribute()
    {
        return match($this->billing_cycle) {
            'monthly' => 'per month',
            'quarterly' => 'per quarter',
            'yearly' => 'per year',
            default => '',
        };
    }
}
