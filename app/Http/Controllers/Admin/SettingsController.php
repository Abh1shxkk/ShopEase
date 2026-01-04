<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\HeroBanner;
use App\Models\FeaturedSection;
use App\Models\ShopBanner;
use App\Models\FooterLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function general()
    {
        $settings = [
            'site_name' => SiteSetting::get('site_name', 'ShopEase'),
            'site_tagline' => SiteSetting::get('site_tagline', 'Shop Smart, Live Better'),
            'site_description' => SiteSetting::get('site_description', ''),
            'contact_email' => SiteSetting::get('contact_email', ''),
            'contact_phone' => SiteSetting::get('contact_phone', ''),
            'contact_address' => SiteSetting::get('contact_address', ''),
            'instagram_url' => SiteSetting::get('instagram_url', ''),
            'facebook_url' => SiteSetting::get('facebook_url', ''),
            'twitter_url' => SiteSetting::get('twitter_url', ''),
        ];
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $fields = ['site_name', 'site_tagline', 'site_description', 'contact_email', 
                   'contact_phone', 'contact_address', 'instagram_url', 'facebook_url', 'twitter_url'];
        
        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''), 'text', 'general');
        }
        
        SiteSetting::clearCache();
        return back()->with('success', 'General settings updated successfully!');
    }

    // Hero Banners
    public function heroBanners()
    {
        $banners = HeroBanner::orderBy('sort_order')->get();
        return view('admin.settings.hero-banners', compact('banners'));
    }

    public function createHeroBanner()
    {
        return view('admin.settings.hero-banner-form', ['banner' => null]);
    }

    public function storeHeroBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'button_text_2' => 'nullable|string|max:50',
            'button_link_2' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('banners', 'public');
        }
        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');

        HeroBanner::create($validated);
        return redirect()->route('admin.settings.hero-banners')->with('success', 'Banner created successfully!');
    }

    public function editHeroBanner(HeroBanner $banner)
    {
        return view('admin.settings.hero-banner-form', compact('banner'));
    }

    public function updateHeroBanner(Request $request, HeroBanner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'button_text_2' => 'nullable|string|max:50',
            'button_link_2' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            if ($banner->image && !str_starts_with($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image_file')->store('banners', 'public');
        }
        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');

        $banner->update($validated);
        return redirect()->route('admin.settings.hero-banners')->with('success', 'Banner updated successfully!');
    }

    public function destroyHeroBanner(HeroBanner $banner)
    {
        if ($banner->image && !str_starts_with($banner->image, 'http')) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.settings.hero-banners')->with('success', 'Banner deleted successfully!');
    }

    // Featured Sections
    public function featuredSections()
    {
        $sections = FeaturedSection::orderBy('section_type')->orderBy('sort_order')->get()->groupBy('section_type');
        return view('admin.settings.featured-sections', compact('sections'));
    }

    public function createFeaturedSection(Request $request)
    {
        $type = $request->get('type', 'category_showcase');
        return view('admin.settings.featured-section-form', ['section' => null, 'type' => $type]);
    }

    public function storeFeaturedSection(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'link' => 'nullable|string|max:255',
            'link_text' => 'nullable|string|max:100',
            'section_type' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('sections', 'public');
        }
        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');

        FeaturedSection::create($validated);
        return redirect()->route('admin.settings.featured-sections')->with('success', 'Section created successfully!');
    }

    public function editFeaturedSection(FeaturedSection $section)
    {
        return view('admin.settings.featured-section-form', ['section' => $section, 'type' => $section->section_type]);
    }

    public function updateFeaturedSection(Request $request, FeaturedSection $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'link' => 'nullable|string|max:255',
            'link_text' => 'nullable|string|max:100',
            'section_type' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            if ($section->image && !str_starts_with($section->image, 'http')) {
                Storage::disk('public')->delete($section->image);
            }
            $validated['image'] = $request->file('image_file')->store('sections', 'public');
        }
        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');

        $section->update($validated);
        return redirect()->route('admin.settings.featured-sections')->with('success', 'Section updated successfully!');
    }

    public function destroyFeaturedSection(FeaturedSection $section)
    {
        if ($section->image && !str_starts_with($section->image, 'http')) {
            Storage::disk('public')->delete($section->image);
        }
        $section->delete();
        return redirect()->route('admin.settings.featured-sections')->with('success', 'Section deleted successfully!');
    }

    // Shop Banners
    public function shopBanners()
    {
        $banners = ShopBanner::orderBy('sort_order')->get();
        return view('admin.settings.shop-banners', compact('banners'));
    }

    public function createShopBanner()
    {
        return view('admin.settings.shop-banner-form', ['banner' => null]);
    }

    public function storeShopBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('shop-banners', 'public');
        }
        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');

        ShopBanner::create($validated);
        return redirect()->route('admin.settings.shop-banners')->with('success', 'Shop banner created successfully!');
    }

    public function editShopBanner(ShopBanner $banner)
    {
        return view('admin.settings.shop-banner-form', compact('banner'));
    }

    public function updateShopBanner(Request $request, ShopBanner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            if ($banner->image && !str_starts_with($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image_file')->store('shop-banners', 'public');
        }
        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');

        $banner->update($validated);
        return redirect()->route('admin.settings.shop-banners')->with('success', 'Shop banner updated successfully!');
    }

    public function destroyShopBanner(ShopBanner $banner)
    {
        if ($banner->image && !str_starts_with($banner->image, 'http')) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.settings.shop-banners')->with('success', 'Shop banner deleted successfully!');
    }

    // Footer Settings
    public function footer()
    {
        $links = FooterLink::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        $settings = [
            'footer_email' => SiteSetting::get('footer_email', 'support@shopease.com'),
            'footer_phone' => SiteSetting::get('footer_phone', '+91 98765 43210'),
            'footer_address' => SiteSetting::get('footer_address', ''),
            'footer_copyright' => SiteSetting::get('footer_copyright', '© ' . date('Y') . ' ShopEase. All rights reserved.'),
            'instagram_url' => SiteSetting::get('instagram_url', ''),
            'facebook_url' => SiteSetting::get('facebook_url', ''),
            'twitter_url' => SiteSetting::get('twitter_url', ''),
            'youtube_url' => SiteSetting::get('youtube_url', ''),
        ];
        return view('admin.settings.footer', compact('links', 'settings'));
    }

    public function updateFooter(Request $request)
    {
        $fields = ['footer_email', 'footer_phone', 'footer_address', 'footer_copyright',
                   'instagram_url', 'facebook_url', 'twitter_url', 'youtube_url'];
        
        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''), 'text', 'footer');
        }
        
        SiteSetting::clearCache();
        return back()->with('success', 'Footer settings updated successfully!');
    }

    public function createFooterLink()
    {
        return view('admin.settings.footer-link-form', ['link' => null]);
    }

    public function storeFooterLink(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'group' => 'required|string|in:shop,account,info',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        FooterLink::create($validated);
        return redirect()->route('admin.settings.footer')->with('success', 'Footer link created successfully!');
    }

    public function editFooterLink(FooterLink $link)
    {
        return view('admin.settings.footer-link-form', compact('link'));
    }

    public function updateFooterLink(Request $request, FooterLink $link)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'group' => 'required|string|in:shop,account,info',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $link->update($validated);
        return redirect()->route('admin.settings.footer')->with('success', 'Footer link updated successfully!');
    }

    public function destroyFooterLink(FooterLink $link)
    {
        $link->delete();
        return redirect()->route('admin.settings.footer')->with('success', 'Footer link deleted successfully!');
    }

    // Payment Settings
    public function payment()
    {
        $settings = [
            'currency' => SiteSetting::get('currency', 'INR'),
            'currency_symbol' => SiteSetting::get('currency_symbol', '₹'),
            'tax_rate' => SiteSetting::get('tax_rate', '18'),
            'tax_name' => SiteSetting::get('tax_name', 'GST'),
            'razorpay_enabled' => SiteSetting::get('razorpay_enabled', '1'),
            'razorpay_key_id' => SiteSetting::get('razorpay_key_id', ''),
            'razorpay_key_secret' => SiteSetting::get('razorpay_key_secret', ''),
            'cod_enabled' => SiteSetting::get('cod_enabled', '1'),
            'min_order_amount' => SiteSetting::get('min_order_amount', '0'),
            'free_shipping_threshold' => SiteSetting::get('free_shipping_threshold', '999'),
            'shipping_charge' => SiteSetting::get('shipping_charge', '50'),
        ];
        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_name' => 'required|string|max:50',
            'min_order_amount' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'shipping_charge' => 'nullable|numeric|min:0',
        ]);

        $fields = [
            'currency', 'currency_symbol', 'tax_rate', 'tax_name',
            'razorpay_enabled', 'razorpay_key_id', 'razorpay_key_secret',
            'cod_enabled', 'min_order_amount', 'free_shipping_threshold', 'shipping_charge'
        ];
        
        foreach ($fields as $field) {
            $value = $request->input($field, '');
            if (in_array($field, ['razorpay_enabled', 'cod_enabled'])) {
                $value = $request->has($field) ? '1' : '0';
            }
            SiteSetting::set($field, $value, 'text', 'payment');
        }
        
        SiteSetting::clearCache();
        return back()->with('success', 'Payment settings updated successfully!');
    }
}
