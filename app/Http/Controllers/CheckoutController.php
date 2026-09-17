<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Http\Requests\Checkout\StoreInstallmentCheckoutRequest;
use App\Models\Order;
use App\Services\BogPaymentService;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected OrderService $orders,
        protected BogPaymentService $bog
    ) {}

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
            'bogConfigured' => $this->bog->isConfigured(),
            'bogClientId' => config('services.bog.client_id'),
            'bogSandbox' => $this->bog->isSandbox(),
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $order = $this->orders->placeOrder($request->user(), $request->validated());

        return redirect()
            ->route('orders.thank-you', $order)
            ->with('success', 'შეკვეთა წარმატებით გაფორმდა.');
    }

    public function storeInstallment(StoreInstallmentCheckoutRequest $request): JsonResponse
    {
        $result = $this->orders->placeInstallmentOrder($request->user(), $request->validated());

        return response()->json([
            'orderId' => $result['bog_order_id'],
            'demo' => $result['demo'],
            'redirectUrl' => route('orders.thank-you', $result['order']),
        ]);
    }

    public function installmentFail(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items');

        return view('checkout.fail', compact('order'));
    }

    public function thankYou(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items');

        return view('checkout.thank-you', compact('order'));
    }
}
