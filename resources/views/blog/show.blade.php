@extends('layouts.shop')

@section('title', $post->meta_title ?? $post->title)
@section('description', $post->meta_description ?? $post->excerpt)

@section('content')
{{-- Hero --}}
<article>
    <header class="py-16 md:py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-6 text-center">
            @if($post->category)
            <a href="{{ route('blog.category', $post->category->slug) }}" class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-500 hover:text-slate-700 transition mb-4 inline-block">
                {{ $post->category->name }}
            </a>
            @endif
            <h1 class="text-3xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">{{ $post->title }}</h1>
            <div class="flex items-center justify-center gap-4 text-sm text-slate-500">
                @if($post->author)
                <span>By {{ $post->author->name }}</span>
                <span>•</span>
                @endif
                <span>{{ $post->formatted_date }}</span>
                <span>•</span>
                <span>{{ $post->reading_time }} min read</span>
            </div>
        </div>
    </header>

    {{-- Featured Image --}}
    @if($post->featured_image)
    <div class="max-w-5xl mx-auto px-6 -mt-8">
        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full aspect-[21/9] object-cover rounded-xl shadow-lg">
    </div>
    @endif

    {{-- Content --}}
    <div class="py-16">
        <div class="max-w-3xl mx-auto px-6">
            <div class="prose prose-lg prose-slate max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>

            {{-- Tags --}}
            @if($post->tags && count($post->tags))
            <div class="mt-12 pt-8 border-t border-slate-200">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="px-3 py-1 bg-slate-100 text-slate-600 text-sm rounded-full hover:bg-slate-200 transition">
                        #{{ $tag }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Share --}}
            <div class="mt-8 pt-8 border-t border-slate-200">
                <p class="text-sm font-medium text-slate-700 mb-3">Share this article</p>
                <div class="flex gap-3">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-200 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-200 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-200 transition">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link copied!')" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-200 transition">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Comments --}}
    @if($post->allow_comments)
    <section class="py-16 bg-slate-50">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-2xl font-serif text-slate-900 mb-8">Comments ({{ $post->approvedComments->count() }})</h2>

            {{-- Comment Form --}}
            <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
                <h3 class="font-semibold text-slate-800 mb-4">Leave a comment</h3>
                <form action="{{ route('blog.comment', $post) }}" method="POST">
                    @csrf
                    @guest
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="guest_name" placeholder="Your name *" required
                            class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                        <input type="email" name="guest_email" placeholder="Your email *" required
                            class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                    </div>
                    @endguest
                    <textarea name="content" rows="4" placeholder="Write your comment..." required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent mb-4"></textarea>
                    <button type="submit" class="bg-slate-900 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                        Post Comment
                    </button>
                </form>
            </div>

            {{-- Comments List --}}
            <div class="space-y-6">
                @foreach($post->approvedComments as $comment)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-medium text-slate-800">{{ $comment->author_name }}</span>
                                <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 text-sm">{{ $comment->content }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</article>

{{-- Related Posts --}}
@if($relatedPosts->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-serif text-slate-900 mb-8">Related Articles</h2>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="group">
                <div class="aspect-[4/3] overflow-hidden rounded-xl mb-4">
                    <img src="{{ $related->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&q=80&w=800' }}" 
                        alt="{{ $related->title }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">{{ $related->formatted_date }}</p>
                <h3 class="text-lg font-serif text-slate-900 group-hover:text-slate-600 transition">{{ $related->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
