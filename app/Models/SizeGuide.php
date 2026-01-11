<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SizeGuide extends Model
{
    protected $fillable = ['category_id', 'name', 'type', 'measurements', 'fit_tips', 'is_active'];

    protected $casts = [
        'measurements' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function getForCategory(?int $categoryId): ?self
    {
        return static::where('is_active', true)
            ->where(function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId)
                  ->orWhereNull('category_id');
            })
            ->orderByRaw('category_id IS NULL')
            ->first();
    }
}
