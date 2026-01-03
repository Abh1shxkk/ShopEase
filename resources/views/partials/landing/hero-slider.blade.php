{{-- Hero Slider Component --}}
@php
$heroSlides = [
    [
        'id' => 1,
        'title' => 'SHOP KILIM CLOGS',
        'subtitle' => 'Hand-crafted comfort using vintage textiles.',
        'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&q=80&w=1600',
    ],
    [
        'id' => 2,
        'title' => 'THE ART OF WANDERING',
        'subtitle' => 'Premium weekender bags for your next journey.',
        'image' => 'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&q=80&w=1600',
    ],
    [
        'id' => 3,
        'title' => 'VINTAGE TEXTILE SOULS',
        'subtitle' => 'Each piece tells a story of heritage and craft.',
        'image' => 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?auto=format&fit=crop&q=80&w=1600',
    ],
];
@endphp

<div class="relative h-[90vh] min-h-[700px] w-full overflow-hidden bg-white">
    {{-- Slides --}}
    @foreach($heroSlides as $index => $slide)
        <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'active' : 'inactive' }}">
            <img 
                src="{{ $slide['image'] }}" 
                alt="{{ $slide['title'] }}"
                class="w-full h-full object-cover"
            />

            <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center p-6 bg-black/5">
                <div class="hero-content {{ $index === 0 ? 'active' : 'inactive' }}">
                    <h1 class="text-5xl md:text-6xl font-serif tracking-[0.1em] text-white mb-8 drop-shadow-sm uppercase">
                        {{ $slide['title'] }}
                    </h1>
                    <div class="flex gap-4 justify-center">
                        <button class="min-w-[160px] px-8 py-4 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] hover:bg-slate-900 hover:text-white transition-all duration-300 rounded-none uppercase">
                            SHOP HIM
                        </button>
                        <button class="min-w-[160px] px-8 py-4 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] hover:bg-slate-900 hover:text-white transition-all duration-300 rounded-none uppercase">
                            SHOP HER
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Navigation Dots --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 flex gap-4">
        @foreach($heroSlides as $index => $slide)
            <button 
                class="slider-dot w-2 h-2 rounded-full border border-white {{ $index === 0 ? 'active' : 'inactive' }}"
                data-slide="{{ $index }}"
            ></button>
        @endforeach
    </div>
</div>
