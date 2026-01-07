<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopularSearch extends Model
{
    protected $fillable = [
        'query',
        'search_count',
    ];

    public static function incrementSearch(string $query): void
    {
        $normalizedQuery = strtolower(trim($query));
        
        if (strlen($normalizedQuery) < 2) {
            return;
        }

        self::updateOrCreate(
            ['query' => $normalizedQuery],
            ['search_count' => \DB::raw('search_count + 1')]
        );
    }

    public static function getPopular(int $limit = 10): array
    {
        return self::orderBy('search_count', 'desc')
            ->limit($limit)
            ->pluck('query')
            ->toArray();
    }

    public static function getTrending(int $limit = 5): array
    {
        // Get searches from last 7 days with high frequency
        return self::where('updated_at', '>=', now()->subDays(7))
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->pluck('query')
            ->toArray();
    }
}
