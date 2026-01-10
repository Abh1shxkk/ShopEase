<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SellerPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'payout_id', 'amount', 'payment_method',
        'status', 'transaction_id', 'notes', 'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payout) {
            if (empty($payout->payout_id)) {
                $payout->payout_id = 'PAY-' . strtoupper(Str::random(12));
            }
        });
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function markAsCompleted(string $transactionId = null): void
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'processed_at' => now(),
        ]);
    }

    public function markAsFailed(string $notes = null): void
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes,
        ]);
        
        // Refund to wallet
        $this->seller->increment('wallet_balance', $this->amount);
        $this->seller->decrement('total_withdrawn', $this->amount);
    }
}
