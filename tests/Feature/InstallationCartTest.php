<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallationCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_shows_installation_options(): void
    {
        $product = $this->product();

        $this->get(route('product.show', $product))
            ->assertOk()
            ->assertSee('მონტაჟის სერვისი')
            ->assertSee('მონტაჟის გარეშე')
            ->assertSee('100.00 ₾');
    }

    public function test_adding_product_with_installation_includes_fee_in_cart_and_order(): void
    {
        $product = $this->product();
        $user = User::factory()->create();

        $this->post(route('cart.add', $product), [
            'quantity' => 1,
            'with_installation' => 1,
        ])->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('მონტაჟით')
            ->assertSee('449 ₾');

        $this->actingAs($user)
            ->post(route('checkout.store'), [
                'name' => 'Test User',
                'phone' => '555123456',
                'city' => 'Tbilisi',
                'address' => 'Test street 1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'with_installation' => 1,
            'price' => 449,
            'line_total' => 449,
        ]);
    }

    public function test_adding_product_without_installation_keeps_base_price(): void
    {
        $product = $this->product();

        $this->post(route('cart.add', $product), [
            'quantity' => 1,
            'with_installation' => 0,
        ])->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('მონტაჟის გარეშე')
            ->assertSee('349 ₾')
            ->assertDontSee('449 ₾');
    }

    protected function product(): Product
    {
        return Product::query()->create([
            'title' => 'Test Camera',
            'description' => 'Test description',
            'price' => 349,
            'in_stock' => true,
            'status' => true,
            'sku' => 'CAM-TEST',
        ]);
    }
}
