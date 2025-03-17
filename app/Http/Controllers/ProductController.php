<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->where('status', 1)
            ->get();
        return view('product.index', compact('products'));
    }

    public function show($product)
    {
        $product = Product::query()
            ->where('id', $product)
            ->where('status', 1)
            ->first();

        return view('product.show', compact('product'));
    }
}
