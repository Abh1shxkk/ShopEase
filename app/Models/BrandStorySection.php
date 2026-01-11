<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandStorySection extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'content', 'image', 'image_position',
        'button_text', 'button_link', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
