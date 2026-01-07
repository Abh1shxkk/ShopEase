<?php

namespace App\Http\Controllers;

use App\Models\ProductBundle;
use App\Models\Product;
use App\Services\BundleService;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    protected BundleService $bundleService;

    public function __construct(BundleService $bundleService)
    {
        $this->bundleService = $bundleService;
    }

    public function index()
    {
        $bundles = $this->bundleService->getActiveBundles();
        return view('bundles.index', compact('bundles'));
    }

    public function show(ProductBundle $bundle)
    {
        if (!$bundle->isAvailable()) {
            abort(404);
        }

        $bundle->load('items.product');
        $relatedBundles = ProductBundle::active()
            ->where('id', '!=', $bundle->id)
            ->with('items.product')
            ->limit(3)
            ->get();

        return view('bundles.show', compact('bundle', 'relatedBundles'));
    }

    public function addToCart(Request $request, ProductBundle $bundle)
    {
        if (!$bundle->isAvailable()) {
            return back()->with('error', 'This bundle is not available.');
        }

        $user = auth()->user();
        $this->bundleService->addBundleToCart($user, $bundle);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bundle added to cart!',
                'cartCount' => $user->cartItems()->sum('quantity'),
            ]);
        }

        return redirect()->route('cart')->with('success', 'Bundle added to cart!');
    }

    public function getFrequentlyBought(Product $product)
    {
        $products = $this->bundleService->getFrequentlyBoughtTogether($product);
        
        return response()->json([
            'products' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'image' => $p->image,
                'url' => route('shop.show', $p),
            ]),
        ]);
    }
}
