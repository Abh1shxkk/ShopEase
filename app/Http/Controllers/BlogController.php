<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()->with(['category', 'author']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = BlogCategory::where('is_active', true)
            ->withCount('publishedPosts')
            ->orderBy('sort_order')
            ->get();
        $featuredPosts = BlogPost::published()->featured()->latest('published_at')->take(3)->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    public function show($slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'approvedComments.user', 'approvedComments.replies'])
            ->firstOrFail();

        $post->incrementViews();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where(function($q) use ($post) {
                if ($post->category_id) {
                    $q->where('category_id', $post->category_id);
                }
                if ($post->tags) {
                    foreach ($post->tags as $tag) {
                        $q->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $posts = BlogPost::published()
            ->where('category_id', $category->id)
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);
        $categories = BlogCategory::where('is_active', true)
            ->withCount('publishedPosts')
            ->orderBy('sort_order')
            ->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    public function storeComment(Request $request, BlogPost $post)
    {
        if (!$post->allow_comments) {
            return back()->with('error', 'Comments are disabled for this post.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
            'guest_name' => 'required_without:user_id|string|max:255',
            'guest_email' => 'required_without:user_id|email|max:255',
        ]);

        $validated['post_id'] = $post->id;
        $validated['user_id'] = auth()->id();
        $validated['is_approved'] = auth()->check(); // Auto-approve for logged in users

        BlogComment::create($validated);

        return back()->with('success', 'Comment submitted successfully!');
    }
}
