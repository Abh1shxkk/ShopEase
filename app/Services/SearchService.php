<?php

namespace App\Services;

use App\Models\Category;
use App\Models\PopularSearch;
use App\Models\Product;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    public function search(string $query, array $filters = [], int $perPage = 12)
    {
        $query = trim($query);
        
        if (empty($query)) {
            return Product::where('status', 'active')->paginate($perPage);
        }

        $products = Product::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($cq) use ($query) {
                      $cq->where('name', 'like', "%{$query}%");
                  });
            });

        // Apply filters
        if (!empty($filters['category'])) {
            $products->whereHas('category', function ($q) use ($filters) {
                $q->where('name', $filters['category']);
            });
        }

        if (!empty($filters['min_price'])) {
            $products->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $products->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['gender'])) {
            $products->where(function ($q) use ($filters) {
                $q->where('gender', $filters['gender'])
                  ->orWhere('gender', 'unisex');
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'relevance';
        switch ($sort) {
            case 'price_low':
                $products->orderBy('price', 'asc');
                break;
            case 'price_high':
                $products->orderBy('price', 'desc');
                break;
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            default:
                // Relevance - prioritize name matches
                $products->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", ["%{$query}%"])
                         ->orderBy('created_at', 'desc');
        }

        return $products->paginate($perPage);
    }

    public function getSuggestions(string $query, int $limit = 8): array
    {
        $query = strtolower(trim($query));
        
        if (strlen($query) < 2) {
            return [];
        }

        $cacheKey = "search_suggestions_{$query}";
        
        return Cache::remember($cacheKey, 300, function () use ($query, $limit) {
            $suggestions = [];

            // Product name suggestions
            $products = Product::where('status', 'active')
                ->where('name', 'like', "{$query}%")
                ->select('name', 'id')
                ->limit($limit)
                ->get();

            foreach ($products as $product) {
                $suggestions[] = [
                    'type' => 'product',
                    'text' => $product->name,
                    'id' => $product->id,
                ];
            }

            // If not enough, search within name
            if (count($suggestions) < $limit) {
                $moreProducts = Product::where('status', 'active')
                    ->where('name', 'like', "%{$query}%")
                    ->where('name', 'not like', "{$query}%")
                    ->select('name', 'id')
                    ->limit($limit - count($suggestions))
                    ->get();

                foreach ($moreProducts as $product) {
                    $suggestions[] = [
                        'type' => 'product',
                        'text' => $product->name,
                        'id' => $product->id,
                    ];
                }
            }

            // Category suggestions
            $categories = Category::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->limit(3)
                ->pluck('name');

            foreach ($categories as $category) {
                $suggestions[] = [
                    'type' => 'category',
                    'text' => $category,
                ];
            }

            return array_slice($suggestions, 0, $limit);
        });
    }

    public function getAutoComplete(string $query, int $limit = 6): array
    {
        $query = strtolower(trim($query));
        
        if (strlen($query) < 2) {
            return $this->getDefaultSuggestions();
        }

        $results = [
            'suggestions' => $this->getSuggestions($query, $limit),
            'popular' => [],
            'history' => [],
        ];

        // Add matching popular searches
        $popular = PopularSearch::where('query', 'like', "{$query}%")
            ->orderBy('search_count', 'desc')
            ->limit(3)
            ->pluck('query')
            ->toArray();
        
        $results['popular'] = $popular;

        // Add user's matching search history
        if (auth()->check()) {
            $history = SearchHistory::where('user_id', auth()->id())
                ->where('query', 'like', "{$query}%")
                ->select('query')
                ->distinct()
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->pluck('query')
                ->toArray();
            
            $results['history'] = $history;
        }

        return $results;
    }

    public function getDefaultSuggestions(): array
    {
        return Cache::remember('default_search_suggestions', 3600, function () {
            return [
                'suggestions' => [],
                'popular' => PopularSearch::getPopular(5),
                'trending' => PopularSearch::getTrending(5),
                'history' => auth()->check() ? SearchHistory::getUserHistory(auth()->id(), 5) : [],
            ];
        });
    }

    public function recordSearch(string $query, int $resultsCount): void
    {
        if (strlen(trim($query)) >= 2) {
            SearchHistory::record($query, $resultsCount);
        }
    }
}
