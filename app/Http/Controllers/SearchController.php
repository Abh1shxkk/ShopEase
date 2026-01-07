<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        $results = $this->searchService->getAutoComplete($query);
        
        return response()->json($results);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $filters = [
            'category' => $request->get('category'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'gender' => $request->get('gender'),
            'sort' => $request->get('sort'),
        ];

        $products = $this->searchService->search($query, $filters);
        
        // Record search
        if (!empty($query)) {
            $this->searchService->recordSearch($query, $products->total());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('shop.partials.products', compact('products'))->render(),
                'pagination' => $products->links('vendor.pagination.luxury')->render(),
                'count' => [
                    'from' => $products->firstItem() ?? 0,
                    'to' => $products->lastItem() ?? 0,
                    'total' => $products->total()
                ]
            ]);
        }

        // Redirect to shop with search query
        return redirect()->route('shop.index', ['search' => $query] + array_filter($filters));
    }

    public function history()
    {
        if (!auth()->check()) {
            return response()->json(['history' => []]);
        }

        $history = SearchHistory::getUserHistory(auth()->id(), 10);
        
        return response()->json(['history' => $history]);
    }

    public function clearHistory()
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        SearchHistory::clearUserHistory(auth()->id());
        
        return response()->json(['success' => true, 'message' => 'Search history cleared']);
    }

    public function removeFromHistory(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $query = $request->get('query');
        
        SearchHistory::where('user_id', auth()->id())
            ->where('query', $query)
            ->delete();
        
        return response()->json(['success' => true]);
    }
}
