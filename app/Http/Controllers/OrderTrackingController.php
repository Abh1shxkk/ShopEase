<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'trackingEvents']);
        
        return view('orders.tracking', compact('order'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->where('shipping_email', $request->email)
            ->first();

        if (!$order) {
            return back()->withErrors(['order_number' => 'Order not found. Please check your order number and email.']);
        }

        $order->load('trackingEvents');

        return view('orders.tracking-guest', compact('order'));
    }

    public function trackForm()
    {
        return view('orders.track-form');
    }
}
