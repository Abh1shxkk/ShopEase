<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    public function getSalesReport(string $period = 'daily', ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $endDate = $endDate ?? now();
        
        switch ($period) {
            case 'daily':
                $startDate = $startDate ?? now()->subDays(30);
                $groupFormat = '%Y-%m-%d';
                $labelFormat = 'M d';
                break;
            case 'weekly':
                $startDate = $startDate ?? now()->subWeeks(12);
                $groupFormat = '%Y-%u';
                $labelFormat = 'W';
                break;
            case 'monthly':
                $startDate = $startDate ?? now()->subMonths(12);
                $groupFormat = '%Y-%m';
                $labelFormat = 'M Y';
                break;
            default:
                $startDate = $startDate ?? now()->subDays(30);
                $groupFormat = '%Y-%m-%d';
                $labelFormat = 'M d';
        }

        $sales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as period"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('AVG(total) as avg_order_value')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $totalRevenue = $sales->sum('revenue');
        $totalOrders = $sales->sum('orders');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Previous period comparison
        $previousStart = $startDate->copy()->sub($endDate->diff($startDate));
        $previousEnd = $startDate->copy()->subDay();
        
        $previousRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('total');

        $revenueGrowth = $previousRevenue > 0 
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 
            : 0;

        return [
            'data' => $sales,
            'labels' => $sales->pluck('period'),
            'revenue' => $sales->pluck('revenue'),
            'orders' => $sales->pluck('orders'),
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'avg_order_value' => $avgOrderValue,
                'revenue_growth' => round($revenueGrowth, 1),
            ]
        ];
    }

    public function getBestSellingProducts(int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $products = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        $totalSold = $products->sum('total_sold');

        return [
            'products' => $products->map(function ($product) use ($totalSold) {
                $product->percentage = $totalSold > 0 ? round(($product->total_sold / $totalSold) * 100, 1) : 0;
                return $product;
            }),
            'total_sold' => $totalSold,
        ];
    }

    public function getCustomerAcquisitionStats(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        // New customers per day
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Customer sources (if tracked)
        $totalNewCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $previousStart = $startDate->copy()->sub($endDate->diff($startDate));
        $previousEnd = $startDate->copy()->subDay();
        
        $previousNewCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        $customerGrowth = $previousNewCustomers > 0 
            ? (($totalNewCustomers - $previousNewCustomers) / $previousNewCustomers) * 100 
            : 0;

        // Returning vs new customers (based on orders)
        $returningCustomers = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $totalOrderingCustomers = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        $newOrderingCustomers = $totalOrderingCustomers - $returningCustomers;

        // Social login stats
        $socialLogins = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('google_id')
            ->orWhereNotNull('facebook_id')
            ->count();

        return [
            'daily_signups' => $newCustomers,
            'labels' => $newCustomers->pluck('date'),
            'counts' => $newCustomers->pluck('count'),
            'summary' => [
                'total_new_customers' => $totalNewCustomers,
                'customer_growth' => round($customerGrowth, 1),
                'returning_customers' => $returningCustomers,
                'new_ordering_customers' => $newOrderingCustomers,
                'social_signups' => $socialLogins,
                'retention_rate' => $totalOrderingCustomers > 0 
                    ? round(($returningCustomers / $totalOrderingCustomers) * 100, 1) 
                    : 0,
            ]
        ];
    }

    public function getAbandonedCartAnalytics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        // Carts that haven't been converted to orders
        $abandonedCarts = Cart::whereBetween('carts.updated_at', [$startDate, $endDate])
            ->whereHas('user', function ($q) {
                $q->whereDoesntHave('orders', function ($oq) {
                    $oq->where('created_at', '>', DB::raw('carts.updated_at'));
                });
            })
            ->with(['product', 'user'])
            ->get();

        // Group by product to see most abandoned products
        $abandonedByProduct = Cart::whereBetween('carts.updated_at', [$startDate, $endDate])
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                DB::raw('COUNT(*) as abandon_count'),
                DB::raw('SUM(carts.quantity) as total_quantity'),
                DB::raw('SUM(carts.quantity * products.price) as potential_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderByDesc('abandon_count')
            ->limit(10)
            ->get();

        // Calculate totals
        $totalAbandonedCarts = Cart::whereBetween('carts.updated_at', [$startDate, $endDate])->count();
        $totalAbandonedValue = Cart::whereBetween('carts.updated_at', [$startDate, $endDate])
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->sum(DB::raw('carts.quantity * products.price'));

        // Completed orders in same period
        $completedOrders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $cartConversionRate = ($totalAbandonedCarts + $completedOrders) > 0
            ? round(($completedOrders / ($totalAbandonedCarts + $completedOrders)) * 100, 1)
            : 0;

        // Daily abandoned carts
        $dailyAbandoned = Cart::whereBetween('carts.updated_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE(carts.updated_at) as date"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'abandoned_by_product' => $abandonedByProduct,
            'daily_abandoned' => $dailyAbandoned,
            'labels' => $dailyAbandoned->pluck('date'),
            'counts' => $dailyAbandoned->pluck('count'),
            'summary' => [
                'total_abandoned_carts' => $totalAbandonedCarts,
                'total_abandoned_value' => $totalAbandonedValue,
                'cart_conversion_rate' => $cartConversionRate,
                'avg_cart_value' => $totalAbandonedCarts > 0 
                    ? round($totalAbandonedValue / $totalAbandonedCarts, 2) 
                    : 0,
            ]
        ];
    }

    public function getOverviewStats(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'today' => [
                'revenue' => Order::where('payment_status', 'paid')->whereDate('created_at', $today)->sum('total'),
                'orders' => Order::where('payment_status', 'paid')->whereDate('created_at', $today)->count(),
                'new_customers' => User::where('role', 'customer')->whereDate('created_at', $today)->count(),
            ],
            'this_week' => [
                'revenue' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisWeek)->sum('total'),
                'orders' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisWeek)->count(),
                'new_customers' => User::where('role', 'customer')->where('created_at', '>=', $thisWeek)->count(),
            ],
            'this_month' => [
                'revenue' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisMonth)->sum('total'),
                'orders' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisMonth)->count(),
                'new_customers' => User::where('role', 'customer')->where('created_at', '>=', $thisMonth)->count(),
            ],
        ];
    }
}
