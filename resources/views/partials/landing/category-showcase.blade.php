{{-- Category Showcase Component --}}
@php
$categories = [
    ['id' => 'c1', 'name' => 'Footwear', 'image' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?auto=format&fit=crop&q=80&w=800', 'itemCount' => 42],
    ['id' => 'c2', 'name' => 'Travel Bags', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=800', 'itemCount' => 18],
    ['id' => 'c3', 'name' => 'Home Goods', 'image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&q=80&w=800', 'itemCount' => 56],
    ['id' => 'c4', 'name' => 'New Arrivals', 'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800', 'itemCount' => 12],
];
@endphp

<section id="categories" class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-px bg-slate-200 border border-slate-200">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category['name']]) }}" class="group relative h-[600px] overflow-hidden bg-white cursor-pointer block">
                    <img 
                        src="{{ $category['image'] }}" 
                        alt="{{ $category['name'] }}" 
                        class="w-full h-full object-cover transition-opacity duration-700 group-hover:opacity-90"
                    />
                    <div class="absolute inset-x-0 bottom-0 p-10 bg-gradient-to-t from-black/40 to-transparent">
                        <h4 class="text-2xl font-serif text-white tracking-wide mb-2">{{ $category['name'] }}</h4>
                        <div class="category-line h-[2px] bg-white"></div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
