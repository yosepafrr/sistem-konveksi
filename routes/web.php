<?php

use App\Livewire\ProfitTracker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopeeController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profit-tracker', ProfitTracker::class)->name('profit.tracker');
});


// SHOPEE AUTHORIZATION
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/connect/shopee', [ShopeeController::class, 'redirectToShopee'])->name('shopee.connect');
    Route::get('/shopee/callback', [ShopeeController::class, 'handleShopeeCallback'])->name('shopee.callback');
});

// GET SHOPEE ORDERS
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/shopee/orders', [ShopeeController::class, 'getShopeeOrders'])->name('shopee.orders');
});

require __DIR__ . '/auth.php';
