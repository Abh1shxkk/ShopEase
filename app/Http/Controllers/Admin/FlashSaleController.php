<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\Product;
use App\Services\FlashSaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FlashSaleController extends Controller
{
    public function __construct(protected FlashSaleService $flashSaleService)
    {
    }

    public function index()
    {
        $sales = FlashSale::withCount('products')
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = $this->flashSaleService->getStats();

        return view('admin.flash-sales.index', compact('sales', 'stats'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'discount_price', 'image']);

        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'starts_at' => 'required|date|after_or_equal:now',
            'ends_at' => 'required|date|after:starts_at',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.sale_price' => 'required|numeric|min:0',
            'products.*.quantity_limit' => 'nullable|integer|min:1',
            'products.*.per_user_limit' => 'required|integer|min:1',
        ]);

        $data = $request->only(['name', 'description', 'starts_at', 'ends_at']);
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('flash-sales', 'public');
        }

        $flashSale = FlashSale::create($data);

        foreach ($request->products as $product) {
            FlashSaleProduct::create([
                'flash_sale_id' => $flashSale->id,
                'product_id' => $product['product_id'],
                'sale_price' => $product['sale_price'],
                'quantity_limit' => $product['quantity_limit'] ?? null,
                'per_user_limit' => $product['per_user_limit'],
            ]);
        }

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale created successfully!');
    }

    public function edit(FlashSale $flashSale)
    {
        $flashSale->load('flashSaleProducts.product');
        
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'discount_price', 'image']);

        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.sale_price' => 'required|numeric|min:0',
            'products.*.quantity_limit' => 'nullable|integer|min:1',
            'products.*.per_user_limit' => 'required|integer|min:1',
        ]);

        $data = $request->only(['name', 'description', 'starts_at', 'ends_at']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('banner_image')) {
            if ($flashSale->banner_image) {
                Storage::disk('public')->delete($flashSale->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('flash-sales', 'public');
        }

        $flashSale->update($data);

        // Update products
        $flashSale->flashSaleProducts()->delete();
        
        foreach ($request->products as $product) {
            FlashSaleProduct::create([
                'flash_sale_id' => $flashSale->id,
                'product_id' => $product['product_id'],
                'sale_price' => $product['sale_price'],
                'quantity_limit' => $product['quantity_limit'] ?? null,
                'per_user_limit' => $product['per_user_limit'],
            ]);
        }

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale updated successfully!');
    }

    public function destroy(FlashSale $flashSale)
    {
        if ($flashSale->banner_image) {
            Storage::disk('public')->delete($flashSale->banner_image);
        }

        $flashSale->delete();

        return back()->with('success', 'Flash sale deleted successfully!');
    }

    public function toggle(FlashSale $flashSale)
    {
        $flashSale->update(['is_active' => !$flashSale->is_active]);

        return back()->with('success', 'Flash sale status updated!');
    }
}
