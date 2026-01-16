{{-- Hero Slider Component - Enhanced with More Photos --}}
@php
use App\Models\HeroBanner;
$heroSlides = HeroBanner::active()->get();

// Fallback if no banners in database - with more variety
if ($heroSlides->isEmpty()) {
    $heroSlides = collect([
        (object)[
            'id' => 1,
            'title' => 'THE ART OF WANDERING',
            'subtitle' => 'Premium weekender bags for your next journey.',
            'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=1600',
            'button_text' => 'SHOP HIM',
            'button_link' => '/shop?gender=men',
            'button_text_2' => 'SHOP HER',
            'button_link_2' => '/shop?gender=women',
        ],
        (object)[
            'id' => 2,
            'title' => 'TIMELESS ELEGANCE',
            'subtitle' => 'Discover handcrafted pieces that tell a story.',
            'image_url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1600',
            'button_text' => 'EXPLORE NOW',
            'button_link' => '/shop',
            'button_text_2' => null,
            'button_link_2' => null,
        ],
        (object)[
            'id' => 3,
            'title' => 'SUMMER COLLECTION',
            'subtitle' => 'Light fabrics, bold colors, endless possibilities.',
            'image_url' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&q=80&w=1600',
            'button_text' => 'SHOP SUMMER',
            'button_link' => '/shop',
            'button_text_2' => null,
            'button_link_2' => null,
        ],
        (object)[
            'id' => 4,
            'title' => 'LUXURY FOOTWEAR',
            'subtitle' => 'Step into comfort with our curated collection.',
            'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&q=80&w=1600',
            'button_text' => 'SHOP SHOES',
            'button_link' => '/shop?category=footwear',
            'button_text_2' => null,
            'button_link_2' => null,
        ],
        (object)[
            'id' => 5,
            'title' => 'ARTISAN ACCESSORIES',
            'subtitle' => 'Handmade with love, worn with pride.',
            'image_url' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&q=80&w=1600',
            'button_text' => 'DISCOVER',
            'button_link' => '/shop?category=accessories',
            'button_text_2' => null,
            'button_link_2' => null,
        ],
    ]);
}

// Floating showcase photos - more photos added
$floatingPhotos = [
    // Left side - stacked shoes
    [
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400',
        'position' => 'top-24 left-4 md:left-12',
        'size' => 'w-28 h-36 md:w-32 md:h-40',
        'rotation' => '-rotate-6',
        'zindex' => 'z-30',
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?auto=format&fit=crop&q=80&w=400',
        'position' => 'top-56 left-8 md:left-20',
        'size' => 'w-24 h-32 md:w-28 md:h-36',
        'rotation' => 'rotate-3',
        'zindex' => 'z-20',
    ],
    // Right side - stacked products
    [
        'image' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?auto=format&fit=crop&q=80&w=400',
        'position' => 'top-20 right-4 md:right-16',
        'size' => 'w-24 h-32 md:w-28 md:h-36',
        'rotation' => 'rotate-6',
        'zindex' => 'z-30',
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&q=80&w=400',
        'position' => 'top-48 right-8 md:right-24',
        'size' => 'w-28 h-36 md:w-32 md:h-40',
        'rotation' => '-rotate-3',
        'zindex' => 'z-20',
    ],
    // Bottom left
    [
        'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&q=80&w=400',
        'position' => 'bottom-32 left-4 md:left-24',
        'size' => 'w-24 h-32 md:w-28 md:h-36',
        'rotation' => 'rotate-6',
        'zindex' => 'z-20',
    ],
];
@endphp

<div class="relative h-[90vh] min-h-[600px] w-full overflow-hidden bg-slate-900 parallax-container">
    {{-- Slides --}}
    @foreach($heroSlides as $index => $slide)
        <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'active' : 'inactive' }}">
            <img 
                src="{{ $slide->image_url }}" 
                alt="{{ $slide->title }}"
                class="w-full h-full object-cover hero-image"
            />
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/50"></div>

            <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center p-6">
                <div class="hero-content {{ $index === 0 ? 'active' : 'inactive' }} max-w-4xl">
                    {{-- Premium Badge --}}
                    <div class="mb-6 hidden md:block">
                        <span class="text-[10px] font-bold tracking-[0.3em] uppercase text-white/70 border border-white/30 px-4 py-2">
                            Premium Collection
                        </span>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif tracking-[0.08em] text-white mb-6 drop-shadow-lg uppercase hero-title-animate">
                        {{ $slide->title }}
                    </h1>
                    
                    @if($slide->subtitle)
                    <p class="text-base md:text-lg text-white/90 mb-8 font-light max-w-2xl mx-auto hero-subtitle-animate">
                        {{ $slide->subtitle }}
                    </p>
                    @endif
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center hero-button-animate">
                        @if($slide->button_text)
                        <a href="{{ $slide->button_link ?? '/shop' }}" 
                           class="min-w-[160px] px-8 py-4 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] 
                                  hover:bg-slate-900 hover:text-white transition-all duration-300 
                                  uppercase btn-ripple btn-magnetic border-2 border-transparent hover:border-white">
                            {{ $slide->button_text }}
                        </a>
                        @endif
                        
                        @if($slide->button_text_2)
                        <a href="{{ $slide->button_link_2 ?? '/shop' }}" 
                           class="min-w-[160px] px-8 py-4 bg-transparent text-white text-[11px] font-bold tracking-[0.2em] 
                                  border-2 border-white hover:bg-white hover:text-slate-900 
                                  transition-all duration-300 uppercase btn-ripple btn-magnetic">
                            {{ $slide->button_text_2 }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Floating Showcase Photos (Only on large screens) --}}
    <div class="hidden xl:block">
        @foreach($floatingPhotos as $photo)
            <div class="hero-floating-photo {{ $photo['position'] }} {{ $photo['size'] }} {{ $photo['rotation'] }} {{ $photo['zindex'] }}">
                <img 
                    src="{{ $photo['image'] }}" 
                    alt="Product showcase" 
                    class="w-full h-full object-cover shadow-2xl"
                />
            </div>
        @endforeach
    </div>

    {{-- Navigation Dots --}}
    @if($heroSlides->count() > 1)
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-3">
        @foreach($heroSlides as $index => $slide)
            <button 
                class="slider-dot w-2.5 h-2.5 rounded-full border border-white/50 transition-all duration-300 
                       hover:scale-110 {{ $index === 0 ? 'active' : 'inactive' }}"
                data-slide="{{ $index }}"
                aria-label="Go to slide {{ $index + 1 }}"
            ></button>
        @endforeach
    </div>
    @endif
</div>
