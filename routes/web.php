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

// Shop routes (Public - everyone can browse)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');

// Guest routes (only for non-logged in users)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
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
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
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
});
