<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $lifestyle = BlogCategory::create([
            'name' => 'Lifestyle',
            'slug' => 'lifestyle',
            'description' => 'Stories about living well'
        ]);

        $travel = BlogCategory::create([
            'name' => 'Travel',
            'slug' => 'travel',
            'description' => 'Adventures and destinations'
        ]);

        $culture = BlogCategory::create([
            'name' => 'Culture',
            'slug' => 'culture',
            'description' => 'Art, heritage and traditions'
        ]);

        // Create posts
        BlogPost::create([
            'title' => 'What the Places We Call Home Have Taught Us',
            'slug' => 'what-the-places-we-call-home-have-taught-us',
            'excerpt' => 'A reflection on the spaces that shape our lives and the memories they hold.',
            'content' => "Home is more than just a place. It is where our stories begin, where we learn to love, and where we find comfort in the familiar. Through the years, the places we call home teach us about resilience, about change, and about the beauty of belonging.\n\nEvery corner holds a memory, every wall has witnessed our growth. From childhood bedrooms to first apartments, each space has contributed to who we are today.\n\nThe kitchen where we learned to cook our grandmother's recipes. The backyard where we played until the streetlights came on. The living room where we gathered for celebrations and quiet evenings alike.\n\nThese spaces shape us in ways we often don't realize until we've moved on. They teach us about comfort, about creating sanctuary, about the importance of having a place to return to.",
            'featured_image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800',
            'category_id' => $lifestyle->id,
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'is_featured' => true,
            'tags' => ['home', 'lifestyle', 'reflection']
        ]);

        BlogPost::create([
            'title' => 'Still Naughty. Still Saucy. Still Yummy.',
            'slug' => 'still-naughty-still-saucy-still-yummy',
            'excerpt' => 'Exploring the bold flavors and vibrant culture of street food around the world.',
            'content' => "There is something magical about street food. The sizzle of a hot pan, the aroma of spices wafting through the air, the joy of discovering a hidden gem tucked away in a narrow alley.\n\nFrom the bustling markets of Bangkok to the food trucks of Los Angeles, street food tells the story of a place and its people. It is authentic, unpretentious, and absolutely delicious.\n\nStreet food vendors are the unsung heroes of culinary culture. They preserve recipes passed down through generations, adapting them to modern tastes while keeping the soul intact.\n\nWhether it's a steaming bowl of pho on a Hanoi sidewalk or tacos from a Mexico City cart, these humble dishes connect us to the heart of a culture in ways that fine dining never could.",
            'featured_image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800',
            'category_id' => $culture->id,
            'status' => 'published',
            'published_at' => now()->subDays(10),
            'is_featured' => true,
            'tags' => ['food', 'culture', 'travel']
        ]);

        BlogPost::create([
            'title' => 'The Art of Wandering Without a Plan',
            'slug' => 'the-art-of-wandering-without-a-plan',
            'excerpt' => 'Sometimes the best adventures come from getting lost.',
            'content' => "In a world of GPS and detailed itineraries, there is something liberating about simply wandering. No destination in mind, no schedule to keep, just the open road and endless possibilities.\n\nWandering teaches us to be present, to notice the small details we usually rush past. It reminds us that the journey itself can be the destination.\n\nThe best discoveries often happen when we least expect them. A hidden cafe down a side street. A breathtaking view around an unexpected corner. A conversation with a stranger that changes our perspective.\n\nSo next time you travel, leave some room for spontaneity. Put away the map, follow your curiosity, and see where the day takes you. You might just find exactly what you didn't know you were looking for.",
            'featured_image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',
            'category_id' => $travel->id,
            'status' => 'published',
            'published_at' => now()->subDays(15),
            'is_featured' => true,
            'tags' => ['travel', 'adventure', 'wanderlust']
        ]);
    }
}
