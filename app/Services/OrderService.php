<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected CartService $cart
    ) {}

    public function placeOrder(User $user, array $data): Order
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

        return DB::transaction(function () use ($user, $data, $items) {
            $order = Order::query()->create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $items->sum('line_total'),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'notes' => $data['notes'] ?? null,
            ]);

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

            $this->cart->clear();

            return $order->load('items');
        });
    }
}
