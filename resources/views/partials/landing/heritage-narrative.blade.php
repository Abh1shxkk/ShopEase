{{-- Heritage Narrative Component - Premium Enhanced --}}
@php
use App\Models\FeaturedSection;
$heritage = FeaturedSection::active()->byType('heritage')->first();

// Fallback defaults
$heritageTitle = $heritage?->title ?? 'Sustainability Through Rediscovery.';
$heritageDescription = $heritage?->description ?? "We don't just make products; we preserve cultures. By sourcing kilims that are up to 100 years old, we reduce waste while honoring the geometric languages of nomadic tribes.";
$heritageImage = $heritage?->image_url ?? 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=1000';
$heritageLink = $heritage?->link ?? '#';
$heritageLinkText = $heritage?->link_text ?? 'Read Our Ethos';

// Stats data
$stats = [
    ['value' => '100+', 'label' => 'Years of Heritage'],
    ['value' => '50+', 'label' => 'Artisan Partners'],
    ['value' => '10K+', 'label' => 'Happy Customers'],
];
@endphp

<section class="py-32 bg-slate-50 overflow-hidden relative section-parallax">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-30 pattern-dots"></div>
    
    {{-- Floating Decorative Elements --}}
    <div class="absolute top-20 right-10 w-40 h-40 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 blur-3xl opacity-40 floating-detail"></div>
    <div class="absolute bottom-20 left-10 w-60 h-60 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 blur-3xl opacity-30"></div>

    <div class="max-w-[1440px] mx-auto px-6 md:px-12 flex flex-col lg:flex-row items-center gap-20 relative">
        {{-- Text Content --}}
        <div class="flex-1 space-y-8 reveal-left">
            {{-- Quote Icon with Animation --}}
            <div class="inline-block">
                <svg class="w-14 h-14 text-amber-300/80 bounce-subtle" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                </svg>
            </div>
            
            <h2 class="text-5xl md:text-6xl lg:text-7xl font-serif leading-tight">
                @php
                    $parts = explode(' ', $heritageTitle);
                    $lastWord = array_pop($parts);
                    echo implode(' ', $parts) . ' <span class="italic text-amber-700">' . $lastWord . '</span>';
                @endphp
            </h2>
            
            <p class="text-lg md:text-xl text-slate-500 font-light leading-relaxed max-w-xl">
                {{ $heritageDescription }}
            </p>

            {{-- Stats Row --}}
            <div class="flex flex-wrap gap-12 pt-6 stagger-container">
                @foreach($stats as $index => $stat)
                <div class="stagger-item">
                    <div class="text-3xl md:text-4xl font-serif text-slate-900 count-up" data-target="{{ intval(preg_replace('/\D/', '', $stat['value'])) }}" data-suffix="{{ preg_replace('/[0-9]/', '', $stat['value']) }}">0</div>
                    <div class="text-xs tracking-widest uppercase text-slate-400 mt-2">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
            
            <div class="pt-8">
                <a href="{{ $heritageLink }}" class="inline-flex items-center gap-3 text-[11px] font-bold tracking-[0.2em] uppercase group">
                    <span class="border-b-2 border-slate-900 pb-2 group-hover:text-amber-700 group-hover:border-amber-700 transition-all duration-300">
                        {{ $heritageLinkText }}
                    </span>
                    <svg class="w-5 h-5 arrow-icon transition-transform duration-300 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Image Section with Mockup Effect --}}
        <div class="flex-1 relative reveal-right">
            {{-- Main Image with Frame --}}
            <div class="relative mockup-frame">
                <div class="aspect-[4/5] bg-slate-200 overflow-hidden shadow-2xl curtain-reveal">
                    <img 
                        src="{{ $heritageImage }}" 
                        alt="Heritage" 
                        class="w-full h-full object-cover image-zoom-slow mockup-inner" 
                    />
                </div>
                
                {{-- Decorative Frame Corner --}}
                <div class="absolute -top-4 -right-4 w-full h-full border-2 border-amber-200/50 -z-10"></div>
            </div>
            
            {{-- Floating Detail Image with Animation --}}
            <div class="absolute -bottom-8 -left-8 w-44 h-56 bg-white p-2 shadow-xl hidden lg:block float-gentle tilt-card">
                <div class="relative h-full overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&q=80&w=300" 
                        class="w-full h-full object-cover tilt-card-inner" 
                        alt="Detail"
                    />
                </div>
                <p class="text-[8px] mt-2 tracking-widest uppercase font-bold text-center text-slate-600">Detail 04 / Indigo Dye</p>
            </div>

            {{-- Second Floating Element --}}
            <div class="absolute -top-6 -right-6 w-32 h-40 bg-white p-2 shadow-lg hidden lg:block float-gentle" style="animation-delay: 2s;">
                <img 
                    src="https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=200" 
                    class="w-full h-full object-cover" 
                    alt="Texture detail"
                />
            </div>

            {{-- Badge Element --}}
            <div class="absolute bottom-1/4 -right-12 hidden xl:flex items-center gap-3 bg-white px-6 py-4 shadow-lg moving-badge">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-wide">Certified</p>
                    <p class="text-[10px] text-slate-400">Artisan Made</p>
                </div>
            </div>
        </div>
    </div>
</section>
