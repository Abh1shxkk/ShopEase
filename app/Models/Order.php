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
        'points_redeemed', 'points_earned',
        'tracking_number', 'carrier', 'shipped_at', 'delivered_at', 'estimated_delivery'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(OrderTrackingEvent::class)->orderByDesc('event_time');
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function addTrackingEvent(string $status, array $data = []): OrderTrackingEvent
    {
        return OrderTrackingEvent::addEvent($this->id, $status, $data);
    }

    public function getLatestTrackingEvent(): ?OrderTrackingEvent
    {
        return $this->trackingEvents()->first();
    }

    public function getTrackingUrl(): ?string
    {
        if (!$this->tracking_number || !$this->carrier) return null;
        
        return match(strtolower($this->carrier)) {
            'fedex' => "https://www.fedex.com/fedextrack/?trknbr={$this->tracking_number}",
            'ups' => "https://www.ups.com/track?tracknum={$this->tracking_number}",
            'usps' => "https://tools.usps.com/go/TrackConfirmAction?tLabels={$this->tracking_number}",
            'dhl' => "https://www.dhl.com/en/express/tracking.html?AWB={$this->tracking_number}",
            'bluedart' => "https://www.bluedart.com/tracking/{$this->tracking_number}",
            'delhivery' => "https://www.delhivery.com/track/package/{$this->tracking_number}",
            default => null,
        };
    }
}
