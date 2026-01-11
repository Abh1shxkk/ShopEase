@extends('layouts.shop')

@section('title', $category->name . ' - Journal')
@section('description', $category->description)

@section('content')
{{-- Hero --}}
<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <a href="{{ route('blog.index') }}" class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-500 hover:text-slate-700 transition mb-4 inline-block">
            ← Back to Journal
        </a>
        <h1 class="text-4xl md:text-5xl font-serif text-slate-900 mb-4">{{ $category->name }}</h1>
        @if($category->description)
        <p class="text-lg text-slate-500 font-light max-w-2xl mx-auto">{{ $category->description }}</p>
        @endif
    </div>
</section>

{{-- Posts --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        @if($posts->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <article class="group">
                <a href="{{ route('blog.show', $post->slug) }}">
                    <div class="aspect-[4/3] overflow-hidden rounded-xl mb-4">
                        <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&q=80&w=800' }}" 
                            alt="{{ $post->title }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                </a>
                <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">{{ $post->formatted_date }}</p>
                <a href="{{ route('blog.show', $post->slug) }}">
                    <h3 class="text-xl font-serif text-slate-900 mb-2 group-hover:text-slate-600 transition">{{ $post->title }}</h3>
                </a>
                @if($post->excerpt)
                <p class="text-sm text-slate-500 font-light line-clamp-2">{{ $post->excerpt }}</p>
                @endif
            </article>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-newspaper text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-500">No articles in this category yet.</p>
        </div>
        @endif
    </div>
</section>

{{-- Other Categories --}}
@if($categories->count() > 1)
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-serif text-slate-900 mb-8 text-center">Explore Other Categories</h2>
        <div class="flex flex-wrap justify-center gap-4">
            @foreach($categories as $cat)
            @if($cat->id !== $category->id)
            <a href="{{ route('blog.category', $cat->slug) }}" class="px-6 py-3 bg-white rounded-full text-slate-700 hover:bg-slate-900 hover:text-white transition shadow-sm">
                {{ $cat->name }} ({{ $cat->published_posts_count }})
            </a>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
