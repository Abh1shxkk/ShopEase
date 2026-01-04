<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}
