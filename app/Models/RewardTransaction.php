<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RewardTransaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'points', 'balance_after',
        'source', 'sourceable_type', 'sourceable_id', 'description'
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }
}
