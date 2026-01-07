<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_plan_id',
        'status',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'cancellation_reason',
        'auto_renew',
        'payment_method',
        'razorpay_subscription_id',
        'amount_paid',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
        'amount_paid' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('ends_at', '>', now());
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('status', 'active')
                     ->whereBetween('ends_at', [now(), now()->addDays($days)]);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at > now();
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->ends_at || $this->ends_at < now()) return 0;
        return (int) now()->diffInDays($this->ends_at);
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->days_remaining > 0 && $this->days_remaining <= 7;
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'auto_renew' => false,
        ]);

        // Update user membership status if this was their only active subscription
        $this->user->updateMembershipStatus();
    }

    public function renew(): self
    {
        $newSubscription = self::create([
            'user_id' => $this->user_id,
            'membership_plan_id' => $this->membership_plan_id,
            'status' => 'pending',
            'starts_at' => $this->ends_at,
            'ends_at' => Carbon::parse($this->ends_at)->addDays($this->plan->duration_days),
            'auto_renew' => $this->auto_renew,
            'amount_paid' => $this->plan->price,
        ]);

        return $newSubscription;
    }
}
