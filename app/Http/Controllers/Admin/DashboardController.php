<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total'),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        
        // Monthly revenue for chart
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers', 'monthlyRevenue'));
    }
}
