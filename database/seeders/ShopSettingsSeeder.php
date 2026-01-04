<?php

namespace Database\Seeders;

use App\Models\ShopBanner;
use App\Models\FooterLink;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class ShopSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Shop Banners
        $banners = [
            [
                'title' => 'New Season Arrivals',
                'subtitle' => 'Discover the latest trends',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600',
                'sort_order' => 1,
            ],
            [
                'title' => 'Summer Collection',
                'subtitle' => 'Light & breezy styles',
                'image' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600',
                'sort_order' => 2,
            ],
            [
                'title' => 'Premium Quality',
                'subtitle' => 'Crafted with care',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600',
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            ShopBanner::firstOrCreate(['title' => $banner['title']], $banner);
        }

        // Footer Links - Shop
        $shopLinks = [
            ['title' => 'Women', 'url' => '/shop?gender=women', 'sort_order' => 1],
            ['title' => 'Men', 'url' => '/shop?gender=men', 'sort_order' => 2],
            ['title' => 'All Products', 'url' => '/shop', 'sort_order' => 3],
        ];

        foreach ($shopLinks as $link) {
            FooterLink::firstOrCreate(
                ['title' => $link['title'], 'group' => 'shop'],
                array_merge($link, ['group' => 'shop'])
            );
        }

        // Footer Links - Account
        $accountLinks = [
            ['title' => 'My Profile', 'url' => '/profile', 'sort_order' => 1],
            ['title' => 'My Orders', 'url' => '/orders', 'sort_order' => 2],
            ['title' => 'Wishlist', 'url' => '/wishlist', 'sort_order' => 3],
            ['title' => 'Shopping Cart', 'url' => '/cart', 'sort_order' => 4],
        ];

        foreach ($accountLinks as $link) {
            FooterLink::firstOrCreate(
                ['title' => $link['title'], 'group' => 'account'],
                array_merge($link, ['group' => 'account'])
            );
        }

        // Footer Settings
        SiteSetting::set('footer_email', 'support@shopease.com', 'text', 'footer');
        SiteSetting::set('footer_phone', '+91 98765 43210', 'text', 'footer');
        SiteSetting::set('footer_address', "123 Commerce Street,\nMumbai, MH 400001", 'text', 'footer');
        SiteSetting::set('footer_copyright', '© ' . date('Y') . ' ShopEase. All rights reserved.', 'text', 'footer');
    }
}
