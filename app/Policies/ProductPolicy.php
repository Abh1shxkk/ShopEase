<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function update(User $user, Product $product): bool
    {
        // Admin can update any product
        if ($user->isAdmin()) {
            return true;
        }

        // Seller can only update their own products
        if ($user->seller && $product->seller_id === $user->seller->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Product $product): bool
    {
        // Admin can delete any product
        if ($user->isAdmin()) {
            return true;
        }

        // Seller can only delete their own products
        if ($user->seller && $product->seller_id === $user->seller->id) {
            return true;
        }

        return false;
    }
}
