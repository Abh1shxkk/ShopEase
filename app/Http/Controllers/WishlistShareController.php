<?php

namespace App\Http\Controllers;

use App\Models\WishlistShare;
use Illuminate\Http\Request;

class WishlistShareController extends Controller
{
    public function create()
    {
        $existingShare = WishlistShare::where('user_id', auth()->id())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();

        return view('wishlist.share', compact('existingShare'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'expires_in' => 'nullable|in:7,30,90,never',
        ]);

        $expiresAt = match($request->expires_in) {
            '7' => now()->addDays(7),
            '30' => now()->addDays(30),
            '90' => now()->addDays(90),
            default => null,
        };

        $share = WishlistShare::createForUser(auth()->id(), [
            'title' => $request->title,
            'description' => $request->description,
            'expires_at' => $expiresAt,
        ]);

        return redirect()->route('wishlist.share.show', $share->share_token)
            ->with('success', 'Your wishlist share link has been created!');
    }

    public function show(string $token)
    {
        $share = WishlistShare::where('share_token', $token)->firstOrFail();
        
        if ($share->isExpired()) {
            abort(404, 'This shared wishlist has expired.');
        }

        if ($share->user_id !== auth()->id()) {
            $share->incrementViews();
        }

        $items = $share->getWishlistItems();

        return view('wishlist.shared', compact('share', 'items'));
    }

    public function destroy(WishlistShare $share)
    {
        if ($share->user_id !== auth()->id()) {
            abort(403);
        }

        $share->delete();

        return redirect()->route('wishlist')->with('success', 'Share link has been deleted.');
    }
}
