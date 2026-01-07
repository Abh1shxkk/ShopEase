<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar',
        'date_of_birth', 'gender', 'last_login_at',
        'email_notifications', 'sms_notifications', 'marketing_emails', 'dark_mode',
        'google_id', 'facebook_id', 'social_avatar', 'email_verified_at',
        'is_member', 'membership_expires_at',
        'login_otp', 'login_otp_expires_at', 'hide_membership_popup',
        'referral_code', 'referred_by', 'reward_points', 'total_earned_points', 'total_redeemed_points'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
            'dark_mode' => 'boolean',
            'is_member' => 'boolean',
            'membership_expires_at' => 'datetime',
            'login_otp_expires_at' => 'datetime',
            'hide_membership_popup' => 'boolean',
            'reward_points' => 'decimal:2',
            'total_earned_points' => 'decimal:2',
            'total_redeemed_points' => 'decimal:2',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Membership relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)
                    ->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->latest();
    }

    public function subscriptionPayments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    // Membership methods
    public function isMember(): bool
    {
        return $this->is_member && 
               $this->membership_expires_at && 
               $this->membership_expires_at > now();
    }

    public function hasActiveMembership(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function getCurrentPlan(): ?MembershipPlan
    {
        $subscription = $this->activeSubscription;
        return $subscription ? $subscription->plan : null;
    }

    public function getMembershipBenefits(): array
    {
        $plan = $this->getCurrentPlan();
        if (!$plan) return [];

        return [
            'free_shipping' => $plan->free_shipping,
            'discount_percentage' => $plan->discount_percentage,
            'early_access_days' => $plan->early_access_days,
            'priority_support' => $plan->priority_support,
            'exclusive_products' => $plan->exclusive_products,
        ];
    }

    public function getMemberDiscount(): float
    {
        $plan = $this->getCurrentPlan();
        return $plan ? (float) $plan->discount_percentage : 0;
    }

    public function hasFreeShipping(): bool
    {
        $plan = $this->getCurrentPlan();
        return $plan ? $plan->free_shipping : false;
    }

    public function getEarlyAccessDays(): int
    {
        $plan = $this->getCurrentPlan();
        return $plan ? $plan->early_access_days : 0;
    }

    public function canAccessEarlySale(EarlyAccessSale $sale): bool
    {
        if ($sale->isAccessibleByPublic()) return true;
        if (!$this->isMember()) return false;
        return $sale->isAccessibleByMembers();
    }

    public function updateMembershipStatus(): void
    {
        $activeSubscription = $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        $this->update([
            'is_member' => (bool) $activeSubscription,
            'membership_expires_at' => $activeSubscription?->ends_at,
        ]);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function defaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function defaultPaymentMethod()
    {
        return $this->paymentMethods()->where('is_default', true)->first();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        if ($this->social_avatar) {
            return $this->social_avatar;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff';
    }

    // Referral relationships
    public function referrer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function rewardTransactions(): HasMany
    {
        return $this->hasMany(RewardTransaction::class);
    }
}
