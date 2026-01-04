<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => 'Get 10% off on your first order',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 500,
                'max_discount' => 200,
                'usage_limit' => null,
                'usage_limit_per_user' => 1,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'FLAT100',
                'name' => 'Flat ₹100 Off',
                'description' => 'Get flat ₹100 off on orders above ₹999',
                'type' => 'fixed',
                'value' => 100,
                'min_order_amount' => 999,
                'max_discount' => null,
                'usage_limit' => 500,
                'usage_limit_per_user' => 2,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE20',
                'name' => '20% Off Sale',
                'description' => 'Save 20% on all products',
                'type' => 'percentage',
                'value' => 20,
                'min_order_amount' => 1500,
                'max_discount' => 500,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'expires_at' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Get ₹49 off (free shipping)',
                'type' => 'fixed',
                'value' => 49,
                'min_order_amount' => 299,
                'max_discount' => null,
                'usage_limit' => null,
                'usage_limit_per_user' => 5,
                'expires_at' => null,
                'is_active' => true,
            ],
            [
                'code' => 'MEGA50',
                'name' => 'Mega Sale 50% Off',
                'description' => 'Massive 50% discount',
                'type' => 'percentage',
                'value' => 50,
                'min_order_amount' => 3000,
                'max_discount' => 1000,
                'usage_limit' => 50,
                'usage_limit_per_user' => 1,
                'starts_at' => now()->addDays(7),
                'expires_at' => now()->addDays(14),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
