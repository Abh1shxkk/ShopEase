{{-- Journal Section Component --}}
@php
use App\Models\BlogPost;
use App\Models\FeaturedSection;

// Try to get blog posts first
$journalPosts = BlogPost::published()
    ->latest('published_at')
    ->take(3)
    ->get();

// Fallback to featured sections if no blog posts
if ($journalPosts->isEmpty()) {
    $featuredPosts = FeaturedSection::active()->byType('journal')->get();
    if ($featuredPosts->isNotEmpty()) {
        $journalPosts = $featuredPosts->map(function($post) {
            return (object)[
                'title' => $post->title,
                'slug' => null,
                'featured_image' => $post->image_url,
                'published_at' => $post->created_at,
            ];
        });
    }
}

// Final fallback with static data
if ($journalPosts->isEmpty()) {
    $journalPosts = collect([
        (object)['title' => 'What the Places We Call Home Have Taught Us', 'slug' => null, 'featured_image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800', 'published_at' => now()->subDays(10)],
        (object)['title' => 'Still Naughty. Still Saucy. Still Yummy.', 'slug' => null, 'featured_image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800', 'published_at' => now()->subDays(20)],
        (object)['title' => 'The Art of Wandering Without a Plan', 'slug' => null, 'featured_image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 'published_at' => now()->subDays(30)],
    ]);
}
@endphp

<section class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        {{-- Section Header --}}
        <div class="mb-16 text-center reveal">
            <h2 class="text-3xl font-serif tracking-wide mb-4">Journal</h2>
            <div class="h-[1px] bg-slate-900 mx-auto title-underline"></div>
        </div>

        {{-- Journal Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 stagger-container">
            @foreach($journalPosts as $post)
                <a href="{{ $post->slug ? route('blog.show', $post->slug) : route('blog.index') }}" class="group cursor-pointer block stagger-item product-card-hover">
                    <div class="aspect-video overflow-hidden mb-6 bg-slate-50 curtain-reveal">
                        <img 
                            src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&q=80&w=800' }}" 
                            alt="{{ $post->title }}" 
                            class="w-full h-full object-cover grayscale-hover"
                        />
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-3">
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : now()->format('M d, Y') }}
                    </p>
                    <h4 class="text-lg font-serif tracking-wide leading-snug group-hover:text-slate-600 transition-colors">
                        {{ $post->title }}
                    </h4>
                </a>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="mt-16 text-center reveal">
            <a href="{{ route('blog.index') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase underline underline-offset-8 decoration-slate-200 underline-animate hover:decoration-slate-900 btn-arrow">
                View All
                <svg class="w-4 h-4 arrow-icon inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
