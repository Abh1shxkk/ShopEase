<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBundle;
use App\Models\Product;
use App\Models\FrequentlyBoughtTogether;
use App\Services\BundleService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BundleController extends Controller
{
    protected BundleService $bundleService;

    public function __construct(BundleService $bundleService)
    {
        $this->bundleService = $bundleService;
    }

    public function index(Request $request)
    {
        $query = ProductBundle::with(['items.product']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $bundles = $query->orderBy('sort_order')->paginate(15);

        $stats = [
            'total' => ProductBundle::count(),
            'active' => ProductBundle::where('is_active', true)->count(),
            'inactive' => ProductBundle::where('is_active', false)->count(),
        ];

        return view('admin.bundles.index', compact('bundles', 'stats'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.bundles.form', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'products' => 'required|array|min:2',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $data = $request->only(['name', 'description', 'discount_type', 'discount_value', 'starts_at', 'ends_at']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('bundles', 'public');
        }

        $this->bundleService->createBundle($data, $request->products);

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle created successfully.');
    }

    public function edit(ProductBundle $bundle)
    {
        $bundle->load('items.product');
        $products = Product::where('status', 'active')->orderBy('name')->get();

        return view('admin.bundles.form', compact('bundle', 'products'));
    }

    public function update(Request $request, ProductBundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'products' => 'required|array|min:2',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $data = $request->only(['name', 'description', 'discount_type', 'discount_value', 'starts_at', 'ends_at']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('bundles', 'public');
        }

        $this->bundleService->updateBundle($bundle, $data, $request->products);

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle updated successfully.');
    }

    public function destroy(ProductBundle $bundle)
    {
        $bundle->delete();
        return back()->with('success', 'Bundle deleted successfully.');
    }

    public function toggleStatus(ProductBundle $bundle)
    {
        $bundle->update(['is_active' => !$bundle->is_active]);
        return back()->with('success', 'Bundle status updated.');
    }

    // Frequently Bought Together Management
    public function frequentlyBought(Request $request)
    {
        $query = FrequentlyBoughtTogether::with(['product', 'relatedProduct']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $pairs = $query->orderByDesc('purchase_count')->paginate(20);

        return view('admin.bundles.frequently-bought', compact('pairs'));
    }

    public function updateFrequentlyBought(Request $request, FrequentlyBoughtTogether $pair)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $pair->update([
            'discount_percentage' => $request->discount_percentage,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Updated successfully.');
    }

    public function regenerateFrequentlyBought()
    {
        $this->bundleService->generateFrequentlyBoughtData();
        return back()->with('success', 'Frequently bought together data regenerated.');
    }
}
