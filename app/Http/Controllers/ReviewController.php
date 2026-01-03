<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this product'
                ], 422);
            }
            return back()->with('error', 'You have already reviewed this product');
        }

        // Check if user has purchased this product (verified purchase)
        $hasPurchased = Order::where('user_id', auth()->id())
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('status', 'delivered')
            ->exists();

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified_purchase' => $hasPurchased,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'comment' => $review->comment,
                    'user_name' => auth()->user()->name,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'created_at' => $review->created_at->diffForHumans(),
                ]
            ]);
        }

        return back()->with('success', 'Review submitted successfully!');
    }

    public function destroy(Review $review)
    {
        // Only allow user to delete their own review
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);
        }

        return back()->with('success', 'Review deleted successfully');
    }
}
