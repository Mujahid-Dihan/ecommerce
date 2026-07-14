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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class)->except(['create', 'show', 'edit']);
});

Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/fix-html', function () {
    $content = file_get_contents('C:\Users\hp\.gemini\antigravity-ide\brain\d0f3a8b8-1ba8-4b43-b3ec-14284325468e\.system_generated\steps\241\content.md');

    preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches);
    if (empty($matches)) {
        return "No body found";
    }

    $bodyContent = $matches[1];

    // Fix unclosed tags
    $bodyContent = preg_replace('/<img([^>]*[^\/])>/is', '<img$1 />', $bodyContent);
    $bodyContent = preg_replace('/<input([^>]*[^\/])>/is', '<input$1 />', $bodyContent);
    $bodyContent = preg_replace('/<br>/i', '<br />', $bodyContent);
    $bodyContent = preg_replace('/<hr>/i', '<hr />', $bodyContent);
    $bodyContent = preg_replace('/<link([^>]*[^\/])>/is', '<link$1 />', $bodyContent);
    $bodyContent = preg_replace('/<meta([^>]*[^\/])>/is', '<meta$1 />', $bodyContent);

    // Remove scripts
    $bodyContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $bodyContent);

    // Replace Brand
    $bodyContent = str_replace('Ryans', 'Electronics Dokan', $bodyContent);
    $bodyContent = str_replace('RYANS', 'ELECTRONICS DOKAN', $bodyContent);
    $bodyContent = str_replace('ryans.com', 'electronicsdokan.com', $bodyContent);

    file_put_contents(base_path('ryans_fixed.html'), $bodyContent);
    return "HTML fixed and saved to ryans_fixed.html";
});

require __DIR__ . '/auth.php';
