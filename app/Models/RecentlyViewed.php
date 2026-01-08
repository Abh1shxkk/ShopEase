<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecentlyViewed extends Model
{
    protected $table = 'recently_viewed';

    protected $fillable = ['user_id', 'product_id', 'viewed_at'];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Track a product view
     */
    public static function track(int $userId, int $productId): self
    {
        return static::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['viewed_at' => now()]
        );
    }

    /**
     * Get recently viewed products for a user
     */
    public static function getForUser(int $userId, int $limit = 10)
    {
        return static::where('user_id', $userId)
            ->with(['product' => fn($q) => $q->where('status', 'active')])
            ->orderByDesc('viewed_at')
            ->limit($limit)
            ->get()
            ->pluck('product')
            ->filter();
    }

    /**
     * Clean old records (keep last 50 per user)
     */
    public static function cleanOldRecords(int $userId, int $keep = 50): void
    {
        $idsToKeep = static::where('user_id', $userId)
            ->orderByDesc('viewed_at')
            ->limit($keep)
            ->pluck('id');

        static::where('user_id', $userId)
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}
