{{-- Artisan Spotlight Component --}}
@php
use App\Models\ProcessStep;
$hasProcessSteps = ProcessStep::where('is_active', true)->exists();
@endphp

<section class="relative h-[80vh] overflow-hidden flex items-center justify-center">
    {{-- Background Image with Parallax Effect --}}
    <div class="absolute inset-0 bg-fixed bg-cover bg-center grayscale parallax-container" 
         style="background-image: url('https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&q=80&w=1600')">
        <div class="absolute inset-0 bg-slate-900/40"></div>
    </div>
    
    {{-- Content Card --}}
    <div class="relative z-10 max-w-2xl text-center bg-white p-12 md:p-20 shadow-2xl reveal-scale">
        <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-8 text-slate-400">Our Craftsmanship</h4>
        
        <h2 class="text-4xl md:text-5xl font-serif mb-8 leading-tight">
            Every Thread Tells a Forgotten Tale
        </h2>
        
        <p class="text-slate-600 text-[14px] leading-relaxed mb-12 font-light italic">
            "We traverse the globe to find vintage textiles that deserve a second life. Our artisans in Istanbul transform these pieces of history into modern icons of luxury."
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('pages.process') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase border border-slate-900 px-10 py-4 hover:bg-slate-900 hover:text-white transition-all btn-ripple btn-magnetic">
                Discover the Process
            </a>
            <a href="{{ route('pages.story') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase border border-slate-300 text-slate-600 px-10 py-4 hover:border-slate-900 hover:text-slate-900 transition-all btn-ripple btn-magnetic">
                Our Story
            </a>
        </div>
    </div>
</section>
