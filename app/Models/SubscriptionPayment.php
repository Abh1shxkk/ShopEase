<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_subscription_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'razorpay_payment_id',
        'razorpay_order_id',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted(string $paymentId = null): void
    {
        $this->update([
            'status' => 'completed',
            'razorpay_payment_id' => $paymentId,
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
