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
        return $this->normalized(Session::get(self::SESSION_KEY, []));
    }

    public function count(): int
    {
        return (int) collect($this->items())->sum('quantity');
    }

    public function add(Product $product, int $quantity = 1, bool $withInstallation = false): void
    {
        $cart = $this->items();
        $key = $this->itemKey($product->id, $withInstallation);

        $cart[$key] = [
            'product_id' => (int) $product->id,
            'quantity' => ($cart[$key]['quantity'] ?? 0) + max(1, $quantity),
            'with_installation' => $withInstallation,
        ];

        $this->save($cart);
    }

    public function update(Product $product, int $quantity, bool $withInstallation = false): void
    {
        $cart = $this->items();
        $key = $this->itemKey($product->id, $withInstallation);

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key] = [
                'product_id' => (int) $product->id,
                'quantity' => $quantity,
                'with_installation' => $withInstallation,
            ];
        }

        $this->save($cart);
    }

    public function remove(Product $product, bool $withInstallation = false): void
    {
        $cart = $this->items();
        unset($cart[$this->itemKey($product->id, $withInstallation)]);
        $this->save($cart);
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
     * @return Collection<int, array{product: Product, quantity: int, with_installation: bool, unit_price: int, line_total: int}>
     */
    public function detailedItems(): Collection
    {
        $cart = $this->items();

        if ($cart === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', collect($cart)->pluck('product_id'))
            ->where('status', true)
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function (array $item) use ($products) {
                $product = $products->get((int) $item['product_id']);

                if (! $product) {
                    return null;
                }

                $withInstallation = (bool) $item['with_installation'];
                $quantity = (int) $item['quantity'];
                $unitPrice = $product->unitPrice($withInstallation);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'with_installation' => $withInstallation,
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice * $quantity,
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
        foreach ($this->normalized($guestCart) as $item) {
            $product = Product::query()->find($item['product_id']);

            if (! $product) {
                continue;
            }

            $this->add($product, (int) $item['quantity'], (bool) $item['with_installation']);
        }
    }

    protected function itemKey(int|string $productId, bool $withInstallation): string
    {
        return $productId.':'.($withInstallation ? '1' : '0');
    }

    /**
     * @param  array<string|int, mixed>  $cart
     * @return array<string, array{product_id: int, quantity: int, with_installation: bool}>
     */
    protected function normalized(array $cart): array
    {
        $normalized = [];

        foreach ($cart as $key => $value) {
            if (is_int($value) || (is_numeric($value) && ! is_array($value))) {
                $productId = (int) $key;
                $normalized[$this->itemKey($productId, false)] = [
                    'product_id' => $productId,
                    'quantity' => (int) $value,
                    'with_installation' => false,
                ];

                continue;
            }

            if (! is_array($value)) {
                continue;
            }

            $withInstallation = (bool) ($value['with_installation'] ?? false);
            $productId = (int) ($value['product_id'] ?? explode(':', (string) $key)[0]);

            $normalized[$this->itemKey($productId, $withInstallation)] = [
                'product_id' => $productId,
                'quantity' => (int) ($value['quantity'] ?? 0),
                'with_installation' => $withInstallation,
            ];
        }

        return array_filter(
            $normalized,
            fn (array $item) => $item['product_id'] > 0 && $item['quantity'] > 0
        );
    }

    /**
     * @param  array<string, array{product_id: int, quantity: int, with_installation: bool}>  $cart
     */
    protected function save(array $cart): void
    {
        Session::put(self::SESSION_KEY, $this->normalized($cart));
    }
}
