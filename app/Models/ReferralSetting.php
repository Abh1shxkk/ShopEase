<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ReferralSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("referral_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("referral_setting_{$key}");
    }

    public static function getDefaults(): array
    {
        return [
            'referrer_reward_points' => 100,
            'referred_reward_points' => 50,
            'min_order_for_completion' => 500,
            'points_per_rupee' => 1,
            'points_value_in_rupees' => 0.25,
            'min_points_to_redeem' => 100,
            'max_points_per_order' => 500,
            'referral_expiry_days' => 30,
            'is_enabled' => true,
        ];
    }
}
