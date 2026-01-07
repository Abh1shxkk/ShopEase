<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $overview = $this->analyticsService->getOverviewStats();
        $salesReport = $this->analyticsService->getSalesReport($period, $startDate, $endDate);
        $bestSelling = $this->analyticsService->getBestSellingProducts(10, $startDate, $endDate);
        $customerStats = $this->analyticsService->getCustomerAcquisitionStats($startDate, $endDate);
        $abandonedCarts = $this->analyticsService->getAbandonedCartAnalytics($startDate, $endDate);

        return view('admin.analytics.index', compact(
            'overview',
            'salesReport',
            'bestSelling',
            'customerStats',
            'abandonedCarts',
            'period'
        ));
    }

    public function salesReport(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $salesReport = $this->analyticsService->getSalesReport($period, $startDate, $endDate);

        return view('admin.analytics.sales', compact('salesReport', 'period'));
    }

    public function bestSelling(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;
        $limit = $request->get('limit', 20);

        $bestSelling = $this->analyticsService->getBestSellingProducts($limit, $startDate, $endDate);

        return view('admin.analytics.best-selling', compact('bestSelling'));
    }

    public function customers(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $customerStats = $this->analyticsService->getCustomerAcquisitionStats($startDate, $endDate);

        return view('admin.analytics.customers', compact('customerStats'));
    }

    public function abandonedCarts(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $abandonedCarts = $this->analyticsService->getAbandonedCartAnalytics($startDate, $endDate);

        return view('admin.analytics.abandoned-carts', compact('abandonedCarts'));
    }

    public function exportSales(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $salesReport = $this->analyticsService->getSalesReport($period, $startDate, $endDate);

        $filename = 'sales_report_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($salesReport) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Period', 'Revenue', 'Orders', 'Avg Order Value']);
            
            foreach ($salesReport['data'] as $row) {
                fputcsv($file, [
                    $row->period,
                    $row->revenue,
                    $row->orders,
                    round($row->avg_order_value, 2)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
