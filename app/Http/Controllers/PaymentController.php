<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Payment\RazorpayService;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected RazorpayService $razorpay;
    protected LoyaltyService $loyaltyService;

    public function __construct(RazorpayService $razorpay, LoyaltyService $loyaltyService)
    {
        $this->razorpay = $razorpay;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Create order and initiate Razorpay payment
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,upi,card,netbanking',
            'use_reward_points' => 'nullable|boolean',
            'redeem_points' => 'nullable|integer|min:100',
        ]);

        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        $user = auth()->user();
        $subtotal = $cartItems->sum('subtotal');
        $shipping = $subtotal >= 500 ? 0 : 49;
        $tax = $subtotal * 0.18; // 18% GST
        
        // Handle reward points redemption
        $pointsDiscount = 0;
        $pointsRedeemed = 0;
        if ($request->use_reward_points && $request->redeem_points >= 100) {
            $validation = $this->loyaltyService->validateRedemption($user, $request->redeem_points, $subtotal);
            if ($validation['valid']) {
                $pointsRedeemed = $request->redeem_points;
                $pointsDiscount = $validation['discount'];
            }
        }
        
        $total = $subtotal + $shipping + $tax - $pointsDiscount;

        DB::beginTransaction();
        try {
            // Create order with pending status
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $pointsDiscount,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'notes' => $request->notes,
                'points_redeemed' => $pointsRedeemed,
            ]);
            
            // Redeem points if used
            if ($pointsRedeemed > 0) {
                $this->loyaltyService->redeemPoints($user, $pointsRedeemed, 'redemption', "Redeemed for order #{$order->order_number}", $order);
            }

            // Create order items
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
            }

            // If COD, complete order directly
            if ($request->payment_method === 'cod') {
                $this->completeOrder($order, $cartItems);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'cod' => true,
                    'redirect' => route('checkout.success', $order),
                ]);
            }

            // Create Razorpay order for online payment
            $razorpayOrder = $this->razorpay->createOrder($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'razorpay_order_id' => $razorpayOrder['order_id'],
                'razorpay_key' => $razorpayOrder['key'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency'],
                'name' => config('app.name'),
                'description' => 'Order #' . $order->order_number,
                'prefill' => [
                    'name' => $request->shipping_name,
                    'email' => $request->shipping_email,
                    'contact' => $request->shipping_phone,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment order creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to create payment order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify Razorpay payment
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Verify signature
        $isValid = $this->razorpay->verifyPayment([
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);

        if (!$isValid) {
            $order->update(['payment_status' => 'failed']);
            return response()->json(['error' => 'Payment verification failed'], 400);
        }

        DB::beginTransaction();
        try {
            // Update order with payment details
            $order->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Complete the order
            $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
            $this->completeOrder($order, $cartItems);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('checkout.success', $order),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Handle payment failure
     */
    public function failed(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($order) {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json([
            'error' => 'Payment failed. Please try again.',
            'redirect' => route('checkout'),
        ]);
    }

    /**
     * Complete order after successful payment
     */
    protected function completeOrder(Order $order, $cartItems)
    {
        // Reduce stock
        foreach ($cartItems as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        Cart::where('user_id', auth()->id())->delete();

        // Update order status
        $order->update(['status' => 'processing']);

        // Mark abandoned cart as recovered
        $abandonedCartService = app(\App\Services\AbandonedCartService::class);
        $abandonedCartService->markRecovered(auth()->user(), $order->id);

        // Handle referral completion
        $referralService = app(\App\Services\ReferralService::class);
        $referralService->completeReferral($order);
        
        // Earn loyalty points from order (with tier multiplier)
        $this->loyaltyService->processOrderCompletion($order);

        // Record frequently bought together data
        $bundleService = app(\App\Services\BundleService::class);
        $bundleService->recordOrderPurchases($order);
    }
}
