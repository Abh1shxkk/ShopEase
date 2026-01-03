<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id', 'type', 'label', 'last_four', 'card_brand',
        'upi_id', 'bank_name', 'is_default', 'expires_at'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return match($this->type) {
            'card' => "{$this->card_brand} •••• {$this->last_four}",
            'upi' => $this->upi_id,
            'netbanking' => $this->bank_name,
            'wallet' => $this->label ?? 'Wallet',
            default => $this->label ?? 'Payment Method'
        };
    }
}
