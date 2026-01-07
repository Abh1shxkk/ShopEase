<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $user = auth()->user();
        $subtotal = $cartItems->sum('subtotal');
        
        // Get applied coupon from session
        $appliedCoupon = session('applied_coupon');
        $couponDiscount = $appliedCoupon ? $appliedCoupon['discount'] : 0;
        
        // Apply member discount
        $memberDiscount = 0;
        $memberDiscountPercent = 0;
        if ($user->isMember()) {
            $memberDiscountPercent = $user->getMemberDiscount();
            if ($memberDiscountPercent > 0) {
                $memberDiscount = ($subtotal - $couponDiscount) * ($memberDiscountPercent / 100);
            }
        }
        
        $discount = $couponDiscount + $memberDiscount;
        
        // Free shipping for members or orders over ₹500
        $hasFreeShipping = $user->hasFreeShipping();
        $shipping = $hasFreeShipping || ($subtotal - $discount) >= 500 ? 0 : 49;
        
        $tax = ($subtotal - $discount) * 0.18;
        $total = $subtotal - $discount + $shipping + $tax;

        return view('checkout.index', compact(
            'cartItems', 'subtotal', 'discount', 'appliedCoupon', 
            'couponDiscount', 'memberDiscount', 'memberDiscountPercent',
            'shipping', 'hasFreeShipping', 'tax', 'total'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,card',
        ]);

        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $user = auth()->user();
        $subtotal = $cartItems->sum('subtotal');
        
        // Handle coupon
        $appliedCoupon = session('applied_coupon');
        $couponDiscount = 0;
        $couponId = null;
        $couponCode = null;
        
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->canBeUsedByUser(auth()->id())) {
                $couponDiscount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
                $couponCode = $coupon->code;
            }
        }
        
        // Apply member discount
        $memberDiscount = 0;
        if ($user->isMember()) {
            $memberDiscountPercent = $user->getMemberDiscount();
            if ($memberDiscountPercent > 0) {
                $memberDiscount = ($subtotal - $couponDiscount) * ($memberDiscountPercent / 100);
            }
        }
        
        $discount = $couponDiscount + $memberDiscount;
        
        // Free shipping for members or orders over ₹500
        $hasFreeShipping = $user->hasFreeShipping();
        $shipping = $hasFreeShipping || ($subtotal - $discount) >= 500 ? 0 : 49;
        
        $tax = ($subtotal - $discount) * 0.18;
        $total = $subtotal - $discount + $shipping + $tax;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_id' => $couponId,
                'coupon_code' => $couponCode,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'paid',
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'total' => $price * $item->quantity,
                ]);

                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }
            
            // Record coupon usage
            if ($couponId && $couponDiscount > 0) {
                CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'discount_amount' => $couponDiscount,
                ]);
                
                // Increment coupon used count
                Coupon::where('id', $couponId)->increment('used_count');
            }

            // Clear cart and coupon session
            Cart::where('user_id', auth()->id())->delete();
            session()->forget('applied_coupon');

            DB::commit();
            
            // Send invoice email
            try {
                Mail::to($order->shipping_email)->send(new OrderInvoiceMail($order));
            } catch (\Exception $e) {
                // Log error but don't fail the order
                \Log::error('Failed to send invoice email: ' . $e->getMessage());
            }
            
            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
