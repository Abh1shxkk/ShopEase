<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbandonedCartReminder extends Model
{
    protected $fillable = [
        'abandoned_cart_id', 'reminder_number', 'channel',
        'status', 'sent_at', 'opened_at', 'clicked_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    public function abandonedCart(): BelongsTo
    {
        return $this->belongsTo(AbandonedCart::class);
    }

    public function markOpened(): void
    {
        if (!$this->opened_at) {
            $this->update(['status' => 'opened', 'opened_at' => now()]);
        }
    }

    public function markClicked(): void
    {
        $this->update(['status' => 'clicked', 'clicked_at' => now()]);
    }
}
