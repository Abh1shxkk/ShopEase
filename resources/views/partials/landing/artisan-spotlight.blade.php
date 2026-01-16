{{-- Artisan Spotlight Component - With Moving Background --}}
@php
use App\Models\ProcessStep;
$hasProcessSteps = ProcessStep::where('is_active', true)->exists();

$craftingSteps = [
    ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'title' => 'Source', 'desc' => 'Finding rare textiles'],
    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Curate', 'desc' => 'Selecting the finest'],
    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Craft', 'desc' => 'Artisan excellence'],
    ['icon' => 'M5 13l4 4L19 7', 'title' => 'Deliver', 'desc' => 'To your doorstep'],
];
@endphp

<section class="relative min-h-[85vh] overflow-hidden flex items-center justify-center py-20">
    {{-- Background Image with Vertical Movement --}}
    <div class="absolute inset-0 moving-bg" 
         style="background-image: url('https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&q=80&w=1600'); 
                background-size: cover; 
                background-repeat: repeat-y;
                height: 200%;">
    </div>
    
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/70 via-slate-900/50 to-slate-900/70"></div>
    
    {{-- Content --}}
    <div class="relative z-10 w-full max-w-6xl mx-auto px-6 md:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left Content Card --}}
            <div class="bg-white/95 backdrop-blur-sm p-8 md:p-14 shadow-2xl reveal-scale">
                <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6 text-slate-400">Our Craftsmanship</h4>
                
                <h2 class="text-3xl md:text-4xl font-serif mb-6 leading-tight">
                    Every Thread Tells a <span class="italic text-amber-700">Forgotten</span> Tale
                </h2>
                
                <p class="text-slate-600 text-sm leading-relaxed mb-8 font-light">
                    "We traverse the globe to find vintage textiles that deserve a second life. Our artisans in Istanbul transform these pieces of history into modern icons of luxury."
                </p>

                {{-- Process Steps --}}
                <div class="grid grid-cols-2 gap-3 mb-10 stagger-container">
                    @foreach($craftingSteps as $step)
                    <div class="stagger-item p-3 border border-slate-100 hover:border-amber-200 transition-colors group">
                        <svg class="w-4 h-4 text-amber-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/>
                        </svg>
                        <p class="text-xs font-bold tracking-wide">{{ $step['title'] }}</p>
                        <p class="text-[10px] text-slate-400">{{ $step['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('pages.process') }}" 
                       class="text-center text-[11px] font-bold tracking-[0.2em] uppercase border-2 border-slate-900 px-8 py-3 
                              hover:bg-slate-900 hover:text-white transition-all duration-300 btn-ripple">
                        Discover the Process
                    </a>
                    <a href="{{ route('pages.story') }}" 
                       class="text-center text-[11px] font-bold tracking-[0.2em] uppercase border-2 border-slate-200 text-slate-500 px-8 py-3 
                              hover:border-amber-400 hover:text-amber-700 transition-all duration-300 btn-ripple">
                        Our Story
                    </a>
                </div>
            </div>

            {{-- Right Side - Image Grid --}}
            <div class="hidden lg:grid grid-cols-2 gap-3 reveal-right">
                <div class="col-span-2 aspect-video overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=800" 
                        alt="Craftsmanship" 
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                    />
                </div>
                <div class="aspect-square overflow-hidden group">
                    <img 
                        src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&q=80&w=400" 
                        alt="Detail work" 
                        class="w-full h-full object-cover image-zoom"
                    />
                </div>
                <div class="aspect-square overflow-hidden group">
                    <img 
                        src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=400" 
                        alt="Material" 
                        class="w-full h-full object-cover image-zoom"
                    />
                </div>
            </div>
        </div>
    </div>
</section>
