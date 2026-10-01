<?php

use App\Http\Controllers\BogCallbackController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/installment', [PagesController::class, 'installment'])->name('pages.installment');
Route::get('/contact', [PagesController::class, 'contact'])->name('pages.contact');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/installation', [PagesController::class, 'installation'])->name('pages.installation');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/bog/callback', BogCallbackController::class)->name('bog.callback');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/installment', [CheckoutController::class, 'storeInstallment'])->name('checkout.installment');
    Route::get('/checkout/installment/{order}/fail', [CheckoutController::class, 'installmentFail'])->name('checkout.installment.fail');
    Route::get('/orders/{order}/thank-you', [CheckoutController::class, 'thankYou'])->name('orders.thank-you');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/clear-cache', function () {
    Artisan::call('storage:link');
});

require __DIR__.'/auth.php';
