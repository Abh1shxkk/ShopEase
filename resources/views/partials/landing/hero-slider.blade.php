{{-- Hero Slider Component --}}
@php
use App\Models\HeroBanner;
$heroSlides = HeroBanner::active()->get();

// Fallback if no banners in database
if ($heroSlides->isEmpty()) {
    $heroSlides = collect([
        (object)[
            'id' => 1,
            'title' => 'SHOP KILIM CLOGS',
            'subtitle' => 'Hand-crafted comfort using vintage textiles.',
            'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&q=80&w=1600',
            'button_text' => 'SHOP HIM',
            'button_link' => '/shop?gender=men',
            'button_text_2' => 'SHOP HER',
            'button_link_2' => '/shop?gender=women',
        ],
    ]);
}
@endphp

<div class="relative h-[90vh] min-h-[700px] w-full overflow-hidden bg-white parallax-container">
    {{-- Slides --}}
    @foreach($heroSlides as $index => $slide)
        <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'active' : 'inactive' }}">
            <img 
                src="{{ $slide->image_url }}" 
                alt="{{ $slide->title }}"
                class="w-full h-full object-cover hero-image"
            />

            <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center p-6 bg-black/5">
                <div class="hero-content {{ $index === 0 ? 'active' : 'inactive' }}">
                    <h1 class="text-5xl md:text-6xl font-serif tracking-[0.1em] text-white mb-8 drop-shadow-sm uppercase hero-title-animate">
                        {{ $slide->title }}
                    </h1>
                    @if($slide->subtitle)
                    <p class="text-lg text-white/90 mb-8 font-light hero-subtitle-animate">{{ $slide->subtitle }}</p>
                    @endif
                    <div class="flex gap-4 justify-center hero-button-animate">
                        @if($slide->button_text)
                        <a href="{{ $slide->button_link ?? '/shop' }}" class="min-w-[160px] px-8 py-4 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] hover:bg-slate-900 hover:text-white transition-all duration-300 rounded-none uppercase btn-ripple btn-magnetic">
                            {{ $slide->button_text }}
                        </a>
                        @endif
                        @if($slide->button_text_2)
                        <a href="{{ $slide->button_link_2 ?? '/shop' }}" class="min-w-[160px] px-8 py-4 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] hover:bg-slate-900 hover:text-white transition-all duration-300 rounded-none uppercase btn-ripple btn-magnetic">
                            {{ $slide->button_text_2 }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-28 left-1/2 -translate-x-1/2 z-30 scroll-indicator hidden md:block">
        <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>

    {{-- Navigation Dots --}}
    @if($heroSlides->count() > 1)
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 flex gap-4">
        @foreach($heroSlides as $index => $slide)
            <button 
                class="slider-dot w-2 h-2 rounded-full border border-white {{ $index === 0 ? 'active' : 'inactive' }}"
                data-slide="{{ $index }}"
            ></button>
        @endforeach
    </div>
    @endif
</div>
