<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryAlert;
use App\Models\Product;
use App\Models\StockNotification;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        // Filter by stock status
        if ($request->filled('status')) {
            match($request->status) {
                'out_of_stock' => $query->where('stock', '<=', 0),
                'low_stock' => $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'low_stock_threshold'),
                'in_stock' => $query->whereColumn('stock', '>', 'low_stock_threshold'),
                default => null
            };
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('stock', 'asc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Product::where('status', 'active')->count(),
            'in_stock' => Product::where('status', 'active')->whereColumn('stock', '>', 'low_stock_threshold')->count(),
            'low_stock' => InventoryService::getLowStockCount(),
            'out_of_stock' => InventoryService::getOutOfStockCount(),
        ];

        return view('admin.inventory.index', compact('products', 'stats'));
    }

    public function alerts(Request $request)
    {
        $query = InventoryAlert::with('product')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('read')) {
            $query->where('is_read', $request->read === 'read');
        }

        $alerts = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => InventoryAlert::count(),
            'unread' => InventoryAlert::where('is_read', false)->count(),
            'low_stock' => InventoryAlert::where('type', 'low_stock')->count(),
            'out_of_stock' => InventoryAlert::where('type', 'out_of_stock')->count(),
        ];

        return view('admin.inventory.alerts', compact('alerts', 'stats'));
    }

    public function markAlertRead(InventoryAlert $alert)
    {
        $alert->update(['is_read' => true]);
        return back()->with('success', 'Alert marked as read');
    }

    public function markAllAlertsRead()
    {
        InventoryAlert::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All alerts marked as read');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
        ]);

        $previousStock = $product->stock;

        $product->update([
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold,
        ]);

        // Check for inventory alerts
        $inventoryService = new InventoryService();
        $inventoryService->checkStock($product, $previousStock);

        return back()->with('success', 'Stock updated successfully');
    }

    public function bulkUpdateStock(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.stock' => 'required|integer|min:0',
        ]);

        $inventoryService = new InventoryService();

        foreach ($request->products as $data) {
            $product = Product::find($data['id']);
            $previousStock = $product->stock;
            
            $product->update(['stock' => $data['stock']]);
            $inventoryService->checkStock($product, $previousStock);
        }

        return back()->with('success', 'Stock updated for ' . count($request->products) . ' products');
    }

    public function notifications(Product $product)
    {
        $notifications = StockNotification::where('product_id', $product->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.inventory.notifications', compact('product', 'notifications'));
    }

    public function sendTestAlert()
    {
        $inventoryService = new InventoryService();
        $inventoryService->sendLowStockEmailToAdmin();

        return back()->with('success', 'Test alert email sent');
    }
}
