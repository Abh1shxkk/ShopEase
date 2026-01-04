<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedSection extends Model
{
    protected $fillable = [
        'name', 'title', 'description', 'image', 'link', 'link_text',
        'section_type', 'extra_data', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'extra_data' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('section_type', $type);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}
