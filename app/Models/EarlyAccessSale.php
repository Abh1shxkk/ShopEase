<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyAccessSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'member_access_at',
        'public_access_at',
        'ends_at',
        'member_discount',
        'applicable_categories',
        'applicable_products',
        'is_active',
    ];

    protected $casts = [
        'member_access_at' => 'datetime',
        'public_access_at' => 'datetime',
        'ends_at' => 'datetime',
        'member_discount' => 'decimal:2',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('ends_at')
                           ->orWhere('ends_at', '>', now());
                     });
    }

    public function scopeCurrentlyRunning($query)
    {
        return $query->active()
                     ->where('member_access_at', '<=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->active()
                     ->where('member_access_at', '>', now());
    }

    public function isAccessibleByMembers(): bool
    {
        return $this->is_active && 
               $this->member_access_at <= now() && 
               ($this->ends_at === null || $this->ends_at > now());
    }

    public function isAccessibleByPublic(): bool
    {
        return $this->is_active && 
               $this->public_access_at <= now() && 
               ($this->ends_at === null || $this->ends_at > now());
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'inactive';
        if ($this->ends_at && $this->ends_at < now()) return 'ended';
        if ($this->public_access_at <= now()) return 'public';
        if ($this->member_access_at <= now()) return 'members_only';
        return 'upcoming';
    }

    public function getTimeUntilMemberAccessAttribute(): ?string
    {
        if ($this->member_access_at <= now()) return null;
        return $this->member_access_at->diffForHumans();
    }

    public function getTimeUntilPublicAccessAttribute(): ?string
    {
        if ($this->public_access_at <= now()) return null;
        return $this->public_access_at->diffForHumans();
    }
}
