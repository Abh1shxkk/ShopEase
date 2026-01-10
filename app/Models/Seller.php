<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'store_name', 'store_slug', 'store_description',
        'store_logo', 'store_banner', 'business_name', 'business_email',
        'business_phone', 'business_address', 'gst_number', 'pan_number',
        'bank_name', 'bank_account_number', 'bank_ifsc_code', 'bank_account_holder',
        'commission_rate', 'wallet_balance', 'total_earnings', 'total_withdrawn',
        'status', 'rejection_reason', 'approved_at', 'is_featured',
        'total_products', 'total_orders', 'average_rating',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'approved_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($seller) {
            if (empty($seller->store_slug)) {
                $seller->store_slug = Str::slug($seller->store_name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(SellerEarning::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(SellerPayout::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(SellerReview::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->store_logo) {
            return asset('storage/' . $this->store_logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->store_name) . '&background=6366f1&color=fff&size=200';
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->store_banner) {
            return asset('storage/' . $this->store_banner);
        }
        return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200';
    }

    public function getAvailableBalanceAttribute(): float
    {
        return $this->wallet_balance;
    }

    public function getPendingEarningsAttribute(): float
    {
        return $this->earnings()->where('status', 'pending')->sum('seller_amount');
    }

    public function updateStats(): void
    {
        $this->update([
            'total_products' => $this->products()->count(),
            'total_orders' => $this->earnings()->distinct('order_id')->count('order_id'),
            'average_rating' => $this->reviews()->where('is_approved', true)->avg('rating') ?? 0,
        ]);
    }

    public function addEarning(float $amount): void
    {
        $this->increment('wallet_balance', $amount);
        $this->increment('total_earnings', $amount);
    }

    public function deductBalance(float $amount): bool
    {
        if ($this->wallet_balance < $amount) {
            return false;
        }
        $this->decrement('wallet_balance', $amount);
        $this->increment('total_withdrawn', $amount);
        return true;
    }
}
