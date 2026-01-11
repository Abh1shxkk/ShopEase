@extends('layouts.admin')

@section('title', 'Blog Comments')
@section('page-title', 'Blog Comments')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200">
        <h2 class="text-lg font-semibold text-slate-800">Comments</h2>
        <p class="text-sm text-slate-500">Moderate blog comments</p>
    </div>

    <div class="divide-y divide-slate-200">
        @forelse($comments as $comment)
        <div class="p-6 {{ !$comment->is_approved ? 'bg-yellow-50' : '' }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">{{ $comment->author_name }}</p>
                            <p class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }} on 
                                <a href="{{ route('blog.show', $comment->post->slug) }}" class="text-blue-600 hover:underline" target="_blank">
                                    {{ Str::limit($comment->post->title, 30) }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm ml-13">{{ $comment->content }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if(!$comment->is_approved)
                    <form action="{{ route('admin.blog.comments.approve', $comment) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition">
                            Approve
                        </button>
                    </form>
                    @else
                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Approved</span>
                    @endif
                    <form action="{{ route('admin.blog.comments.delete', $comment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this comment?')">
                        @csrf @method('DELETE')
                        <button class="p-2 text-slate-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-slate-500">
            <i class="fas fa-comments text-4xl mb-4 text-slate-300"></i>
            <p>No comments yet</p>
        </div>
        @endforelse
    </div>

    @if($comments->hasPages())
    <div class="p-6 border-t border-slate-200">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection
