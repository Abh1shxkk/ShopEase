<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTrackingEvent extends Model
{
    protected $fillable = ['order_id', 'status', 'location', 'description', 'tracking_number', 'carrier', 'event_time'];

    protected $casts = [
        'event_time' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function addEvent(int $orderId, string $status, array $data = []): self
    {
        return static::create([
            'order_id' => $orderId,
            'status' => $status,
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'tracking_number' => $data['tracking_number'] ?? null,
            'carrier' => $data['carrier'] ?? null,
            'event_time' => $data['event_time'] ?? now(),
        ]);
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'order_placed' => 'Order Placed',
            'payment_confirmed' => 'Payment Confirmed',
            'processing' => 'Processing',
            'packed' => 'Packed',
            'shipped' => 'Shipped',
            'in_transit' => 'In Transit',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'returned' => 'Returned',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusIcon(): string
    {
        return match($this->status) {
            'order_placed' => 'clipboard-list',
            'payment_confirmed' => 'credit-card',
            'processing' => 'cog',
            'packed' => 'cube',
            'shipped' => 'truck',
            'in_transit' => 'map',
            'out_for_delivery' => 'map-pin',
            'delivered' => 'check-circle',
            'cancelled' => 'x-circle',
            'returned' => 'arrow-left',
            default => 'info',
        };
    }
}
