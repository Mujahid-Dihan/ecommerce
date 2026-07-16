<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;

use App\Http\Controllers\Frontend\ShopController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');

Route::get('/dashboard', function () {
    return inertia('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================================
// ADMIN ROUTES
// ========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Brands
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

    // Attributes & Colors
    Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class)->except(['create', 'show', 'edit']);
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class)->except(['create', 'show', 'edit']);

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{subOrder}/sub-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateSubOrderStatus'])->name('orders.update-sub-status');
    Route::put('/orders/{order}/payment', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');

    // Customers
    Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{customer}/toggle-ban', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleBan'])->name('customers.toggle-ban');

    // Sellers
    Route::get('/sellers', [\App\Http\Controllers\Admin\SellerController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{seller}', [\App\Http\Controllers\Admin\SellerController::class, 'show'])->name('sellers.show');
    Route::put('/sellers/{seller}/approve', [\App\Http\Controllers\Admin\SellerController::class, 'approve'])->name('sellers.approve');
    Route::put('/sellers/{seller}/reject', [\App\Http\Controllers\Admin\SellerController::class, 'reject'])->name('sellers.reject');
    Route::put('/sellers/{seller}/commission', [\App\Http\Controllers\Admin\SellerController::class, 'updateCommission'])->name('sellers.update-commission');

    // Refunds
    Route::get('/refunds', [\App\Http\Controllers\Admin\RefundController::class, 'index'])->name('refunds.index');
    Route::put('/refunds/{refund}/approve', [\App\Http\Controllers\Admin\RefundController::class, 'approve'])->name('refunds.approve');
    Route::put('/refunds/{refund}/reject', [\App\Http\Controllers\Admin\RefundController::class, 'reject'])->name('refunds.reject');

    // POS
    Route::get('/pos', [\App\Http\Controllers\Admin\PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [\App\Http\Controllers\Admin\PosController::class, 'searchProducts'])->name('pos.search');
    Route::post('/pos/checkout', [\App\Http\Controllers\Admin\PosController::class, 'checkout'])->name('pos.checkout');

    // Flash Deals
    Route::resource('flash-deals', \App\Http\Controllers\Admin\FlashDealController::class)->except(['show', 'edit', 'update']);

    // Coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['show']);

    // Newsletter
    Route::get('/newsletter', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');

    // Blog
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class)->except(['show']);
    Route::get('/blog-categories', [\App\Http\Controllers\Admin\BlogController::class, 'categories'])->name('blog.categories');
    Route::post('/blog-categories', [\App\Http\Controllers\Admin\BlogController::class, 'storeCategory'])->name('blog.categories.store');
    Route::delete('/blog-categories/{category}', [\App\Http\Controllers\Admin\BlogController::class, 'destroyCategory'])->name('blog.categories.destroy');

    // Support
    Route::get('/support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticket}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('support.reply');
    Route::put('/support/{ticket}/status', [\App\Http\Controllers\Admin\SupportController::class, 'updateStatus'])->name('support.update-status');

    // Staff & Roles
    Route::get('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('/staff/roles', [\App\Http\Controllers\Admin\StaffController::class, 'roles'])->name('staff.roles');
    Route::post('/staff/roles', [\App\Http\Controllers\Admin\StaffController::class, 'storeRole'])->name('staff.roles.store');
    Route::delete('/staff/roles/{role}', [\App\Http\Controllers\Admin\StaffController::class, 'destroyRole'])->name('staff.roles.destroy');

    // Reports
    Route::get('/reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/stock', [\App\Http\Controllers\Admin\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/wishlist', [\App\Http\Controllers\Admin\ReportController::class, 'wishlist'])->name('reports.wishlist');
    Route::get('/reports/commission', [\App\Http\Controllers\Admin\ReportController::class, 'commission'])->name('reports.commission');

    // Settings
    Route::get('/settings/general', [\App\Http\Controllers\Admin\SettingController::class, 'general'])->name('settings.general');
    Route::post('/settings/general', [\App\Http\Controllers\Admin\SettingController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'payment'])->name('settings.payment');
    Route::post('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'updatePayment'])->name('settings.payment.update');
    Route::get('/settings/smtp', [\App\Http\Controllers\Admin\SettingController::class, 'smtp'])->name('settings.smtp');
    Route::post('/settings/smtp', [\App\Http\Controllers\Admin\SettingController::class, 'updateSmtp'])->name('settings.smtp.update');
    Route::get('/settings/features', [\App\Http\Controllers\Admin\SettingController::class, 'features'])->name('settings.features');
    Route::post('/settings/features', [\App\Http\Controllers\Admin\SettingController::class, 'updateFeatures'])->name('settings.features.update');

    // Pages
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except(['show']);

    // Shipping
    Route::get('/shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::post('/shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'store'])->name('shipping.store');
    Route::put('/shipping/{method}', [\App\Http\Controllers\Admin\ShippingController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping/{method}', [\App\Http\Controllers\Admin\ShippingController::class, 'destroy'])->name('shipping.destroy');
});

// ========================================
// SELLER ROUTES
// ========================================
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);

    // Seller Orders
    Route::get('/orders', [\App\Http\Controllers\Seller\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Seller\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Seller\OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// ========================================
// AUTH / PROFILE ROUTES
// ========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
