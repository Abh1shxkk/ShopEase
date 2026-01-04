<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use App\Models\FeaturedSection;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // General Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'ShopEase', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Shop Smart, Live Better', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Discover quality products at unbeatable prices.', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'hello@shopease.com', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+91 98765 43210', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_address', 'value' => 'Mumbai, Maharashtra, India', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/shopease', 'type' => 'text', 'group' => 'general'],
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'group' => 'general'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'text', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // Hero Banners
        $banners = [
            [
                'title' => 'SHOP KILIM CLOGS',
                'subtitle' => 'Hand-crafted comfort using vintage textiles.',
                'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'SHOP HIM',
                'button_link' => '/shop?gender=men',
                'button_text_2' => 'SHOP HER',
                'button_link_2' => '/shop?gender=women',
                'sort_order' => 0,
            ],
            [
                'title' => 'THE ART OF WANDERING',
                'subtitle' => 'Premium weekender bags for your next journey.',
                'image' => 'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'SHOP HIM',
                'button_link' => '/shop?gender=men',
                'button_text_2' => 'SHOP HER',
                'button_link_2' => '/shop?gender=women',
                'sort_order' => 1,
            ],
            [
                'title' => 'VINTAGE TEXTILE SOULS',
                'subtitle' => 'Each piece tells a story of heritage and craft.',
                'image' => 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'SHOP HIM',
                'button_link' => '/shop?gender=men',
                'button_text_2' => 'SHOP HER',
                'button_link_2' => '/shop?gender=women',
                'sort_order' => 2,
            ],
        ];

        foreach ($banners as $banner) {
            HeroBanner::updateOrCreate(['title' => $banner['title']], $banner);
        }

        // Category Showcase
        $categories = [
            ['name' => 'Women Category', 'title' => 'Women', 'link' => '/shop?gender=women', 'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800', 'section_type' => 'category_showcase', 'sort_order' => 0],
            ['name' => 'Men Category', 'title' => 'Men', 'link' => '/shop?gender=men', 'image' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?auto=format&fit=crop&q=80&w=800', 'section_type' => 'category_showcase', 'sort_order' => 1],
            ['name' => 'Accessories Category', 'title' => 'Accessories', 'link' => '/shop?category=Accessories', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=800', 'section_type' => 'category_showcase', 'sort_order' => 2],
            ['name' => 'New Arrivals', 'title' => 'New Arrivals', 'link' => '/shop?sort=newest', 'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800', 'section_type' => 'category_showcase', 'sort_order' => 3],
        ];

        foreach ($categories as $cat) {
            FeaturedSection::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // Heritage Section
        FeaturedSection::updateOrCreate(
            ['name' => 'Heritage Main'],
            [
                'name' => 'Heritage Main',
                'title' => 'Sustainability Through Rediscovery.',
                'description' => "We don't just make products; we preserve cultures. By sourcing kilims that are up to 100 years old, we reduce waste while honoring the geometric languages of nomadic tribes.",
                'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=1000',
                'link' => '/about',
                'link_text' => 'Read Our Ethos',
                'section_type' => 'heritage',
                'sort_order' => 0,
            ]
        );

        // Journal Posts
        $journals = [
            ['name' => 'Journal 1', 'title' => 'What the Places We Call Home Have Taught Us', 'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800', 'section_type' => 'journal', 'sort_order' => 0],
            ['name' => 'Journal 2', 'title' => 'Still Naughty. Still Saucy. Still Yummy.', 'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800', 'section_type' => 'journal', 'sort_order' => 1],
            ['name' => 'Journal 3', 'title' => 'The Art of Wandering Without a Plan', 'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 'section_type' => 'journal', 'sort_order' => 2],
        ];

        foreach ($journals as $journal) {
            FeaturedSection::updateOrCreate(['name' => $journal['name']], $journal);
        }
    }
}
