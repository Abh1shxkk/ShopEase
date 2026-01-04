<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share footer data with all views that include the footer
        View::composer(['partials.landing.footer', 'layouts.app', 'layouts.shop'], function ($view) {
            $footerLinks = FooterLink::active()->get()->groupBy('group');
            $footerSettings = [
                'email' => SiteSetting::get('footer_email', 'support@shopease.com'),
                'phone' => SiteSetting::get('footer_phone', '+91 98765 43210'),
                'address' => SiteSetting::get('footer_address', '123 Commerce Street, Mumbai, MH 400001'),
                'copyright' => SiteSetting::get('footer_copyright', '© ' . date('Y') . ' ShopEase. All rights reserved.'),
                'instagram_url' => SiteSetting::get('instagram_url', ''),
                'facebook_url' => SiteSetting::get('facebook_url', ''),
                'twitter_url' => SiteSetting::get('twitter_url', ''),
                'youtube_url' => SiteSetting::get('youtube_url', ''),
            ];
            
            $view->with('footerLinks', $footerLinks);
            $view->with('footerSettings', $footerSettings);
        });
    }
}
