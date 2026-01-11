<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'gallery', 'tags', 'status', 'published_at',
        'is_featured', 'allow_comments', 'views', 'meta_title', 
        'meta_description', 'reading_time'
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->reading_time)) {
                $post->reading_time = ceil(str_word_count(strip_tags($post->content)) / 200);
            }
        });
        static::updating(function ($post) {
            $post->reading_time = ceil(str_word_count(strip_tags($post->content)) / 200);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'post_id');
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'post_id')
            ->where('is_approved', true)
            ->whereNull('parent_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('M d, Y') : $this->created_at->format('M d, Y');
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
