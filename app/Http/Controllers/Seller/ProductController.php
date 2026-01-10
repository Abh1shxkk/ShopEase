<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        
        $products = $seller->products()
            ->with('category')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->approval, fn($q) => $q->where('approval_status', $request->approval))
            ->latest()
            ->paginate(15);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('seller.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'nullable|in:men,women,unisex',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $seller = $request->user()->seller;
        $settings = SellerSetting::get();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['seller_id'] = $seller->id;
        $validated['status'] = 'active';
        $validated['approval_status'] = $settings->auto_approve_products ? 'approved' : 'pending';

        Product::create($validated);

        $seller->increment('total_products');

        return redirect()->route('seller.products.index')
            ->with('success', $settings->auto_approve_products 
                ? 'Product added successfully!' 
                : 'Product submitted for approval.');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        $categories = Category::orderBy('name')->get();
        return view('seller.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'nullable|in:men,women,unisex',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $seller = $product->seller;
        $product->delete();
        $seller->decrement('total_products');

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
