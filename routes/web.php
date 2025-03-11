<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::all();
    return view('welcome' , compact('products'));
});


Route::get('/{product}', function ($product) {
    $product = Product::find($product);

    return view('product', compact('product'));
})->name('product');
