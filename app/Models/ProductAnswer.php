<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAnswer extends Model
{
    protected $fillable = ['question_id', 'user_id', 'answer', 'is_seller_answer', 'is_approved', 'helpful_count'];

    protected $casts = [
        'is_seller_answer' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(ProductQuestion::class, 'question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
