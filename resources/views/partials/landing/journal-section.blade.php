{{-- Journal Section Component - Fixed --}}
@php
use App\Models\BlogPost;
use App\Models\FeaturedSection;

// Try to get blog posts first
$journalPosts = collect();

try {
    $journalPosts = BlogPost::published()
        ->whereNotNull('featured_image')
        ->where('featured_image', 'LIKE', 'http%')
        ->latest('published_at')
        ->take(3)
        ->get();
} catch (\Exception $e) {
    // Fallback on error
}

// Fallback to featured sections if no valid blog posts
if ($journalPosts->isEmpty()) {
    try {
        $featuredPosts = FeaturedSection::active()->byType('journal')->get();
        if ($featuredPosts->isNotEmpty()) {
            $journalPosts = $featuredPosts->map(function($post) {
                return (object)[
                    'title' => $post->title,
                    'slug' => null,
                    'excerpt' => $post->description ?? 'Discover the story behind our craftsmanship.',
                    'featured_image' => $post->image_url,
                    'published_at' => $post->created_at,
                ];
            });
        }
    } catch (\Exception $e) {
        // Fallback on error
    }
}

// Final fallback with static data
if ($journalPosts->isEmpty()) {
    $journalPosts = collect([
        (object)[
            'title' => 'What the Places We Call Home Have Taught Us', 
            'slug' => null, 
            'excerpt' => 'Exploring the cultural heritage that inspires our designs.',
            'featured_image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800', 
            'published_at' => now()->subDays(10),
        ],
        (object)[
            'title' => 'Still Naughty. Still Saucy. Still Yummy.', 
            'slug' => null, 
            'excerpt' => 'Our latest collection brings playful elegance to everyday fashion.',
            'featured_image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800', 
            'published_at' => now()->subDays(20),
        ],
        (object)[
            'title' => 'The Art of Wandering Without a Plan', 
            'slug' => null, 
            'excerpt' => 'How spontaneous travel shaped our approach to design.',
            'featured_image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 
            'published_at' => now()->subDays(30),
        ],
    ]);
}
@endphp

<section class="py-24 bg-white border-t border-slate-100 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        {{-- Section Header --}}
        <div class="mb-16 flex flex-col md:flex-row items-center justify-between gap-6 reveal">
            <div>
                <span class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4 block">Stories & Insights</span>
                <h2 class="text-3xl md:text-4xl font-serif tracking-wide">From Our Journal</h2>
            </div>
            <a href="{{ route('blog.index') }}" 
               class="text-[11px] font-bold tracking-[0.2em] uppercase border-2 border-slate-900 px-6 py-3 
                      hover:bg-slate-900 hover:text-white transition-all duration-300 btn-ripple">
                View All Stories
            </a>
        </div>

        {{-- Journal Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 stagger-container">
            @foreach($journalPosts as $index => $post)
                @php
                    // Validate image URL
                    $imageUrl = $post->featured_image ?? null;
                    if (!$imageUrl || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        $defaultImages = [
                            'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800',
                            'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800',
                            'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',
                        ];
                        $imageUrl = $defaultImages[$index % 3];
                    }
                    
                    // Build link URL safely
                    $linkUrl = route('blog.index');
                    if (!empty($post->slug)) {
                        try {
                            $linkUrl = route('blog.show', ['slug' => $post->slug]);
                        } catch (\Exception $e) {
                            $linkUrl = route('blog.index');
                        }
                    }
                @endphp
                
                <article class="stagger-item group">
                    <a href="{{ $linkUrl }}" class="block">
                        {{-- Image Container --}}
                        <div class="relative aspect-[4/3] overflow-hidden mb-5 bg-slate-100">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                onerror="this.src='https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&q=80&w=800'"
                            />
                            
                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            {{-- Read Arrow --}}
                            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 
                                        transform translate-y-2 group-hover:translate-y-0 
                                        transition-all duration-300">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div class="space-y-2">
                            {{-- Date --}}
                            <p class="text-[10px] font-medium tracking-widest text-slate-400 uppercase">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : now()->format('M d, Y') }}
                            </p>
                            
                            {{-- Title --}}
                            <h4 class="text-lg md:text-xl font-serif tracking-wide leading-snug 
                                       group-hover:text-amber-700 transition-colors duration-300">
                                {{ $post->title }}
                            </h4>
                            
                            {{-- Excerpt --}}
                            @if(isset($post->excerpt) && $post->excerpt)
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-2">
                                {{ Str::limit(strip_tags($post->excerpt), 100) }}
                            </p>
                            @endif
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
