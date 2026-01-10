<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SellerEarning;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        
        $orders = SellerEarning::with(['order.user', 'order.items.product'])
            ->where('seller_id', $seller->id)
            ->when($request->status, fn($q) => $q->whereHas('order', fn($o) => $o->where('status', $request->status)))
            ->when($request->search, fn($q) => $q->whereHas('order', fn($o) => $o->where('order_number', 'like', "%{$request->search}%")))
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => SellerEarning::where('seller_id', $seller->id)->count(),
            'pending' => SellerEarning::where('seller_id', $seller->id)->whereHas('order', fn($q) => $q->where('status', 'pending'))->count(),
            'processing' => SellerEarning::where('seller_id', $seller->id)->whereHas('order', fn($q) => $q->where('status', 'processing'))->count(),
            'delivered' => SellerEarning::where('seller_id', $seller->id)->whereHas('order', fn($q) => $q->where('status', 'delivered'))->count(),
        ];

        return view('seller.orders.index', compact('orders', 'stats'));
    }

    public function show(Request $request, Order $order)
    {
        $seller = $request->user()->seller;
        
        // Check if seller has products in this order
        $earning = SellerEarning::where('seller_id', $seller->id)
            ->where('order_id', $order->id)
            ->first();
            
        if (!$earning) {
            abort(403, 'You do not have access to this order.');
        }

        $order->load(['user', 'items.product']);
        
        // Filter items to only show seller's products
        $sellerItems = $order->items->filter(fn($item) => $item->product && $item->product->seller_id === $seller->id);
        
        return view('seller.orders.show', compact('order', 'sellerItems', 'earning'));
    }
}
