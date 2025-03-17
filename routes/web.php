<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index']);
Route::get('/contact', [PagesController::class, 'contact'])->name('pages.contact');
Route::get('/{product}', [ProductController::class, 'show'])->name('product.show');

