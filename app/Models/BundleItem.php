<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundleItem extends Model
{
    protected $fillable = ['bundle_id', 'product_id', 'quantity', 'sort_order'];

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(ProductBundle::class, 'bundle_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->product->price * $this->quantity;
    }
}
