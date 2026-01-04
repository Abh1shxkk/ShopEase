<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar',
        'date_of_birth', 'gender', 'last_login_at',
        'email_notifications', 'sms_notifications', 'marketing_emails',
        'google_id', 'facebook_id', 'social_avatar', 'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function defaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function defaultPaymentMethod()
    {
        return $this->paymentMethods()->where('is_default', true)->first();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        if ($this->social_avatar) {
            return $this->social_avatar;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff';
    }
}
