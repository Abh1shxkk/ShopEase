<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\SellerService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private SellerService $sellerService)
    {
    }

    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        
        if (!$seller) {
            return redirect()->route('seller.register');
        }

        if (!$seller->isApproved()) {
            return view('seller.pending', compact('seller'));
        }

        $stats = $this->sellerService->getSellerStats($seller);
        $recentOrders = $seller->earnings()
            ->with('order.user')
            ->latest()
            ->take(10)
            ->get();

        $recentProducts = $seller->products()
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('seller', 'stats', 'recentOrders', 'recentProducts'));
    }
}
