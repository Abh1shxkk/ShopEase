<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_commission_rate', 'minimum_payout_amount', 'payout_frequency_days',
        'auto_approve_sellers', 'auto_approve_products', 'seller_terms',
    ];

    protected $casts = [
        'default_commission_rate' => 'decimal:2',
        'minimum_payout_amount' => 'decimal:2',
        'auto_approve_sellers' => 'boolean',
        'auto_approve_products' => 'boolean',
    ];

    public static function get(): self
    {
        return self::firstOrCreate([], [
            'default_commission_rate' => 10.00,
            'minimum_payout_amount' => 500.00,
            'payout_frequency_days' => 7,
            'auto_approve_sellers' => false,
            'auto_approve_products' => false,
        ]);
    }
}
