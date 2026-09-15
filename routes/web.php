<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [ProductController::class, 'index']);
Route::get('/installment', [PagesController::class, 'installment'])->name('pages.installment');
Route::get('/contact', [PagesController::class, 'contact'])->name('pages.contact');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/installation', [PagesController::class, 'installation'])->name('pages.installation');

Route::get('/clear-cache', function(){
     Artisan::call('storage:link');
});
