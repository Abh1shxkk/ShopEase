<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    protected $fillable = ['user_id', 'session_id', 'status', 'last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id')->orderBy('created_at');
    }

    public static function getOrCreate(?int $userId, ?string $sessionId): self
    {
        $query = static::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        $conversation = $query->where('status', 'active')->first();
        
        if (!$conversation) {
            $conversation = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'status' => 'active',
                'last_message_at' => now(),
            ]);
        }
        
        return $conversation;
    }

    public function addMessage(string $role, string $content, array $metadata = []): ChatMessage
    {
        $this->update(['last_message_at' => now()]);
        
        return $this->messages()->create([
            'role' => $role,
            'content' => $content,
            'metadata' => $metadata,
        ]);
    }

    public function getRecentMessages(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->messages()->latest()->limit($limit)->get()->reverse();
    }
}
