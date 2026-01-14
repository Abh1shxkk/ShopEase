{{-- Heritage Narrative Component --}}
@php
use App\Models\FeaturedSection;
$heritage = FeaturedSection::active()->byType('heritage')->first();

// Fallback defaults
$heritageTitle = $heritage?->title ?? 'Sustainability Through Rediscovery.';
$heritageDescription = $heritage?->description ?? "We don't just make products; we preserve cultures. By sourcing kilims that are up to 100 years old, we reduce waste while honoring the geometric languages of nomadic tribes.";
$heritageImage = $heritage?->image_url ?? 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=1000';
$heritageLink = $heritage?->link ?? '#';
$heritageLinkText = $heritage?->link_text ?? 'Read Our Ethos';
@endphp

<section class="py-32 bg-slate-50 overflow-hidden relative">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center gap-20">
        {{-- Text Content --}}
        <div class="flex-1 space-y-8 reveal-left">
            {{-- Quote Icon --}}
            <svg class="w-12 h-12 text-slate-200 bounce-subtle" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
            </svg>
            
            <h2 class="text-5xl md:text-7xl font-serif leading-tight">
                @php
                    $parts = explode(' ', $heritageTitle);
                    $lastWord = array_pop($parts);
                    echo implode(' ', $parts) . ' <span class="italic">' . $lastWord . '</span>';
                @endphp
            </h2>
            
            <p class="text-lg text-slate-500 font-light leading-relaxed max-w-lg">
                {{ $heritageDescription }}
            </p>
            
            <div class="pt-6">
                <a href="{{ $heritageLink }}" class="text-[11px] font-bold tracking-[0.2em] uppercase border-b border-slate-900 pb-2 hover:text-slate-500 hover:border-slate-500 transition-all btn-arrow">
                    {{ $heritageLinkText }}
                    <svg class="w-4 h-4 arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Image Section --}}
        <div class="flex-1 relative reveal-right">
            <div class="aspect-[4/5] bg-slate-200 overflow-hidden shadow-2xl curtain-reveal">
                <img 
                    src="{{ $heritageImage }}" 
                    alt="Heritage" 
                    class="w-full h-full object-cover image-zoom-slow" 
                />
            </div>
            
            {{-- Floating Detail Image --}}
            <div class="absolute -bottom-10 -left-10 w-48 h-64 bg-white p-2 shadow-xl hidden lg:block float-gentle">
                <img 
                    src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&q=80&w=300" 
                    class="w-full h-full object-cover" 
                    alt="Detail"
                />
                <p class="text-[8px] mt-2 tracking-widest uppercase font-bold text-center">Detail 04 / Indigo Dye</p>
            </div>
        </div>
    </div>
</section>
