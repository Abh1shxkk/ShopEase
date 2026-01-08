<?php

namespace App\Http\Controllers;

use App\Models\AbandonedCart;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\AbandonedCartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected AbandonedCartService $abandonedCartService)
    {
    }

    public function index()
    {
        $cartItems = Cart::with(['product', 'variant'])->where('user_id', auth()->id())->get();
        
        // Remove cart items with deleted products (orphaned items)
        $orphanedItems = $cartItems->filter(fn($item) => !$item->product);
        if ($orphanedItems->isNotEmpty()) {
            Cart::whereIn('id', $orphanedItems->pluck('id'))->delete();
            $cartItems = $cartItems->filter(fn($item) => $item->product);
        }
        
        $subtotal = $cartItems->sum('subtotal');
        $shipping = $subtotal >= 250 ? 0 : 10;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        // Capture abandoned cart snapshot
        if ($cartItems->isNotEmpty()) {
            $this->abandonedCartService->capture(auth()->user());
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    /**
     * Recover abandoned cart
     */
    public function recover(string $token)
    {
        $abandonedCart = $this->abandonedCartService->processRecoveryClick($token);
        
        if (!$abandonedCart) {
            return redirect()->route('cart')->with('error', 'Invalid or expired recovery link.');
        }

        // Auto-login if not logged in
        if (!auth()->check()) {
            auth()->login($abandonedCart->user);
        }

        // Restore cart items
        $this->abandonedCartService->restoreCart($abandonedCart);

        return redirect()->route('cart')->with('success', 'Your cart has been restored! Complete your purchase now.');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::find($request->variant_id) : null;
        
        // Check if product has variants but none selected
        if ($product->has_variants && !$variant) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a variant'
                ], 400);
            }
            return back()->with('error', 'Please select a variant');
        }
        
        // Check stock
        $availableStock = $variant ? $variant->stock : $product->stock;
        if ($availableStock < ($request->quantity ?? 1)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available'
                ], 400);
            }
            return back()->with('error', 'Not enough stock available');
        }

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + ($request->quantity ?? 1);
            if ($availableStock < $newQuantity) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not enough stock available'
                    ], 400);
                }
                return back()->with('error', 'Not enough stock available');
            }
            $cart->update(['quantity' => $newQuantity]);
        } else {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        // Get cart count
        $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => $cartCount
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, Cart $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);

        if ($cartItem->user_id !== auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        // Handle deleted product
        if (!$cartItem->product) {
            $cartItem->delete();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Product no longer available'], 400);
            }
            return back()->with('error', 'Product no longer available');
        }

        $availableStock = $cartItem->available_stock;
        if ($availableStock < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available'], 400);
            }
            return back()->with('error', 'Not enough stock available');
        }

        $cartItem->update(['quantity' => $request->quantity]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated!',
                'cart_count' => Cart::where('user_id', auth()->id())->sum('quantity')
            ]);
        }
        
        return back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request, Cart $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $cartItem->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_count' => Cart::where('user_id', auth()->id())->sum('quantity')
            ]);
        }
        
        return back()->with('success', 'Item removed from cart!');
    }

    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
