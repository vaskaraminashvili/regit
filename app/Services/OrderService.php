<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class OrderService
{
    public function __construct(
        protected CartService $cart,
        protected BogPaymentService $bog
    ) {}

    public function placeOrder(User $user, array $data): Order
    {
        $items = $this->cartItemsReadyForCheckout();

        return DB::transaction(function () use ($user, $data, $items) {
            $order = $this->createOrder($user, $data, $items, [
                'status' => 'pending',
                'payment_method' => 'standard',
            ]);

            $this->cart->clear();

            return $order->load('items');
        });
    }

    public function placeInstallmentOrder(User $user, array $data): array
    {
        $demo = ! $this->bog->isConfigured();

        if ($demo && ! $this->bog->isSandbox()) {
            throw ValidationException::withMessages([
                'installment' => 'განვადება ამჟამად მიუწვდომელია.',
            ]);
        }

        $items = $this->cartItemsReadyForCheckout();
        $months = (int) $data['month'];
        $discountCode = filled($data['discount_code'] ?? null) ? (string) $data['discount_code'] : 'standard';

        return DB::transaction(function () use ($user, $data, $items, $months, $discountCode, $demo) {
            $order = $this->createOrder($user, $data, $items, [
                'status' => $demo ? 'processing' : 'pending',
                'payment_method' => 'bog_installment',
                'payment_status' => $demo ? 'completed' : 'created',
                'installment_months' => $months,
                'installment_type' => $discountCode,
            ]);

            try {
                $bogOrderId = $demo
                    ? 'sandbox-demo-'.Str::uuid()
                    : $this->bog->createInstallmentOrder(
                        $this->bogOrderPayload($order),
                        $months,
                        $discountCode
                    );
            } catch (RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'installment' => $exception->getMessage(),
                ]);
            }

            $order->update([
                'bog_order_id' => $bogOrderId,
            ]);

            $this->cart->clear();

            return [
                'order' => $order->fresh('items'),
                'bog_order_id' => $bogOrderId,
                'demo' => $demo,
            ];
        });
    }

    public function applyBogCallback(array $payload): void
    {
        $bogOrderId = $payload['body']['order_id'] ?? null;

        if (! is_string($bogOrderId) || $bogOrderId === '') {
            return;
        }

        $order = Order::query()->where('bog_order_id', $bogOrderId)->first();

        if (! $order) {
            $externalId = $payload['body']['external_order_id'] ?? null;

            if (filled($externalId)) {
                $order = Order::query()->find($externalId);
            }
        }

        if (! $order) {
            return;
        }

        $paymentStatus = data_get($payload, 'body.order_status.key');

        if (! is_string($paymentStatus) || $paymentStatus === '') {
            return;
        }

        $order->update([
            'payment_status' => $paymentStatus,
            'status' => match ($paymentStatus) {
                'completed' => 'processing',
                'rejected', 'refunded' => 'cancelled',
                default => $order->status,
            },
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{product: \App\Models\Product, quantity: int, with_installation: bool, unit_price: int, line_total: int}>
     */
    protected function cartItemsReadyForCheckout()
    {
        $items = $this->cart->detailedItems();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'კალათა ცარიელია.',
            ]);
        }

        foreach ($items as $item) {
            if (! $item['product']->in_stock) {
                throw ValidationException::withMessages([
                    'cart' => "პროდუქტი \"{$item['product']->title}\" არ არის მარაგში.",
                ]);
            }
        }

        return $items;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array{product: \App\Models\Product, quantity: int, with_installation: bool, unit_price: int, line_total: int}>  $items
     */
    protected function createOrder(User $user, array $data, $items, array $attributes): Order
    {
        $order = Order::query()->create(array_merge([
            'user_id' => $user->id,
            'total' => $items->sum('line_total'),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'notes' => $data['notes'] ?? null,
        ], $attributes));

        foreach ($items as $item) {
            $product = $item['product'];

            $order->items()->create([
                'product_id' => $product->id,
                'sku' => $product->sku,
                'title' => $product->title,
                'with_installation' => $item['with_installation'],
                'price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'line_total' => $item['line_total'],
            ]);
        }

        return $order;
    }

    protected function bogOrderPayload(Order $order): array
    {
        $order->loadMissing('items');

        return [
            'callback_url' => route('bog.callback'),
            'external_order_id' => (string) $order->id,
            'purchase_units' => [
                'currency' => 'GEL',
                'total_amount' => $order->total,
                'basket' => $order->items->map(fn ($item) => [
                    'product_id' => (string) ($item->product_id ?: $item->id),
                    'description' => $item->title.($item->with_installation ? ' (მონტაჟით)' : ''),
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->line_total,
                ])->values()->all(),
            ],
            'redirect_urls' => [
                'success' => route('orders.thank-you', $order),
                'fail' => route('checkout.installment.fail', $order),
            ],
            'buyer' => [
                'full_name' => $order->name,
                'masked_phone' => $order->phone,
            ],
        ];
    }
}
