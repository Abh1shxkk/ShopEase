<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Gender filter (Men/Women)
        if ($request->filled('gender')) {
            $gender = strtolower($request->gender);
            if (in_array($gender, ['men', 'women'])) {
                $query->where(function($q) use ($gender) {
                    $q->where('gender', $gender)
                      ->orWhere('gender', 'unisex');
                });
            }
        }

        // Category filter - use category_id with relationship
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            
            // Try to find category IDs first
            $categoryIds = Category::whereIn('name', $categories)->pluck('id')->toArray();
            
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            } else {
                // Fallback to old category_old column
                $query->whereIn('category_old', $categories);
            }
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock filter
        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        // Sorting
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        
        // Get categories from database, fallback to hardcoded if empty
        $dbCategories = Category::where('is_active', true)->orderBy('sort_order')->pluck('name')->toArray();
        $categories = !empty($dbCategories) ? $dbCategories : ['Electronics', 'Fashion', 'Home', 'Books', 'Sports', 'Beauty'];
        
        $totalProducts = Product::where('status', 'active')->count();
        
        // Get current gender filter for view
        $currentGender = $request->gender;

        // AJAX request - return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('shop.partials.products', compact('products'))->render(),
                'pagination' => $products->links()->render(),
                'count' => [
                    'from' => $products->firstItem() ?? 0,
                    'to' => $products->lastItem() ?? 0,
                    'total' => $products->total()
                ]
            ]);
        }

        return view('shop.index', compact('products', 'categories', 'totalProducts', 'currentGender'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        // Get related products - try category_id first, then fallback
        $relatedQuery = Product::where('status', 'active')
            ->where('id', '!=', $product->id);
            
        if ($product->category_id) {
            $relatedQuery->where('category_id', $product->category_id);
        } elseif ($product->category_old) {
            $relatedQuery->where('category_old', $product->category_old);
        }
        
        $relatedProducts = $relatedQuery->take(6)->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
