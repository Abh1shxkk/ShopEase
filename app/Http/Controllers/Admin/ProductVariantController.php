<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->latest()->paginate(20);
        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.products.variants.form', [
            'product' => $product,
            'variant' => null,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'nullable|string|max:50|unique:product_variants,sku',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'color_code' => 'nullable|string|max:7',
            'material' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ]);

        // Generate SKU if not provided
        if (empty($validated['sku'])) {
            $validated['sku'] = ProductVariant::generateSku(
                $product,
                $validated['size'] ?? null,
                $validated['color'] ?? null,
                $validated['material'] ?? null
            );
        }

        // Handle image
        if (!empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('variants', 'public');
        }
        unset($validated['image_url']);

        $validated['is_active'] = $request->has('is_active');
        $validated['product_id'] = $product->id;

        ProductVariant::create($validated);

        // Enable has_variants on product
        if (!$product->has_variants) {
            $product->update(['has_variants' => true]);
        }

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Variant created successfully!');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.products.variants.form', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'sku' => 'nullable|string|max:50|unique:product_variants,sku,' . $variant->id,
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'color_code' => 'nullable|string|max:7',
            'material' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ]);

        // Handle image
        if (!empty($validated['image_url'])) {
            if ($variant->image && !str_starts_with($variant->image, 'http')) {
                Storage::disk('public')->delete($variant->image);
            }
            $validated['image'] = $validated['image_url'];
        } elseif ($request->hasFile('image')) {
            if ($variant->image && !str_starts_with($variant->image, 'http')) {
                Storage::disk('public')->delete($variant->image);
            }
            $validated['image'] = $request->file('image')->store('variants', 'public');
        }
        unset($validated['image_url']);

        $validated['is_active'] = $request->has('is_active');

        $variant->update($validated);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Variant updated successfully!');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->image && !str_starts_with($variant->image, 'http')) {
            Storage::disk('public')->delete($variant->image);
        }
        
        $variant->delete();

        // Disable has_variants if no variants left
        if ($product->variants()->count() === 0) {
            $product->update(['has_variants' => false]);
        }

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Variant deleted successfully!');
    }

    public function bulkCreate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
            'color_codes' => 'nullable|string',
            'materials' => 'nullable|string',
            'default_stock' => 'required|integer|min:0',
        ]);

        $sizes = array_filter(array_map('trim', explode(',', $validated['sizes'] ?? '')));
        $colors = array_filter(array_map('trim', explode(',', $validated['colors'] ?? '')));
        $colorCodes = array_filter(array_map('trim', explode(',', $validated['color_codes'] ?? '')));
        $materials = array_filter(array_map('trim', explode(',', $validated['materials'] ?? '')));

        // If no options provided, create at least one variant
        if (empty($sizes)) $sizes = [null];
        if (empty($colors)) $colors = [null];
        if (empty($materials)) $materials = [null];

        $created = 0;
        foreach ($sizes as $size) {
            foreach ($colors as $index => $color) {
                foreach ($materials as $material) {
                    // Check if variant already exists
                    $exists = $product->variants()
                        ->where('size', $size)
                        ->where('color', $color)
                        ->where('material', $material)
                        ->exists();

                    if (!$exists) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => ProductVariant::generateSku($product, $size, $color, $material),
                            'size' => $size,
                            'color' => $color,
                            'color_code' => $colorCodes[$index] ?? null,
                            'material' => $material,
                            'stock' => $validated['default_stock'],
                            'is_active' => true,
                        ]);
                        $created++;
                    }
                }
            }
        }

        if ($created > 0 && !$product->has_variants) {
            $product->update(['has_variants' => true]);
        }

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', "{$created} variants created successfully!");
    }

    public function updateStock(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $variant->update(['stock' => $validated['stock']]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Stock updated!']);
        }

        return back()->with('success', 'Stock updated!');
    }
}
