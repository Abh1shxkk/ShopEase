<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AbandonedCart extends Model
{
    protected $fillable = [
        'user_id', 'cart_total', 'items_count', 'cart_snapshot',
        'status', 'reminder_count', 'last_reminder_at',
        'recovered_at', 'recovered_order_id', 'recovery_token'
    ];

    protected $casts = [
        'cart_snapshot' => 'array',
        'cart_total' => 'decimal:2',
        'last_reminder_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($cart) {
            $cart->recovery_token = Str::random(64);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recoveredOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'recovered_order_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(AbandonedCartReminder::class);
    }

    /**
     * Create or update abandoned cart for user
     */
    public static function captureForUser(User $user): ?self
    {
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            // Remove any existing abandoned cart if cart is now empty
            static::where('user_id', $user->id)
                ->where('status', 'pending')
                ->delete();
            return null;
        }

        $cartTotal = $cartItems->sum('subtotal');
        $snapshot = $cartItems->map(fn($item) => [
            'product_id' => $item->product_id,
            'product_name' => $item->product->name,
            'product_image' => $item->product->image_url,
            'price' => $item->product->discount_price ?? $item->product->price,
            'quantity' => $item->quantity,
            'subtotal' => $item->subtotal,
        ])->toArray();

        return static::updateOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            [
                'cart_total' => $cartTotal,
                'items_count' => $cartItems->count(),
                'cart_snapshot' => $snapshot,
            ]
        );
    }

    /**
     * Mark as recovered
     */
    public function markRecovered(?int $orderId = null): void
    {
        $this->update([
            'status' => 'recovered',
            'recovered_at' => now(),
            'recovered_order_id' => $orderId,
        ]);
    }

    /**
     * Mark as expired
     */
    public function markExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Check if can send reminder
     */
    public function canSendReminder(int $maxReminders = 3, int $hoursBetween = 24): bool
    {
        if ($this->status !== 'pending') return false;
        if ($this->reminder_count >= $maxReminders) return false;
        
        if ($this->last_reminder_at) {
            return $this->last_reminder_at->addHours($hoursBetween) <= now();
        }
        
        // First reminder after 1 hour of cart abandonment
        return $this->updated_at->addHour() <= now();
    }

    /**
     * Record reminder sent
     */
    public function recordReminderSent(string $channel = 'email'): AbandonedCartReminder
    {
        $this->increment('reminder_count');
        $this->update([
            'status' => 'reminded',
            'last_reminder_at' => now(),
        ]);

        return $this->reminders()->create([
            'reminder_number' => $this->reminder_count,
            'channel' => $channel,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Get recovery URL
     */
    public function getRecoveryUrlAttribute(): string
    {
        return route('cart.recover', $this->recovery_token);
    }

    /**
     * Scope for pending carts
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for carts needing reminder
     */
    public function scopeNeedsReminder($query, int $maxReminders = 3)
    {
        return $query->where('status', 'pending')
            ->where('reminder_count', '<', $maxReminders)
            ->where(function ($q) {
                $q->whereNull('last_reminder_at')
                    ->where('updated_at', '<=', now()->subHour())
                    ->orWhere('last_reminder_at', '<=', now()->subHours(24));
            });
    }
}
