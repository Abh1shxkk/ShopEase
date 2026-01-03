{{-- Artisan Spotlight Component --}}
<section class="relative h-[80vh] overflow-hidden flex items-center justify-center">
    {{-- Background Image with Parallax Effect --}}
    <div class="absolute inset-0 bg-fixed bg-cover bg-center grayscale" 
         style="background-image: url('https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&q=80&w=1600')">
        <div class="absolute inset-0 bg-slate-900/40"></div>
    </div>
    
    {{-- Content Card --}}
    <div class="relative z-10 max-w-2xl text-center bg-white p-12 md:p-20 shadow-2xl">
        <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-8 text-slate-400">Our Craftsmanship</h4>
        
        <h2 class="text-4xl md:text-5xl font-serif mb-8 leading-tight">
            Every Thread Tells a Forgotten Tale
        </h2>
        
        <p class="text-slate-600 text-[14px] leading-relaxed mb-12 font-light italic">
            "We traverse the globe to find vintage textiles that deserve a second life. Our artisans in Istanbul transform these pieces of history into modern icons of luxury."
        </p>
        
        <button class="text-[11px] font-bold tracking-[0.2em] uppercase border border-slate-900 px-10 py-4 hover:bg-slate-900 hover:text-white transition-all">
            Discover the Process
        </button>
    </div>
</section>
