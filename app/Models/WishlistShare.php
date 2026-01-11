<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WishlistShare extends Model
{
    protected $fillable = ['user_id', 'share_token', 'title', 'description', 'is_public', 'view_count', 'expires_at'];

    protected $casts = [
        'is_public' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getWishlistItems()
    {
        return Wishlist::where('user_id', $this->user_id)->with('product')->get();
    }

    public static function createForUser(int $userId, array $data = []): self
    {
        return static::create([
            'user_id' => $userId,
            'share_token' => Str::random(32),
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'is_public' => $data['is_public'] ?? true,
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getShareUrl(): string
    {
        return route('wishlist.shared', $this->share_token);
    }
}
