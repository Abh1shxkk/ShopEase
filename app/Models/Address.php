<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'name', 'phone', 'address_line_1', 'address_line_2',
        'city', 'state', 'pincode', 'country', 'landmark', 'is_default', 'type'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->landmark ? "Near {$this->landmark}" : null,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country
        ]);
        return implode(', ', $parts);
    }
}
