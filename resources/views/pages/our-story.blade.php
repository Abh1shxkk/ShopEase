@extends('layouts.shop')

@section('title', 'Our Story')
@section('description', 'Discover the passion, craftsmanship, and dedication behind every piece we create.')

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
        transform: translateX(-40px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
    .fade-in-left.is-visible {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* Fade in from right */
    .fade-in-right {
        opacity: 0;
        transform: translateX(40px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
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
    
    /* Team member hover */
    .team-member {
        transition: transform 0.4s ease;
    }
    .team-member:hover {
        transform: translateY(-8px);
    }
    .team-member .team-image img {
        transition: transform 0.6s ease, filter 0.6s ease;
        filter: grayscale(100%);
    }
    .team-member:hover .team-image img {
        transform: scale(1.05);
        filter: grayscale(0%);
    }
    
    /* Smooth text reveal */
    .text-reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .is-visible .text-reveal {
        opacity: 1;
        transform: translateY(0);
    }
    .is-visible .text-reveal:nth-child(1) { transition-delay: 0.1s; }
    .is-visible .text-reveal:nth-child(2) { transition-delay: 0.2s; }
    .is-visible .text-reveal:nth-child(3) { transition-delay: 0.3s; }
    .is-visible .text-reveal:nth-child(4) { transition-delay: 0.4s; }
    
    /* Button hover animation */
    .btn-animate {
        position: relative;
        overflow: hidden;
        transition: color 0.3s ease;
    }
    .btn-animate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: #1e293b;
        transition: left 0.3s ease;
        z-index: -1;
    }
    .btn-animate:hover::before {
        left: 0;
    }
    .btn-animate:hover {
        color: white;
    }
</style>

{{-- Hero Section --}}
<section class="relative h-[60vh] min-h-[500px] overflow-hidden">
    <div class="absolute inset-0 hero-parallax" style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&q=80&w=2000')">
        <div class="absolute inset-0 bg-slate-900/60"></div>
    </div>
    <div class="relative z-10 h-full flex items-center justify-center text-center px-6">
        <div class="max-w-3xl">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-white/70 mb-6">Our Journey</p>
            <h1 class="text-5xl md:text-7xl font-serif text-white mb-6">Our Story</h1>
            <p class="text-lg text-white/80 font-light max-w-xl mx-auto">
                Discover the passion, craftsmanship, and dedication behind every piece we create.
            </p>
        </div>
    </div>
</section>

{{-- Story Sections --}}
@forelse($sections as $index => $section)
    @if($section->image_position === 'background')
        {{-- Background Image Layout --}}
        <section class="relative min-h-[600px] flex items-center overflow-hidden">
            <div class="absolute inset-0 hero-parallax" style="background-image: url('{{ $section->image_url ?? "https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000" }}')">
                <div class="absolute inset-0 bg-slate-900/70"></div>
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
        {{-- Full Width Layout --}}
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
                @if($section->button_text && $section->button_link)
                <div class="fade-in-section text-center mt-10">
                    <a href="{{ $section->button_link }}" class="btn-animate relative inline-block text-[11px] font-bold tracking-[0.2em] uppercase border border-slate-900 px-10 py-4 text-slate-900 z-10">
                        {{ $section->button_text }}
                    </a>
                </div>
                @endif
            </div>
        </section>
    @else
        {{-- Left/Right Layout --}}
        <section class="py-24 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
            <div class="max-w-[1440px] mx-auto px-6 md:px-12">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    {{-- Content --}}
                    <div class="{{ $section->image_position === 'right' ? 'fade-in-left lg:order-1' : 'fade-in-right lg:order-2' }}">
                        <p class="text-reveal text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">{{ $section->subtitle ?? 'Our Story' }}</p>
                        <h2 class="text-reveal text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">{{ $section->title }}</h2>
                        <div class="text-reveal text-slate-600 text-[15px] leading-relaxed font-light space-y-4">
                            {!! nl2br(e($section->content)) !!}
                        </div>
                        @if($section->button_text && $section->button_link)
                        <div class="text-reveal mt-10">
                            <a href="{{ $section->button_link }}" class="inline-block text-[11px] font-bold tracking-[0.2em] uppercase border-b border-slate-900 pb-2 text-slate-900 hover:text-slate-600 hover:border-slate-600 transition-all duration-300">
                                {{ $section->button_text }}
                            </a>
                        </div>
                        @endif
                    </div>
                    {{-- Image --}}
                    <div class="{{ $section->image_position === 'right' ? 'fade-in-right lg:order-2' : 'fade-in-left lg:order-1' }}">
                        <div class="image-zoom aspect-[4/5] shadow-2xl">
                            @if($section->image_url)
                            <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                            @else
                            <img src="https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@empty
    {{-- Default Content if no sections --}}
    <section class="py-24">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in-left">
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">Our Beginning</p>
                    <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">A Passion for Quality</h2>
                    <div class="text-slate-600 text-[15px] leading-relaxed font-light space-y-4">
                        <p>Every great journey begins with a single step. Ours started with a simple belief: that everyone deserves access to beautifully crafted, high-quality products that stand the test of time.</p>
                        <p>Founded with a vision to bridge the gap between exceptional craftsmanship and everyday accessibility, we set out to create something special.</p>
                    </div>
                </div>
                <div class="fade-in-right image-zoom aspect-[4/5] shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000" alt="" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
@endforelse

{{-- Team Section --}}
@if($team->count() > 0)
<section class="py-24 bg-slate-900 text-white">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="fade-in-section text-center mb-16">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">The People</p>
            <h2 class="text-4xl md:text-5xl font-serif mb-4">Meet Our Team</h2>
            <p class="text-slate-400 text-lg font-light">The passionate people behind our brand</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($team as $member)
            <div class="fade-in-section team-member text-center">
                <div class="team-image relative mb-6 mx-auto w-48 h-48 overflow-hidden">
                    @if($member->image_url)
                    <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                        <span class="text-5xl font-serif text-slate-600">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    </div>
                    @endif
                </div>
                <h3 class="text-lg font-serif mb-1">{{ $member->name }}</h3>
                <p class="text-[11px] tracking-[0.15em] uppercase text-slate-400 mb-3">{{ $member->position }}</p>
                @if($member->bio)
                <p class="text-slate-500 text-[13px] font-light mb-4">{{ Str::limit($member->bio, 100) }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-24 bg-white">
    <div class="fade-in-section max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-6">Experience Our Craftsmanship</h2>
        <p class="text-lg text-slate-500 font-light mb-10">Discover our collection and see the quality for yourself.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase bg-slate-900 text-white px-10 py-4 hover:bg-slate-800 transition-all duration-300">
                Shop Now
            </a>
            <a href="{{ route('pages.process') }}" class="btn-animate relative text-[11px] font-bold tracking-[0.2em] uppercase border border-slate-300 text-slate-700 px-10 py-4 z-10 hover:border-slate-900">
                Our Process
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
