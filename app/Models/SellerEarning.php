<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'order_id', 'order_item_id', 'order_amount',
        'commission_rate', 'commission_amount', 'seller_amount',
        'status', 'processed_at',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'seller_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function markAsProcessed(): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);
        
        $this->seller->addEarning($this->seller_amount);
    }
}
