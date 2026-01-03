<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum('subtotal');
        $shipping = $subtotal >= 250 ? 0 : 10;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < ($request->quantity ?? 1)) {
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
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + ($request->quantity ?? 1);
            if ($product->stock < $newQuantity) {
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

        if ($cartItem->product->stock < $request->quantity) {
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
