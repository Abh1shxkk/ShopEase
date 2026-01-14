{{-- Instagram Feed Component --}}
@php
$instagramImages = [
    'https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=400',
];
@endphp

<section class="py-24 border-t border-slate-100">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        {{-- Section Header --}}
        <div class="mb-12 text-center reveal">
            <h3 class="text-xl font-serif tracking-widest uppercase text-slate-800">
                Follow Us On Instagram @shopease
            </h3>
        </div>

        {{-- Instagram Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 stagger-container">
            @foreach($instagramImages as $index => $image)
                <a href="#" class="aspect-square bg-slate-50 overflow-hidden border border-slate-100 block stagger-item group">
                    <img 
                        src="{{ $image }}" 
                        alt="Instagram feed item {{ $index + 1 }}" 
                        class="w-full h-full object-cover instagram-image group-hover:scale-110 transition-transform duration-700"
                    />
                </a>
            @endforeach
        </div>
    </div>
</section>
