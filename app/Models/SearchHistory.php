<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    protected $fillable = [
        'user_id',
        'query',
        'results_count',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $query, int $resultsCount = 0): self
    {
        $history = self::create([
            'user_id' => auth()->id(),
            'query' => strtolower(trim($query)),
            'results_count' => $resultsCount,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Update popular searches
        PopularSearch::incrementSearch($query);

        return $history;
    }

    public static function getUserHistory(?int $userId = null, int $limit = 10): array
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return [];
        }

        return self::where('user_id', $userId)
            ->select('query')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->pluck('query')
            ->toArray();
    }

    public static function clearUserHistory(?int $userId = null): void
    {
        $userId = $userId ?? auth()->id();
        
        if ($userId) {
            self::where('user_id', $userId)->delete();
        }
    }
}
