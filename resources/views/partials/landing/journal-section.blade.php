{{-- Journal Section Component --}}
@php
use App\Models\FeaturedSection;
$journalPosts = FeaturedSection::active()->byType('journal')->get();

// Fallback if no posts in database
if ($journalPosts->isEmpty()) {
    $journalPosts = collect([
        (object)['title' => 'What the Places We Call Home Have Taught Us', 'image_url' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800', 'created_at' => now()->subDays(10)],
        (object)['title' => 'Still Naughty. Still Saucy. Still Yummy.', 'image_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800', 'created_at' => now()->subDays(20)],
        (object)['title' => 'The Art of Wandering Without a Plan', 'image_url' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800', 'created_at' => now()->subDays(30)],
    ]);
}
@endphp

<section class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        {{-- Section Header --}}
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-serif tracking-wide mb-4">Journal</h2>
            <div class="w-12 h-[1px] bg-slate-900 mx-auto"></div>
        </div>

        {{-- Journal Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach($journalPosts as $post)
                <a href="{{ $post->link ?? '#' }}" class="group cursor-pointer block">
                    <div class="aspect-video overflow-hidden mb-6 bg-slate-50">
                        <img 
                            src="{{ $post->image_url }}" 
                            alt="{{ $post->title }}" 
                            class="w-full h-full object-cover grayscale-hover"
                        />
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-3">{{ $post->created_at->format('M d, Y') }}</p>
                    <h4 class="text-lg font-serif tracking-wide leading-snug group-hover:text-slate-600 transition-colors">
                        {{ $post->title }}
                    </h4>
                </a>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="mt-16 text-center">
            <button class="text-[11px] font-bold tracking-[0.2em] uppercase underline underline-offset-8 decoration-slate-200 underline-animate hover:decoration-slate-900">
                View All
            </button>
        </div>
    </div>
</section>
