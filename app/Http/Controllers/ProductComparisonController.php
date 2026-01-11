<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductComparison;
use Illuminate\Http\Request;

class ProductComparisonController extends Controller
{
    public function index(Request $request)
    {
        $comparison = ProductComparison::getForUser(
            auth()->id(),
            session()->getId()
        );
        
        $products = $comparison ? $comparison->getProducts() : collect();
        
        return view('shop.compare', compact('products'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        
        $comparison = ProductComparison::getForUser(auth()->id(), session()->getId());
        $currentCount = $comparison ? count($comparison->product_ids ?? []) : 0;
        
        if ($currentCount >= 4) {
            return response()->json([
                'success' => false,
                'message' => 'You can compare up to 4 products at a time.'
            ]);
        }
        
        ProductComparison::addProduct(
            auth()->id(),
            session()->getId(),
            $request->product_id
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to comparison.',
            'count' => $currentCount + 1
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        
        ProductComparison::removeProduct(
            auth()->id(),
            session()->getId(),
            $request->product_id
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from comparison.'
        ]);
    }

    public function clear()
    {
        $comparison = ProductComparison::getForUser(auth()->id(), session()->getId());
        if ($comparison) {
            $comparison->delete();
        }
        
        return redirect()->route('compare.index')->with('success', 'Comparison cleared.');
    }

    public function count()
    {
        $comparison = ProductComparison::getForUser(auth()->id(), session()->getId());
        return response()->json([
            'count' => $comparison ? count($comparison->product_ids ?? []) : 0
        ]);
    }
}
