<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'discount', 'coupon_id', 'coupon_code',
        'shipping', 'tax', 'total',
        'status', 'payment_status', 'payment_method',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'paid_at',
        'shipping_name', 'shipping_email', 'shipping_phone',
        'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip', 'notes',
        'points_redeemed', 'points_earned'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }
}
