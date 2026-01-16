{{-- Category Showcase Component - Premium Enhanced --}}
@php
use App\Models\FeaturedSection;
$showcaseCategories = FeaturedSection::active()->byType('category_showcase')->get();

// Fallback if no sections in database
if ($showcaseCategories->isEmpty()) {
    $showcaseCategories = collect([
        (object)['title' => 'Women', 'subtitle' => 'Elegant collection', 'link' => route('shop.index', ['gender' => 'women']), 'image_url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800'],
        (object)['title' => 'Men', 'subtitle' => 'Classic styles', 'link' => route('shop.index', ['gender' => 'men']), 'image_url' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?auto=format&fit=crop&q=80&w=800'],
        (object)['title' => 'Accessories', 'subtitle' => 'Finishing touches', 'link' => route('shop.index', ['category' => 'Accessories']), 'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=800'],
        (object)['title' => 'New Arrivals', 'subtitle' => 'Fresh drops', 'link' => route('shop.index', ['sort' => 'newest']), 'image_url' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800'],
    ]);
}
@endphp

<section id="categories" class="py-24 bg-white border-t border-slate-100 overflow-hidden section-parallax">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        {{-- Section Header --}}
        <div class="mb-16 text-center reveal">
            <span class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4 block">Explore</span>
            <h2 class="text-3xl md:text-4xl font-serif tracking-wide mb-4">Shop by Category</h2>
            <div class="h-[1px] bg-slate-900 mx-auto title-underline"></div>
        </div>

        {{-- Category Grid with Premium Hover Effects --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($showcaseCategories->count(), 4) }} gap-1 bg-slate-200 stagger-container">
            @foreach($showcaseCategories as $index => $category)
                <a href="{{ $category->link ?? '/shop' }}" 
                   class="group relative h-[600px] overflow-hidden bg-white cursor-pointer block stagger-item">
                    
                    {{-- Image with Multiple Effects --}}
                    <img 
                        src="{{ $category->image_url }}" 
                        alt="{{ $category->title }}" 
                        class="w-full h-full object-cover transition-all duration-1000 ease-out
                               group-hover:scale-110 group-hover:rotate-1"
                    />
                    
                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent 
                                opacity-80 group-hover:opacity-90 transition-opacity duration-500"></div>
                    
                    {{-- Shine Effect --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-white/0 via-white/10 to-white/0 
                                translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                    
                    {{-- Content --}}
                    <div class="absolute inset-x-0 bottom-0 p-10 transform transition-transform duration-500 group-hover:-translate-y-4">
                        {{-- Category Number --}}
                        <span class="text-[10px] tracking-[0.3em] text-white/50 font-medium mb-2 block">
                            0{{ $index + 1 }}
                        </span>
                        
                        {{-- Title with Animation --}}
                        <h4 class="text-3xl md:text-4xl font-serif text-white tracking-wide mb-2 
                                   transform transition-all duration-500 group-hover:translate-x-2">
                            {{ $category->title }}
                        </h4>
                        
                        {{-- Subtitle (if available) --}}
                        @if(isset($category->subtitle))
                        <p class="text-sm text-white/70 mb-4 opacity-0 group-hover:opacity-100 
                                  transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-100">
                            {{ $category->subtitle }}
                        </p>
                        @endif
                        
                        {{-- Animated Line --}}
                        <div class="category-line h-[2px] bg-white"></div>
                        
                        {{-- Explore Button (appears on hover) --}}
                        <div class="mt-6 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 
                                    transition-all duration-500 delay-200">
                            <span class="inline-flex items-center gap-2 text-[11px] font-bold tracking-[0.2em] uppercase text-white">
                                Explore
                                <svg class="w-4 h-4 arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Top Corner Badge --}}
                    <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-[-10px] group-hover:translate-y-0">
                        <div class="w-12 h-12 rounded-full border border-white/30 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
