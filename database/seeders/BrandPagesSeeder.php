<?php

namespace Database\Seeders;

use App\Models\BrandStorySection;
use App\Models\ProcessStep;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class BrandPagesSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        BrandStorySection::truncate();
        ProcessStep::truncate();
        TeamMember::truncate();

        // Brand Story Sections
        BrandStorySection::create([
            'title' => 'Our Beginning',
            'subtitle' => 'A passion for quality',
            'content' => "Every great journey begins with a single step. Ours started with a simple belief: that everyone deserves access to beautifully crafted, high-quality products that stand the test of time.\n\nFounded with a vision to bridge the gap between exceptional craftsmanship and everyday accessibility, we set out to create something special.",
            'image' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000',
            'image_position' => 'right',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        BrandStorySection::create([
            'title' => 'Our Mission',
            'subtitle' => 'Quality meets accessibility',
            'content' => "We believe that exceptional quality shouldn't come with an exceptional price tag. Our mission is to bring you carefully curated products that combine timeless design with superior craftsmanship.\n\nEvery item in our collection is selected with care, ensuring it meets our high standards for quality, durability, and style.",
            'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&q=80&w=1000',
            'image_position' => 'left',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        BrandStorySection::create([
            'title' => 'Our Promise',
            'subtitle' => 'Committed to excellence',
            'content' => "When you shop with us, you're not just buying a product – you're investing in quality that lasts. We stand behind everything we sell with our satisfaction guarantee.\n\nOur dedicated team is here to ensure your experience exceeds expectations, from browsing to delivery and beyond.",
            'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000',
            'image_position' => 'background',
            'button_text' => 'Shop Now',
            'button_link' => '/shop',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Process Steps - No emojis
        ProcessStep::create([
            'title' => 'Sourcing',
            'description' => "We carefully select materials from trusted suppliers around the world. Each material is chosen for its quality, sustainability, and ability to create products that last.\n\nOur sourcing team travels extensively to find the finest raw materials, building relationships with artisans and suppliers who share our commitment to excellence.",
            'icon' => null,
            'image' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000',
            'step_number' => 1,
            'is_active' => true,
        ]);

        ProcessStep::create([
            'title' => 'Design',
            'description' => "Our design team combines timeless aesthetics with modern functionality. Every piece is thoughtfully designed to be both beautiful and practical.\n\nWe believe great design should enhance your life, not complicate it. That's why we focus on clean lines, quality materials, and attention to detail.",
            'icon' => null,
            'image' => 'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?auto=format&fit=crop&q=80&w=1000',
            'step_number' => 2,
            'is_active' => true,
        ]);

        ProcessStep::create([
            'title' => 'Crafting',
            'description' => "Skilled artisans bring our designs to life using time-honored techniques combined with modern precision. Each piece is crafted with care and attention to detail.\n\nWe work with craftspeople who take pride in their work, ensuring every stitch, seam, and finish meets our exacting standards.",
            'icon' => null,
            'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&q=80&w=1000',
            'step_number' => 3,
            'is_active' => true,
        ]);

        ProcessStep::create([
            'title' => 'Quality Check',
            'description' => "Before any product reaches you, it undergoes rigorous quality inspection. We check every detail to ensure it meets our high standards.\n\nOur quality control process includes multiple checkpoints, from material inspection to final product review, ensuring consistency and excellence.",
            'icon' => null,
            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=1000',
            'step_number' => 4,
            'is_active' => true,
        ]);

        ProcessStep::create([
            'title' => 'Delivery',
            'description' => "We carefully package each order to ensure it arrives in perfect condition. Our shipping partners are chosen for their reliability and care.\n\nFrom our hands to yours, we ensure your purchase is protected and delivered with the same attention to detail we put into creating it.",
            'icon' => null,
            'image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&q=80&w=1000',
            'step_number' => 5,
            'is_active' => true,
        ]);

        // Team Members
        TeamMember::create([
            'name' => 'Sarah Johnson',
            'position' => 'Founder & CEO',
            'bio' => 'With over 15 years in the fashion industry, Sarah founded ShopEase with a vision to make quality accessible to everyone.',
            'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=400',
            'linkedin' => 'https://linkedin.com',
            'twitter' => 'https://twitter.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Michael Chen',
            'position' => 'Creative Director',
            'bio' => 'Michael brings a unique blend of traditional craftsmanship and modern design sensibility to every collection.',
            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=400',
            'linkedin' => 'https://linkedin.com',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Emily Rodriguez',
            'position' => 'Head of Operations',
            'bio' => 'Emily ensures that every order is processed with care and delivered on time, maintaining our high standards.',
            'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=400',
            'linkedin' => 'https://linkedin.com',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'David Kim',
            'position' => 'Quality Assurance Lead',
            'bio' => 'David\'s meticulous attention to detail ensures that every product meets our exacting quality standards.',
            'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=400',
            'sort_order' => 4,
            'is_active' => true,
        ]);
    }
}
