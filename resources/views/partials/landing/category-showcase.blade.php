{{-- Category Showcase Component --}}
@php
use App\Models\FeaturedSection;
$showcaseCategories = FeaturedSection::active()->byType('category_showcase')->get();

// Fallback if no sections in database
if ($showcaseCategories->isEmpty()) {
    $showcaseCategories = collect([
        (object)['title' => 'Women', 'link' => route('shop.index', ['gender' => 'women']), 'image_url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800'],
        (object)['title' => 'Men', 'link' => route('shop.index', ['gender' => 'men']), 'image_url' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?auto=format&fit=crop&q=80&w=800'],
        (object)['title' => 'Accessories', 'link' => route('shop.index', ['category' => 'Accessories']), 'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=800'],
        (object)['title' => 'New Arrivals', 'link' => route('shop.index', ['sort' => 'newest']), 'image_url' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800'],
    ]);
}
@endphp

<section id="categories" class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($showcaseCategories->count(), 4) }} gap-px bg-slate-200 border border-slate-200">
            @foreach($showcaseCategories as $category)
                <a href="{{ $category->link ?? '/shop' }}" class="group relative h-[600px] overflow-hidden bg-white cursor-pointer block">
                    <img 
                        src="{{ $category->image_url }}" 
                        alt="{{ $category->title }}" 
                        class="w-full h-full object-cover transition-opacity duration-700 group-hover:opacity-90"
                    />
                    <div class="absolute inset-x-0 bottom-0 p-10 bg-gradient-to-t from-black/40 to-transparent">
                        <h4 class="text-2xl font-serif text-white tracking-wide mb-2">{{ $category->title }}</h4>
                        <div class="category-line h-[2px] bg-white"></div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
