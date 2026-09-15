<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected OrderService $orders
    ) {
    }

    public function create(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'კალათა ცარიელია.');
        }

        return view('checkout.create', [
            'items' => $this->cart->detailedItems(),
            'total' => $this->cart->total(),
            'user' => auth()->user(),
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $order = $this->orders->placeOrder($request->user(), $request->validated());

        return redirect()
            ->route('orders.thank-you', $order)
            ->with('success', 'შეკვეთა წარმატებით გაფორმდა.');
    }

    public function thankYou(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items');

        return view('checkout.thank-you', compact('order'));
    }
}
