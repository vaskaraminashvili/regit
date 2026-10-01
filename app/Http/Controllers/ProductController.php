<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('status', true)
            ->get();

        return view('product.index', compact('products'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->status, 404);

        return view('product.show', compact('product'));
    }
}
