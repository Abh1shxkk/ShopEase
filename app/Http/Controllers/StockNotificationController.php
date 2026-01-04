<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockNotification;
use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'email' => 'required|email',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is actually out of stock
        if ($product->stock > 0) {
            return response()->json([
                'success' => false,
                'message' => 'This product is currently in stock'
            ], 422);
        }

        // Check if already subscribed
        $existing = StockNotification::where('product_id', $request->product_id)
            ->where('email', $request->email)
            ->where('notified', false)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed for this notification'
            ], 422);
        }

        StockNotification::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => "We'll notify you when this item is back in stock!"
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'email' => 'required|email',
        ]);

        StockNotification::where('product_id', $request->product_id)
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification subscription removed'
        ]);
    }
}
