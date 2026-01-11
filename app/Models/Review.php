<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_approved',
        'helpful_count',
        'not_helpful_count',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ReviewPhoto::class)->orderBy('sort_order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function userVote(?int $userId): ?ReviewVote
    {
        if (!$userId) return null;
        return $this->votes()->where('user_id', $userId)->first();
    }

    public function vote(int $userId, bool $isHelpful): void
    {
        $existingVote = $this->userVote($userId);
        
        if ($existingVote) {
            if ($existingVote->is_helpful !== $isHelpful) {
                // Change vote
                if ($isHelpful) {
                    $this->decrement('not_helpful_count');
                    $this->increment('helpful_count');
                } else {
                    $this->decrement('helpful_count');
                    $this->increment('not_helpful_count');
                }
                $existingVote->update(['is_helpful' => $isHelpful]);
            }
        } else {
            ReviewVote::create([
                'review_id' => $this->id,
                'user_id' => $userId,
                'is_helpful' => $isHelpful,
            ]);
            
            if ($isHelpful) {
                $this->increment('helpful_count');
            } else {
                $this->increment('not_helpful_count');
            }
        }
    }
}
