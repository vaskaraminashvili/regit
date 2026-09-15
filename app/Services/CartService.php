<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    public const SESSION_KEY = 'cart';

    public function items(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return (int) array_sum($this->items());
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->items();
        $productId = (string) $product->id;
        $cart[$productId] = ($cart[$productId] ?? 0) + max(1, $quantity);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = $this->items();
        $productId = (string) $product->id;

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(Product $product): void
    {
        $cart = $this->items();
        unset($cart[(string) $product->id]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @return Collection<int, array{product: Product, quantity: int, line_total: int}>
     */
    public function detailedItems(): Collection
    {
        $cart = $this->items();

        if ($cart === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('status', true)
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function (int $quantity, string $productId) use ($products) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => (int) $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function total(): int
    {
        return (int) $this->detailedItems()->sum('line_total');
    }

    public function merge(array $guestCart): void
    {
        $cart = $this->items();

        foreach ($guestCart as $productId => $quantity) {
            $productId = (string) $productId;
            $cart[$productId] = ($cart[$productId] ?? 0) + (int) $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }
}
