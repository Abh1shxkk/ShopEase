@extends('layouts.shop')

@section('title', 'Our Ethos')
@section('description', 'Sustainability through rediscovery. We preserve cultures by sourcing timeless pieces while reducing waste.')

@section('content')
<style>
    /* Fade in animation on scroll */
    .fade-in-section {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
    .fade-in-section.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Fade in from left */
    .fade-in-left {
        opacity: 0;
        transform: translateX(-60px);
        transition: opacity 1s ease-out, transform 1s ease-out;
    }
    .fade-in-left.is-visible {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* Fade in from right */
    .fade-in-right {
        opacity: 0;
        transform: translateX(60px);
        transition: opacity 1s ease-out, transform 1s ease-out;
    }
    .fade-in-right.is-visible {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* Image zoom on hover */
    .image-zoom {
        overflow: hidden;
    }
    .image-zoom img {
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .image-zoom:hover img {
        transform: scale(1.08);
    }
    
    /* Hero parallax effect */
    .hero-parallax {
        background-attachment: fixed;
        background-position: center;
        background-size: cover;
    }
    
    /* Quote mark styling */
    .quote-mark {
        font-family: 'Playfair Display', serif;
        font-size: 120px;
        line-height: 1;
        color: #e2e8f0;
        position: absolute;
        top: -20px;
        left: 0;
    }
    
    /* Text reveal animation */
    .text-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .is-visible .text-reveal {
        opacity: 1;
        transform: translateY(0);
    }
    .is-visible .text-reveal:nth-child(1) { transition-delay: 0.1s; }
    .is-visible .text-reveal:nth-child(2) { transition-delay: 0.2s; }
    .is-visible .text-reveal:nth-child(3) { transition-delay: 0.3s; }
    .is-visible .text-reveal:nth-child(4) { transition-delay: 0.4s; }
    
    /* Value card hover */
    .value-card {
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    .value-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    /* Italic heading style */
    .italic-heading {
        font-style: italic;
        font-weight: 400;
    }
    
    /* Underline link animation */
    .link-underline {
        position: relative;
        display: inline-block;
    }
    .link-underline::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 1px;
        background: currentColor;
        transform: scaleX(1);
        transition: transform 0.3s ease;
    }
    .link-underline:hover::after {
        transform: scaleX(1.1);
    }
</style>

{{-- Hero Section with Quote Style --}}
<section class="py-20 md:py-32 bg-white">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-start">
            {{-- Left Content --}}
            <div class="fade-in-left relative">
                <span class="quote-mark">"</span>
                <div class="pt-16">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif text-slate-900 leading-tight mb-8">
                        Sustainability<br>
                        Through<br>
                        <span class="italic-heading">Rediscovery.</span>
                    </h1>
                    <p class="text-slate-600 text-[15px] leading-relaxed font-light max-w-md mb-8">
                        We don't just make products; we preserve cultures. By sourcing pieces that are timeless, we reduce waste while honoring the craftsmanship of artisans around the world.
                    </p>
                    <a href="{{ route('pages.story') }}" class="link-underline text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900">
                        Read Our Story
                    </a>
                </div>
            </div>
            
            {{-- Right Images --}}
            <div class="fade-in-right relative">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 image-zoom aspect-[4/5]">
                        <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&q=80&w=1000" alt="Sustainability" class="w-full h-full object-cover">
                    </div>
                    <div class="col-span-6 image-zoom aspect-square -mt-24 ml-4 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=600" alt="Craftsmanship" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Ethos Sections from Database --}}
@forelse($sections as $index => $section)
    @if($section->image_position === 'background')
        {{-- Full Background Section --}}
        <section class="relative min-h-[70vh] flex items-center overflow-hidden">
            <div class="absolute inset-0 hero-parallax" style="background-image: url('{{ $section->image_url ?? "https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=2000" }}')">
                <div class="absolute inset-0 bg-slate-900/60"></div>
            </div>
            <div class="fade-in-section relative z-10 max-w-4xl mx-auto px-6 py-24 text-center text-white">
                <h2 class="text-reveal text-4xl md:text-5xl font-serif mb-4">{{ $section->title }}</h2>
                @if($section->subtitle)
                <p class="text-reveal text-lg text-white/70 mb-8 italic">{{ $section->subtitle }}</p>
                @endif
                <div class="text-reveal text-white/90 text-[15px] leading-relaxed max-w-2xl mx-auto font-light">
                    {!! nl2br(e($section->content)) !!}
                </div>
                @if($section->button_text && $section->button_link)
                <div class="text-reveal mt-10">
                    <a href="{{ $section->button_link }}" class="inline-block text-[11px] font-bold tracking-[0.2em] uppercase border border-white px-10 py-4 text-white hover:bg-white hover:text-slate-900 transition-all duration-300">
                        {{ $section->button_text }}
                    </a>
                </div>
                @endif
            </div>
        </section>
    @elseif($section->image_position === 'full')
        {{-- Full Width Image Section --}}
        <section class="py-24 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
            <div class="max-w-[1440px] mx-auto px-6 md:px-12">
                <div class="fade-in-section text-center mb-12">
                    <h2 class="text-reveal text-4xl md:text-5xl font-serif text-slate-900 mb-4">{{ $section->title }}</h2>
                    @if($section->subtitle)
                    <p class="text-reveal text-lg text-slate-500 italic">{{ $section->subtitle }}</p>
                    @endif
                </div>
                @if($section->image_url)
                <div class="fade-in-section image-zoom mb-12 aspect-[21/9]">
                    <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                </div>
                @endif
                <div class="fade-in-section max-w-3xl mx-auto text-center text-slate-600 text-[15px] leading-relaxed font-light">
                    {!! nl2br(e($section->content)) !!}
                </div>
            </div>
        </section>
    @else
        {{-- Left/Right Layout --}}
        <section class="py-24 {{ $index % 2 === 0 ? 'bg-slate-50' : 'bg-white' }}">
            <div class="max-w-[1440px] mx-auto px-6 md:px-12">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    {{-- Content --}}
                    <div class="{{ $section->image_position === 'right' ? 'fade-in-left lg:order-1' : 'fade-in-right lg:order-2' }}">
                        @if($section->subtitle)
                        <p class="text-reveal text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">{{ $section->subtitle }}</p>
                        @endif
                        <h2 class="text-reveal text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">{{ $section->title }}</h2>
                        <div class="text-reveal text-slate-600 text-[15px] leading-relaxed font-light space-y-4">
                            {!! nl2br(e($section->content)) !!}
                        </div>
                        @if($section->button_text && $section->button_link)
                        <div class="text-reveal mt-10">
                            <a href="{{ $section->button_link }}" class="link-underline text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900">
                                {{ $section->button_text }}
                            </a>
                        </div>
                        @endif
                    </div>
                    {{-- Image --}}
                    <div class="{{ $section->image_position === 'right' ? 'fade-in-right lg:order-2' : 'fade-in-left lg:order-1' }}">
                        <div class="image-zoom aspect-[4/5] shadow-2xl">
                            <img src="{{ $section->image_url ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1000' }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@empty
    {{-- Default Sections if none in database --}}
    
    {{-- Sustainability Section --}}
    <section class="py-24 bg-slate-50">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in-left">
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">Our Commitment</p>
                    <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">Mindful<br><span class="italic-heading">Consumption</span></h2>
                    <div class="text-slate-600 text-[15px] leading-relaxed font-light space-y-4">
                        <p>We believe in quality over quantity. Each piece in our collection is carefully selected to ensure it meets our standards of craftsmanship and sustainability.</p>
                        <p>By choosing timeless designs over fleeting trends, we encourage a more thoughtful approach to consumption that benefits both you and the planet.</p>
                    </div>
                </div>
                <div class="fade-in-right image-zoom aspect-[4/5] shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=1000" alt="Mindful Consumption" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- Artisan Section --}}
    <section class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in-left lg:order-2">
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">Preserving Heritage</p>
                    <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">Supporting<br><span class="italic-heading">Artisans</span></h2>
                    <div class="text-slate-600 text-[15px] leading-relaxed font-light space-y-4">
                        <p>Behind every product is a story of skilled hands and generations of knowledge. We partner directly with artisan communities to ensure fair compensation and preserve traditional crafts.</p>
                        <p>Your purchase directly supports these communities, helping to keep ancient techniques alive for future generations.</p>
                    </div>
                </div>
                <div class="fade-in-right lg:order-1 image-zoom aspect-[4/5] shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1452860606245-08befc0ff44b?auto=format&fit=crop&q=80&w=1000" alt="Supporting Artisans" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
@endforelse

{{-- Our Values Section --}}
<section class="py-24 bg-slate-900 text-white">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="fade-in-section text-center mb-16">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">What We Stand For</p>
            <h2 class="text-4xl md:text-5xl font-serif mb-4">Our Values</h2>
            <p class="text-slate-400 text-lg font-light max-w-2xl mx-auto">The principles that guide everything we do</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($values as $value)
            <div class="fade-in-section value-card bg-slate-800/50 p-8 text-center">
                @if($value->image_url)
                <div class="image-zoom w-full aspect-video mb-6 overflow-hidden">
                    <img src="{{ $value->image_url }}" alt="{{ $value->title }}" class="w-full h-full object-cover">
                </div>
                @else
                <div class="w-16 h-16 bg-slate-700 flex items-center justify-center mx-auto mb-6">
                    @if($value->icon === 'leaf')
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    @elseif($value->icon === 'heart')
                    <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    @elseif($value->icon === 'globe')
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                @endif
                <h3 class="text-xl font-serif mb-3">{{ $value->title }}</h3>
                <p class="text-slate-400 text-[14px] font-light leading-relaxed">{{ $value->description }}</p>
            </div>
            @empty
            {{-- Default Values --}}
            <div class="fade-in-section value-card bg-slate-800/50 p-8 text-center">
                <div class="w-16 h-16 bg-slate-700 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <h3 class="text-xl font-serif mb-3">Sustainability</h3>
                <p class="text-slate-400 text-[14px] font-light leading-relaxed">We prioritize eco-friendly practices and materials in everything we do.</p>
            </div>
            <div class="fade-in-section value-card bg-slate-800/50 p-8 text-center">
                <div class="w-16 h-16 bg-slate-700 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-xl font-serif mb-3">Craftsmanship</h3>
                <p class="text-slate-400 text-[14px] font-light leading-relaxed">Every piece is crafted with care, honoring traditional techniques.</p>
            </div>
            <div class="fade-in-section value-card bg-slate-800/50 p-8 text-center">
                <div class="w-16 h-16 bg-slate-700 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-serif mb-3">Community</h3>
                <p class="text-slate-400 text-[14px] font-light leading-relaxed">We support artisan communities and fair trade practices worldwide.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-24 bg-white">
    <div class="fade-in-section max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-6">Join Our Journey</h2>
        <p class="text-lg text-slate-500 font-light mb-10 max-w-2xl mx-auto">Be part of a movement that values quality, sustainability, and the preservation of traditional craftsmanship.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase bg-slate-900 text-white px-10 py-4 hover:bg-slate-800 transition-all duration-300">
                Shop Collection
            </a>
            <a href="{{ route('pages.story') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase border border-slate-300 text-slate-700 px-10 py-4 hover:border-slate-900 transition-all duration-300">
                Our Story
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in-section, .fade-in-left, .fade-in-right');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, { threshold: 0.15 });
    
    fadeElements.forEach(element => observer.observe(element));
});
</script>
@endpush
