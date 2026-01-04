<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 422);
        }

        if (!$coupon->isValid()) {
            $message = match($coupon->status) {
                'inactive' => 'This coupon is no longer active',
                'scheduled' => 'This coupon is not yet active',
                'expired' => 'This coupon has expired',
                'exhausted' => 'This coupon has reached its usage limit',
                default => 'This coupon cannot be used'
            };
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        if (!$coupon->canBeUsedByUser(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used this coupon the maximum number of times'
            ], 422);
        }

        // Calculate cart subtotal
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum(fn($item) => 
            ($item->product->discount_price ?? $item->product->price) * $item->quantity
        );

        if ($subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum order amount of ₹" . number_format($coupon->min_order_amount, 0) . " required"
            ], 422);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        // Store in session
        session(['applied_coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'name' => $coupon->name,
            'discount' => $discount,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount' => $discount,
                'formatted_discount' => '₹' . number_format($discount, 0),
            ]
        ]);
    }

    public function remove()
    {
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed'
        ]);
    }
}
