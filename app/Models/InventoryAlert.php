<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAlert extends Model
{
    protected $fillable = ['product_id', 'type', 'stock_level', 'email_sent', 'is_read'];

    protected $casts = [
        'email_sent' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'out_of_stock' => 'red',
            'low_stock' => 'amber',
            'restocked' => 'green',
            default => 'slate'
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'out_of_stock' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'low_stock' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'restocked' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }
}
