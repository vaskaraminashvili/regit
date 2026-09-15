<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {
    }

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->detailedItems(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(AddToCartRequest $request, Product $product): RedirectResponse
    {
        abort_unless($product->status && $product->in_stock, 404);

        $this->cart->add($product, (int) $request->validated('quantity', 1));

        return redirect()
            ->route('cart.index')
            ->with('success', 'პროდუქტი დაემატა კალათაში.');
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $this->cart->update($product, (int) $request->validated('quantity'));

        return redirect()
            ->route('cart.index')
            ->with('success', 'კალათა განახლდა.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return redirect()
            ->route('cart.index')
            ->with('success', 'პროდუქტი წაიშალა კალათიდან.');
    }
}
