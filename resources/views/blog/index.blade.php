@extends('layouts.shop')

@section('title', 'Journal')
@section('description', 'Stories, insights, and inspiration from our world.')

@section('content')
{{-- Hero --}}
<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-serif text-slate-900 mb-4">Journal</h1>
        <p class="text-lg text-slate-500 font-light max-w-2xl mx-auto">Stories, insights, and inspiration from our world.</p>
    </div>
</section>

{{-- Featured Posts --}}
@if($featuredPosts->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-serif text-slate-900 mb-8">Featured Stories</h2>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($featuredPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group">
                <div class="aspect-[4/3] overflow-hidden mb-4">
                    <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&q=80&w=800' }}" 
                        alt="{{ $post->title }}" 
                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                </div>
                <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">
                    {{ $post->formatted_date }}
                </p>
                <h3 class="text-xl font-serif text-slate-900 group-hover:text-slate-600 transition">{{ $post->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Main Content --}}
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-4 gap-12">
            {{-- Posts --}}
            <div class="lg:col-span-3">
                {{-- Search --}}
                <form action="{{ route('blog.index') }}" method="GET" class="mb-8">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..."
                            class="w-full px-4 py-3 pr-12 border border-slate-200 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent bg-white">
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                @if($posts->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                        <a href="{{ route('blog.show', $post->slug) }}">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&q=80&w=800' }}" 
                                    alt="{{ $post->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                        </a>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-3">
                                @if($post->category)
                                <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">{{ $post->category->name }}</span>
                                <span class="text-slate-300">•</span>
                                @endif
                                <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">{{ $post->formatted_date }}</span>
                            </div>
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <h3 class="text-lg font-serif text-slate-900 mb-2 group-hover:text-slate-600 transition line-clamp-2">{{ $post->title }}</h3>
                            </a>
                            @if($post->excerpt)
                            <p class="text-sm text-slate-500 font-light line-clamp-2">{{ $post->excerpt }}</p>
                            @endif
                            <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $post->reading_time }} min read</span>
                                <span>{{ number_format($post->views) }} views</span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
                @else
                <div class="text-center py-16">
                    <i class="fas fa-newspaper text-5xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500">No articles found.</p>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                {{-- Categories --}}
                <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
                    <h3 class="font-semibold text-slate-800 mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('blog.index') }}" class="flex items-center justify-between text-sm {{ !request('category') ? 'text-slate-900 font-medium' : 'text-slate-600 hover:text-slate-900' }} transition">
                                <span>All Posts</span>
                                <span class="text-slate-400">{{ $posts->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between text-sm {{ request('category') === $category->slug ? 'text-slate-900 font-medium' : 'text-slate-600 hover:text-slate-900' }} transition">
                                <span>{{ $category->name }}</span>
                                <span class="text-slate-400">{{ $category->published_posts_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="bg-slate-900 rounded-xl p-6 text-white">
                    <h3 class="font-semibold mb-2">Subscribe</h3>
                    <p class="text-sm text-slate-400 mb-4">Get the latest stories delivered to your inbox.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Your email" required
                            class="w-full px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-white focus:border-transparent mb-3">
                        <button type="submit" class="w-full bg-white text-slate-900 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 transition">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
