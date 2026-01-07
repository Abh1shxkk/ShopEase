<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Newsletter Routes (Public)
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Landing Page
Route::get('/', function () {
    $curatedProducts = \App\Models\Product::with('category')
        ->where('status', 'active')
        ->whereNotNull('image')
        ->inRandomOrder()
        ->take(8)
        ->get();
    
    $newArrivals = \App\Models\Product::with('category')
        ->where('status', 'active')
        ->whereNotNull('image')
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();
    
    return view('landing', compact('curatedProducts', 'newArrivals'));
})->name('home');

// Language Switch
Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

// Bundle routes (Public)
Route::get('/bundles', [\App\Http\Controllers\BundleController::class, 'index'])->name('bundles.index');
Route::get('/bundles/{bundle:slug}', [\App\Http\Controllers\BundleController::class, 'show'])->name('bundles.show');

// Shop routes (Public - everyone can browse)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');

// Membership routes (Public)
Route::get('/membership', [\App\Http\Controllers\MembershipController::class, 'index'])->name('membership.index');

// Search routes (Public)
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');
Route::get('/search/suggestions', [\App\Http\Controllers\SearchController::class, 'suggestions'])->name('search.suggestions');

// Support routes (Public)
Route::prefix('support')->name('support.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SupportController::class, 'index'])->name('index');
    Route::get('/faq', [\App\Http\Controllers\SupportController::class, 'faq'])->name('faq');
    Route::post('/faq/{faq}/feedback', [\App\Http\Controllers\SupportController::class, 'faqFeedback'])->name('faq.feedback');
    Route::get('/contact', [\App\Http\Controllers\SupportController::class, 'contact'])->name('contact');
    Route::get('/ticket/create', [\App\Http\Controllers\SupportController::class, 'createTicket'])->name('ticket.create');
    Route::post('/ticket', [\App\Http\Controllers\SupportController::class, 'storeTicket'])->name('ticket.store');
    Route::get('/ticket/{ticket}', [\App\Http\Controllers\SupportController::class, 'showTicket'])->name('ticket.show');
    Route::match(['get', 'post'], '/ticket/track', [\App\Http\Controllers\SupportController::class, 'trackTicket'])->name('ticket.track');
});

// Support routes (Authenticated)
Route::middleware('auth')->prefix('support')->name('support.')->group(function () {
    Route::get('/tickets', [\App\Http\Controllers\SupportController::class, 'tickets'])->name('tickets');
    Route::post('/ticket/{ticket}/reply', [\App\Http\Controllers\SupportController::class, 'replyTicket'])->name('ticket.reply');
});

// Guest routes (only for non-logged in users)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
    Route::post('/login/otp/verify', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');
    Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->name('login.otp.resend');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password/send-otp', [PasswordResetController::class, 'sendOtp'])->name('password.sendOtp');
    Route::post('/forgot-password/verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.verifyOtp');
    Route::post('/forgot-password/resend-otp', [PasswordResetController::class, 'resendOtp'])->name('password.resendOtp');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');
    
    // Social Login Routes
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
});

// Auth routes (logged in users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/membership/popup/hide', [AuthController::class, 'hideMembershipPopup'])->name('membership.popup.hide');
    Route::post('/membership/popup/dismiss', [AuthController::class, 'dismissMembershipPopup'])->name('membership.popup.dismiss');
    
    // Debug route to test popup
    Route::get('/test-popup', function() {
        session(['show_membership_popup' => true]);
        return redirect('/shop')->with('success', 'Popup session set!');
    });
    
    // Search History
    Route::get('/search/history', [\App\Http\Controllers\SearchController::class, 'history'])->name('search.history');
    Route::post('/search/history/clear', [\App\Http\Controllers\SearchController::class, 'clearHistory'])->name('search.history.clear');
    Route::post('/search/history/remove', [\App\Http\Controllers\SearchController::class, 'removeFromHistory'])->name('search.history.remove');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Payment Routes
    Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::post('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    
    // Coupon Routes
    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
    
    // Membership Routes
    Route::prefix('membership')->name('membership.')->group(function () {
        Route::get('/subscribe/{plan}', [\App\Http\Controllers\MembershipController::class, 'subscribe'])->name('subscribe');
        Route::post('/subscribe/{plan}/process', [\App\Http\Controllers\MembershipController::class, 'processPayment'])->name('process-payment');
        Route::post('/verify-payment', [\App\Http\Controllers\MembershipController::class, 'verifyPayment'])->name('verify-payment');
        Route::get('/success/{subscription}', [\App\Http\Controllers\MembershipController::class, 'success'])->name('success');
        Route::get('/manage', [\App\Http\Controllers\MembershipController::class, 'manage'])->name('manage');
        Route::post('/cancel/{subscription}', [\App\Http\Controllers\MembershipController::class, 'cancel'])->name('cancel');
        Route::post('/toggle-auto-renew/{subscription}', [\App\Http\Controllers\MembershipController::class, 'toggleAutoRenew'])->name('toggle-auto-renew');
    });
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('orders.invoice.download');
    Route::get('/orders/{order}/invoice/view', [\App\Http\Controllers\InvoiceController::class, 'view'])->name('orders.invoice.view');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::post('/profile/toggle-dark-mode', [ProfileController::class, 'toggleDarkMode'])->name('profile.toggle-dark-mode');
    Route::delete('/profile', [ProfileController::class, 'deleteAccount'])->name('profile.delete');
    
    // Addresses
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::put('/profile/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.addresses.delete');
    
    // Payment Methods
    Route::post('/profile/payment-methods', [ProfileController::class, 'storePaymentMethod'])->name('profile.payment-methods.store');
    Route::delete('/profile/payment-methods/{paymentMethod}', [ProfileController::class, 'deletePaymentMethod'])->name('profile.payment-methods.delete');
    
    // Reviews
    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Referral System
    Route::get('/referrals', [\App\Http\Controllers\ReferralController::class, 'index'])->name('referrals.index');
    Route::post('/referrals/apply-code', [\App\Http\Controllers\ReferralController::class, 'applyCode'])->name('referrals.apply-code');
    
    // Bundle Routes
    Route::post('/bundles/{bundle}/add-to-cart', [\App\Http\Controllers\BundleController::class, 'addToCart'])->name('bundles.add-to-cart');
    
    // Stock Notifications (Notify me when back in stock)
    Route::post('/stock-notification', [\App\Http\Controllers\StockNotificationController::class, 'store'])->name('stock-notification.store');
    Route::delete('/stock-notification', [\App\Http\Controllers\StockNotificationController::class, 'destroy'])->name('stock-notification.destroy');
});

// Admin routes
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    
    // Products
    Route::resource('products', ProductController::class)->except(['show']);
    
    // Product Variants
    Route::get('/products/{product}/variants', [\App\Http\Controllers\Admin\ProductVariantController::class, 'index'])->name('products.variants.index');
    Route::get('/products/{product}/variants/create', [\App\Http\Controllers\Admin\ProductVariantController::class, 'create'])->name('products.variants.create');
    Route::post('/products/{product}/variants', [\App\Http\Controllers\Admin\ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::post('/products/{product}/variants/bulk', [\App\Http\Controllers\Admin\ProductVariantController::class, 'bulkCreate'])->name('products.variants.bulk-create');
    Route::get('/products/{product}/variants/{variant}/edit', [\App\Http\Controllers\Admin\ProductVariantController::class, 'edit'])->name('products.variants.edit');
    Route::put('/products/{product}/variants/{variant}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'update'])->name('products.variants.update');
    Route::patch('/products/{product}/variants/{variant}/stock', [\App\Http\Controllers\Admin\ProductVariantController::class, 'updateStock'])->name('products.variants.update-stock');
    Route::delete('/products/{product}/variants/{variant}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'destroy'])->name('products.variants.destroy');
    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    
    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/toggle-dark-mode', [AdminProfileController::class, 'toggleDarkMode'])->name('profile.toggle-dark-mode');
    
    // Site Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
    
    // Hero Banners
    Route::get('/settings/hero-banners', [SettingsController::class, 'heroBanners'])->name('settings.hero-banners');
    Route::get('/settings/hero-banners/create', [SettingsController::class, 'createHeroBanner'])->name('settings.hero-banners.create');
    Route::post('/settings/hero-banners', [SettingsController::class, 'storeHeroBanner'])->name('settings.hero-banners.store');
    Route::get('/settings/hero-banners/{banner}/edit', [SettingsController::class, 'editHeroBanner'])->name('settings.hero-banners.edit');
    Route::put('/settings/hero-banners/{banner}', [SettingsController::class, 'updateHeroBanner'])->name('settings.hero-banners.update');
    Route::delete('/settings/hero-banners/{banner}', [SettingsController::class, 'destroyHeroBanner'])->name('settings.hero-banners.destroy');
    
    // Featured Sections
    Route::get('/settings/featured-sections', [SettingsController::class, 'featuredSections'])->name('settings.featured-sections');
    Route::get('/settings/featured-sections/create', [SettingsController::class, 'createFeaturedSection'])->name('settings.featured-sections.create');
    Route::post('/settings/featured-sections', [SettingsController::class, 'storeFeaturedSection'])->name('settings.featured-sections.store');
    Route::get('/settings/featured-sections/{section}/edit', [SettingsController::class, 'editFeaturedSection'])->name('settings.featured-sections.edit');
    Route::put('/settings/featured-sections/{section}', [SettingsController::class, 'updateFeaturedSection'])->name('settings.featured-sections.update');
    Route::delete('/settings/featured-sections/{section}', [SettingsController::class, 'destroyFeaturedSection'])->name('settings.featured-sections.destroy');
    
    // Shop Banners
    Route::get('/settings/shop-banners', [SettingsController::class, 'shopBanners'])->name('settings.shop-banners');
    Route::get('/settings/shop-banners/create', [SettingsController::class, 'createShopBanner'])->name('settings.shop-banners.create');
    Route::post('/settings/shop-banners', [SettingsController::class, 'storeShopBanner'])->name('settings.shop-banners.store');
    Route::get('/settings/shop-banners/{banner}/edit', [SettingsController::class, 'editShopBanner'])->name('settings.shop-banners.edit');
    Route::put('/settings/shop-banners/{banner}', [SettingsController::class, 'updateShopBanner'])->name('settings.shop-banners.update');
    Route::delete('/settings/shop-banners/{banner}', [SettingsController::class, 'destroyShopBanner'])->name('settings.shop-banners.destroy');
    
    // Footer Settings
    Route::get('/settings/footer', [SettingsController::class, 'footer'])->name('settings.footer');
    Route::post('/settings/footer', [SettingsController::class, 'updateFooter'])->name('settings.footer.update');
    Route::get('/settings/footer-links/create', [SettingsController::class, 'createFooterLink'])->name('settings.footer-links.create');
    Route::post('/settings/footer-links', [SettingsController::class, 'storeFooterLink'])->name('settings.footer-links.store');
    Route::get('/settings/footer-links/{link}/edit', [SettingsController::class, 'editFooterLink'])->name('settings.footer-links.edit');
    Route::put('/settings/footer-links/{link}', [SettingsController::class, 'updateFooterLink'])->name('settings.footer-links.update');
    Route::delete('/settings/footer-links/{link}', [SettingsController::class, 'destroyFooterLink'])->name('settings.footer-links.destroy');
    
    // Payment Settings
    Route::get('/settings/payment', [SettingsController::class, 'payment'])->name('settings.payment');
    Route::post('/settings/payment', [SettingsController::class, 'updatePayment'])->name('settings.payment.update');
    
    // Coupons
    Route::get('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::patch('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle');
    
    // Inventory Management
    Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/alerts', [\App\Http\Controllers\Admin\InventoryController::class, 'alerts'])->name('inventory.alerts');
    Route::patch('/inventory/alerts/{alert}/read', [\App\Http\Controllers\Admin\InventoryController::class, 'markAlertRead'])->name('inventory.alerts.mark-read');
    Route::post('/inventory/alerts/mark-all-read', [\App\Http\Controllers\Admin\InventoryController::class, 'markAllAlertsRead'])->name('inventory.alerts.mark-all-read');
    Route::patch('/inventory/{product}/stock', [\App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::get('/inventory/{product}/notifications', [\App\Http\Controllers\Admin\InventoryController::class, 'notifications'])->name('inventory.notifications');
    
    // Support Management
    Route::get('/support/tickets', [\App\Http\Controllers\Admin\SupportController::class, 'tickets'])->name('support.tickets');
    Route::get('/support/tickets/{ticket}', [\App\Http\Controllers\Admin\SupportController::class, 'showTicket'])->name('support.tickets.show');
    Route::patch('/support/tickets/{ticket}', [\App\Http\Controllers\Admin\SupportController::class, 'updateTicket'])->name('support.tickets.update');
    Route::post('/support/tickets/{ticket}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'replyTicket'])->name('support.tickets.reply');
    
    // FAQ Management
    Route::get('/support/faq/categories', [\App\Http\Controllers\Admin\SupportController::class, 'faqCategories'])->name('support.faq.categories');
    Route::get('/support/faq/categories/create', [\App\Http\Controllers\Admin\SupportController::class, 'createFaqCategory'])->name('support.faq.categories.create');
    Route::post('/support/faq/categories', [\App\Http\Controllers\Admin\SupportController::class, 'storeFaqCategory'])->name('support.faq.categories.store');
    Route::get('/support/faq/categories/{category}/edit', [\App\Http\Controllers\Admin\SupportController::class, 'editFaqCategory'])->name('support.faq.categories.edit');
    Route::put('/support/faq/categories/{category}', [\App\Http\Controllers\Admin\SupportController::class, 'updateFaqCategory'])->name('support.faq.categories.update');
    Route::delete('/support/faq/categories/{category}', [\App\Http\Controllers\Admin\SupportController::class, 'destroyFaqCategory'])->name('support.faq.categories.destroy');
    
    Route::get('/support/faqs', [\App\Http\Controllers\Admin\SupportController::class, 'faqs'])->name('support.faqs');
    Route::get('/support/faqs/create', [\App\Http\Controllers\Admin\SupportController::class, 'createFaq'])->name('support.faqs.create');
    Route::post('/support/faqs', [\App\Http\Controllers\Admin\SupportController::class, 'storeFaq'])->name('support.faqs.store');
    Route::get('/support/faqs/{faq}/edit', [\App\Http\Controllers\Admin\SupportController::class, 'editFaq'])->name('support.faqs.edit');
    Route::put('/support/faqs/{faq}', [\App\Http\Controllers\Admin\SupportController::class, 'updateFaq'])->name('support.faqs.update');
    Route::delete('/support/faqs/{faq}', [\App\Http\Controllers\Admin\SupportController::class, 'destroyFaq'])->name('support.faqs.destroy');
    
    // Live Chat Settings
    Route::get('/support/live-chat', [\App\Http\Controllers\Admin\SupportController::class, 'liveChatSettings'])->name('support.live-chat');
    Route::post('/support/live-chat', [\App\Http\Controllers\Admin\SupportController::class, 'updateLiveChatSettings'])->name('support.live-chat.update');
    
    // Membership Management
    Route::get('/membership/plans', [\App\Http\Controllers\Admin\MembershipController::class, 'plans'])->name('membership.plans');
    Route::get('/membership/plans/create', [\App\Http\Controllers\Admin\MembershipController::class, 'createPlan'])->name('membership.plans.create');
    Route::post('/membership/plans', [\App\Http\Controllers\Admin\MembershipController::class, 'storePlan'])->name('membership.plans.store');
    Route::get('/membership/plans/{plan}/edit', [\App\Http\Controllers\Admin\MembershipController::class, 'editPlan'])->name('membership.plans.edit');
    Route::put('/membership/plans/{plan}', [\App\Http\Controllers\Admin\MembershipController::class, 'updatePlan'])->name('membership.plans.update');
    Route::delete('/membership/plans/{plan}', [\App\Http\Controllers\Admin\MembershipController::class, 'destroyPlan'])->name('membership.plans.destroy');
    
    Route::get('/membership/subscribers', [\App\Http\Controllers\Admin\MembershipController::class, 'subscribers'])->name('membership.subscribers');
    Route::get('/membership/subscribers/{subscription}', [\App\Http\Controllers\Admin\MembershipController::class, 'showSubscriber'])->name('membership.subscribers.show');
    
    Route::get('/membership/sales', [\App\Http\Controllers\Admin\MembershipController::class, 'sales'])->name('membership.sales');
    Route::get('/membership/sales/create', [\App\Http\Controllers\Admin\MembershipController::class, 'createSale'])->name('membership.sales.create');
    Route::post('/membership/sales', [\App\Http\Controllers\Admin\MembershipController::class, 'storeSale'])->name('membership.sales.store');
    Route::get('/membership/sales/{sale}/edit', [\App\Http\Controllers\Admin\MembershipController::class, 'editSale'])->name('membership.sales.edit');
    Route::put('/membership/sales/{sale}', [\App\Http\Controllers\Admin\MembershipController::class, 'updateSale'])->name('membership.sales.update');
    Route::delete('/membership/sales/{sale}', [\App\Http\Controllers\Admin\MembershipController::class, 'destroySale'])->name('membership.sales.destroy');
    
    // Newsletter Management
    Route::get('/newsletter', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');
    Route::get('/newsletter/export', [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletter.export');
    
    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/sales', [\App\Http\Controllers\Admin\AnalyticsController::class, 'salesReport'])->name('analytics.sales');
    Route::get('/analytics/best-selling', [\App\Http\Controllers\Admin\AnalyticsController::class, 'bestSelling'])->name('analytics.best-selling');
    Route::get('/analytics/customers', [\App\Http\Controllers\Admin\AnalyticsController::class, 'customers'])->name('analytics.customers');
    Route::get('/analytics/abandoned-carts', [\App\Http\Controllers\Admin\AnalyticsController::class, 'abandonedCarts'])->name('analytics.abandoned-carts');
    Route::get('/analytics/export-sales', [\App\Http\Controllers\Admin\AnalyticsController::class, 'exportSales'])->name('analytics.export-sales');

    // Product Bundles Management
    Route::get('/bundles', [\App\Http\Controllers\Admin\BundleController::class, 'index'])->name('bundles.index');
    Route::get('/bundles/create', [\App\Http\Controllers\Admin\BundleController::class, 'create'])->name('bundles.create');
    Route::post('/bundles', [\App\Http\Controllers\Admin\BundleController::class, 'store'])->name('bundles.store');
    Route::get('/bundles/{bundle}/edit', [\App\Http\Controllers\Admin\BundleController::class, 'edit'])->name('bundles.edit');
    Route::put('/bundles/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'update'])->name('bundles.update');
    Route::delete('/bundles/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'destroy'])->name('bundles.destroy');
    Route::patch('/bundles/{bundle}/toggle', [\App\Http\Controllers\Admin\BundleController::class, 'toggleStatus'])->name('bundles.toggle');
    Route::get('/bundles/frequently-bought', [\App\Http\Controllers\Admin\BundleController::class, 'frequentlyBought'])->name('bundles.frequently-bought');
    Route::patch('/bundles/frequently-bought/{pair}', [\App\Http\Controllers\Admin\BundleController::class, 'updateFrequentlyBought'])->name('bundles.update-fbt');
    Route::post('/bundles/regenerate-fbt', [\App\Http\Controllers\Admin\BundleController::class, 'regenerateFrequentlyBought'])->name('bundles.regenerate-fbt');

    // Referral Management
    Route::get('/referrals', [\App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/transactions', [\App\Http\Controllers\Admin\ReferralController::class, 'transactions'])->name('referrals.transactions');
    Route::get('/referrals/settings', [\App\Http\Controllers\Admin\ReferralController::class, 'settings'])->name('referrals.settings');
    Route::post('/referrals/settings', [\App\Http\Controllers\Admin\ReferralController::class, 'updateSettings'])->name('referrals.settings.update');
    Route::post('/referrals/users/{user}/adjust-points', [\App\Http\Controllers\Admin\ReferralController::class, 'adjustPoints'])->name('referrals.adjust-points');

    // Newsletter Campaigns
    Route::get('/newsletter/campaigns', [\App\Http\Controllers\Admin\NewsletterController::class, 'campaigns'])->name('newsletter.campaigns');
    Route::get('/newsletter/campaigns/create', [\App\Http\Controllers\Admin\NewsletterController::class, 'createCampaign'])->name('newsletter.campaigns.create');
    Route::post('/newsletter/campaigns', [\App\Http\Controllers\Admin\NewsletterController::class, 'storeCampaign'])->name('newsletter.campaigns.store');
    Route::get('/newsletter/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class, 'showCampaign'])->name('newsletter.campaigns.show');
    Route::get('/newsletter/campaigns/{campaign}/edit', [\App\Http\Controllers\Admin\NewsletterController::class, 'editCampaign'])->name('newsletter.campaigns.edit');
    Route::put('/newsletter/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class, 'updateCampaign'])->name('newsletter.campaigns.update');
    Route::delete('/newsletter/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroyCampaign'])->name('newsletter.campaigns.destroy');
    Route::post('/newsletter/campaigns/{campaign}/send-test', [\App\Http\Controllers\Admin\NewsletterController::class, 'sendTestEmail'])->name('newsletter.campaigns.send-test');
    Route::post('/newsletter/campaigns/{campaign}/send', [\App\Http\Controllers\Admin\NewsletterController::class, 'sendCampaign'])->name('newsletter.campaigns.send');
    Route::get('/newsletter/campaigns/{campaign}/preview', [\App\Http\Controllers\Admin\NewsletterController::class, 'previewCampaign'])->name('newsletter.campaigns.preview');
});
