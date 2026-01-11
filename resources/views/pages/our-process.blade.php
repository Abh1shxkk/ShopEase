@extends('layouts.shop')

@section('title', 'Our Process')
@section('description', 'From concept to creation, discover how we craft each piece with care and precision.')

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
    
    /* Smooth reveal for text */
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
    
    /* Step number animation */
    .step-number {
        position: relative;
        overflow: hidden;
    }
    .step-number::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: white;
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }
    .is-visible .step-number::after {
        transform: translateX(0);
    }
    
    /* Card hover effect */
    .card-hover {
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
</style>

{{-- Hero Section --}}
<section class="relative h-[60vh] min-h-[500px] overflow-hidden">
    <div class="absolute inset-0 hero-parallax" style="background-image: url('https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&q=80&w=2000')">
        <div class="absolute inset-0 bg-slate-900/60"></div>
    </div>
    <div class="relative z-10 h-full flex items-center justify-center text-center px-6">
        <div class="max-w-3xl">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-white/70 mb-6">How We Work</p>
            <h1 class="text-5xl md:text-7xl font-serif text-white mb-6">Our Process</h1>
            <p class="text-lg text-white/80 font-light max-w-xl mx-auto">
                From concept to creation, discover how we craft each piece with care and precision.
            </p>
        </div>
    </div>
</section>

{{-- Process Steps --}}
<section class="py-24">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        @if($steps->count() > 0)
        <div class="space-y-32">
            @foreach($steps as $index => $step)
            <div class="fade-in-section relative">
                {{-- Step Number --}}
                <div class="hidden lg:flex absolute -left-4 top-0 w-20 h-20 bg-slate-900 text-white items-center justify-center step-number">
                    <span class="text-3xl font-serif">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="grid lg:grid-cols-2 gap-16 items-center lg:pl-24">
                    {{-- Content --}}
                    <div class="{{ $index % 2 === 0 ? 'lg:order-1' : 'lg:order-2' }}">
                        <div class="flex items-center gap-4 mb-6 lg:hidden">
                            <span class="w-12 h-12 bg-slate-900 text-white flex items-center justify-center text-xl font-serif">{{ $step->step_number }}</span>
                        </div>
                        <h2 class="text-reveal text-3xl md:text-4xl font-serif text-slate-900 mb-6">{{ $step->title }}</h2>
                        <div class="text-reveal text-slate-600 text-[15px] leading-relaxed font-light space-y-4">
                            {!! nl2br(e($step->description)) !!}
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="{{ $index % 2 === 0 ? 'lg:order-2' : 'lg:order-1' }}">
                        <div class="image-zoom aspect-[4/3] shadow-xl">
                            @if($step->image_url)
                            <img src="{{ $step->image_url }}" alt="{{ $step->title }}" class="w-full h-full object-cover">
                            @else
                            @php
                                $defaultImages = [
                                    'https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000',
                                    'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?auto=format&fit=crop&q=80&w=1000',
                                    'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&q=80&w=1000',
                                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=1000',
                                    'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&q=80&w=1000',
                                ];
                            @endphp
                            <img src="{{ $defaultImages[$index % count($defaultImages)] }}" alt="{{ $step->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Connector Line --}}
                @if(!$loop->last)
                <div class="hidden lg:block absolute left-6 top-full h-32 w-px bg-slate-200"></div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-slate-500 text-lg">Our process details are coming soon...</p>
        </div>
        @endif
    </div>
</section>

{{-- Quality Promise --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="fade-in-section text-center mb-16">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 mb-4">Our Commitment</p>
            <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-4">Our Quality Promise</h2>
            <p class="text-lg text-slate-500 font-light max-w-2xl mx-auto">Every step of our process is designed to deliver exceptional quality.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="fade-in-section card-hover bg-white p-10 text-center">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-serif text-slate-900 mb-3">Quality Materials</h3>
                <p class="text-slate-500 text-[14px] font-light">We source only the finest materials from trusted suppliers around the world.</p>
            </div>
            <div class="fade-in-section card-hover bg-white p-10 text-center">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-serif text-slate-900 mb-3">Handcrafted Care</h3>
                <p class="text-slate-500 text-[14px] font-light">Each piece is crafted with attention to detail by skilled artisans.</p>
            </div>
            <div class="fade-in-section card-hover bg-white p-10 text-center">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-serif text-slate-900 mb-3">Timeless Design</h3>
                <p class="text-slate-500 text-[14px] font-light">Our designs are created to stand the test of time, both in style and durability.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 hero-parallax" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000')">
        <div class="absolute inset-0 bg-slate-900/80"></div>
    </div>
    <div class="fade-in-section relative z-10 max-w-4xl mx-auto px-6 text-center text-white">
        <h2 class="text-4xl md:text-5xl font-serif mb-6">Ready to Experience the Difference?</h2>
        <p class="text-lg text-white/70 font-light mb-10">Explore our collection and discover pieces crafted with passion.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase bg-white text-slate-900 px-10 py-4 hover:bg-slate-100 transition-all duration-300">
                Shop Collection
            </a>
            <a href="{{ route('pages.story') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase border border-white/50 text-white px-10 py-4 hover:bg-white/10 transition-all duration-300">
                Our Story
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in-section');
    
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
