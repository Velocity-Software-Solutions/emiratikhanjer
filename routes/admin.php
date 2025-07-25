<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingOptionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderItemController;

Route::middleware(['auth', IsAdmin::class])->group(function () {

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Route::get('admin/category', [CategoryController::class, 'index']);
    // Route::resource('admin.category', CategoryController::class);

});
Route::prefix('admin')->as('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('shippingOptions', ShippingOptionController::class);
    Route::resource('orders', OrderController::class);
    
    
});
