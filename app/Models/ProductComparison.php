<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductComparison extends Model
{
    protected $fillable = ['user_id', 'session_id', 'product_ids'];

    protected $casts = [
        'product_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProducts()
    {
        return Product::whereIn('id', $this->product_ids ?? [])->get();
    }

    public static function getForUser(?int $userId, ?string $sessionId): ?self
    {
        return static::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->first();
    }

    public static function addProduct(?int $userId, ?string $sessionId, int $productId): self
    {
        $comparison = static::getForUser($userId, $sessionId);
        
        if (!$comparison) {
            $comparison = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_ids' => [$productId],
            ]);
        } else {
            $ids = $comparison->product_ids ?? [];
            if (!in_array($productId, $ids) && count($ids) < 4) {
                $ids[] = $productId;
                $comparison->update(['product_ids' => $ids]);
            }
        }
        
        return $comparison;
    }

    public static function removeProduct(?int $userId, ?string $sessionId, int $productId): void
    {
        $comparison = static::getForUser($userId, $sessionId);
        if ($comparison) {
            $ids = array_filter($comparison->product_ids ?? [], fn($id) => $id != $productId);
            if (empty($ids)) {
                $comparison->delete();
            } else {
                $comparison->update(['product_ids' => array_values($ids)]);
            }
        }
    }
}
