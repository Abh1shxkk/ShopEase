<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for occasional shoppers who want to save on shipping.',
                'price' => 99,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'free_shipping' => true,
                'discount_percentage' => 0,
                'early_access_days' => 0,
                'priority_support' => false,
                'exclusive_products' => false,
                'features' => ['Free shipping on all orders', 'Member-only newsletter'],
                'sort_order' => 1,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Our most popular plan with great savings and early access.',
                'price' => 199,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'free_shipping' => true,
                'discount_percentage' => 10,
                'early_access_days' => 2,
                'priority_support' => true,
                'exclusive_products' => false,
                'features' => ['Free shipping on all orders', '10% off all purchases', '2-day early access to sales', 'Priority customer support'],
                'sort_order' => 2,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Elite',
                'slug' => 'elite',
                'description' => 'The ultimate shopping experience with maximum benefits.',
                'price' => 499,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'free_shipping' => true,
                'discount_percentage' => 15,
                'early_access_days' => 5,
                'priority_support' => true,
                'exclusive_products' => true,
                'features' => ['Free shipping on all orders', '15% off all purchases', '5-day early access to sales', 'Priority customer support', 'Access to exclusive products', 'Birthday bonus rewards'],
                'sort_order' => 3,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Annual',
                'slug' => 'premium-annual',
                'description' => 'Save 20% with annual billing. Best value for regular shoppers.',
                'price' => 1899,
                'billing_cycle' => 'yearly',
                'duration_days' => 365,
                'free_shipping' => true,
                'discount_percentage' => 10,
                'early_access_days' => 2,
                'priority_support' => true,
                'exclusive_products' => false,
                'features' => ['Free shipping on all orders', '10% off all purchases', '2-day early access to sales', 'Priority customer support', 'Save 20% vs monthly'],
                'sort_order' => 4,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Elite Annual',
                'slug' => 'elite-annual',
                'description' => 'Maximum savings with all elite benefits for a full year.',
                'price' => 4799,
                'billing_cycle' => 'yearly',
                'duration_days' => 365,
                'free_shipping' => true,
                'discount_percentage' => 15,
                'early_access_days' => 5,
                'priority_support' => true,
                'exclusive_products' => true,
                'features' => ['Free shipping on all orders', '15% off all purchases', '5-day early access to sales', 'Priority customer support', 'Access to exclusive products', 'Birthday bonus rewards', 'Save 20% vs monthly'],
                'sort_order' => 5,
                'is_popular' => false,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
