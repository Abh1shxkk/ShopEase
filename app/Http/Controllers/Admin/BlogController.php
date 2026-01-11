<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // Posts
    public function posts()
    {
        $posts = BlogPost::with(['category', 'author'])
            ->latest()
            ->paginate(15);
        return view('admin.blog.posts.index', compact('posts'));
    }

    public function createPost()
    {
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.blog.posts.form', compact('categories'));
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'category_id' => 'nullable|exists:blog_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['allow_comments'] = $request->boolean('allow_comments', true);
        
        if ($validated['status'] === 'published' && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.posts')->with('success', 'Post created successfully!');
    }

    public function editPost(BlogPost $post)
    {
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.blog.posts.form', compact('post', 'categories'));
    }

    public function updatePost(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'category_id' => 'nullable|exists:blog_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['allow_comments'] = $request->boolean('allow_comments', true);

        if ($validated['status'] === 'published' && !$post->published_at && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.blog.posts')->with('success', 'Post updated successfully!');
    }

    public function deletePost(BlogPost $post)
    {
        $post->delete();
        return redirect()->route('admin.blog.posts')->with('success', 'Post deleted successfully!');
    }

    // Categories
    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->orderBy('sort_order')->get();
        return view('admin.blog.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.blog.categories.form');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        BlogCategory::create($validated);

        return redirect()->route('admin.blog.categories')->with('success', 'Category created successfully!');
    }

    public function editCategory(BlogCategory $category)
    {
        return view('admin.blog.categories.form', compact('category'));
    }

    public function updateCategory(Request $request, BlogCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('admin.blog.categories')->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(BlogCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.blog.categories')->with('success', 'Category deleted successfully!');
    }

    // Comments
    public function comments()
    {
        $comments = BlogComment::with(['post', 'user'])
            ->latest()
            ->paginate(20);
        return view('admin.blog.comments.index', compact('comments'));
    }

    public function approveComment(BlogComment $comment)
    {
        $comment->update(['is_approved' => true]);
        return back()->with('success', 'Comment approved!');
    }

    public function deleteComment(BlogComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted!');
    }
}
