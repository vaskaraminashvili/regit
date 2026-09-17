<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BogInstallmentCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_shows_bog_installment_block(): void
    {
        config([
            'services.bog.client_id' => null,
            'services.bog.client_secret' => null,
        ]);
        $user = User::factory()->create();
        $product = $this->product();

        $this->actingAs($user)
            ->post(route('cart.add', $product), ['quantity' => 1])
            ->assertRedirect();

        $this->actingAs($user)
            ->get(route('checkout.create'))
            ->assertOk()
            ->assertSee('განვადება საქართველოს ბანკით')
            ->assertSee('მოითხოვე განვადება')
            ->assertSee('Sandbox / ტესტი')
            ->assertSee('აირჩიეთ განვადების ვადა');
    }

    public function test_checkout_loads_bog_sdk_when_configured(): void
    {
        $this->configureBog();

        $user = User::factory()->create();
        $product = $this->product();

        $this->actingAs($user)
            ->post(route('cart.add', $product), ['quantity' => 1])
            ->assertRedirect();

        $this->actingAs($user)
            ->get(route('checkout.create'))
            ->assertOk()
            ->assertSee('bog-sdk.js', false)
            ->assertSee('მოითხოვე განვადება');
    }

    public function test_installment_checkout_creates_local_and_bog_order(): void
    {
        $this->configureBog();

        Http::fake([
            'oauth2-sandbox.bog.ge/*' => Http::response([
                'access_token' => 'test-token',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ]),
            'oauth2.bog.ge/*' => Http::response([
                'access_token' => 'test-token',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ]),
            'api-sandbox.bog.ge/*' => Http::response([
                'id' => 'bog-order-123',
            ]),
            'api.bog.ge/payments/v1/ecommerce/orders' => Http::response([
                'id' => 'bog-order-123',
            ]),
        ]);

        $user = User::factory()->create();
        $product = $this->product();

        $this->actingAs($user)
            ->post(route('cart.add', $product), ['quantity' => 1])
            ->assertRedirect();

        $this->actingAs($user)
            ->postJson(route('checkout.installment'), [
                'name' => 'Test User',
                'phone' => '555123456',
                'city' => 'Tbilisi',
                'address' => 'Test street 1',
                'month' => 6,
                'discount_code' => 'standard',
            ])
            ->assertOk()
            ->assertJsonPath('orderId', 'bog-order-123')
            ->assertJsonPath('demo', false);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'bog_installment',
            'bog_order_id' => 'bog-order-123',
            'installment_months' => 6,
            'total' => 349,
        ]);
    }

    public function test_sandbox_demo_installment_works_without_credentials(): void
    {
        config([
            'services.bog.client_id' => null,
            'services.bog.client_secret' => null,
            'services.bog.sandbox' => true,
        ]);

        $user = User::factory()->create();
        $product = $this->product();

        $this->actingAs($user)
            ->post(route('cart.add', $product), ['quantity' => 1])
            ->assertRedirect();

        $response = $this->actingAs($user)
            ->postJson(route('checkout.installment'), [
                'name' => 'Test User',
                'phone' => '555123456',
                'city' => 'Tbilisi',
                'address' => 'Test street 1',
                'month' => 6,
            ])
            ->assertOk()
            ->assertJsonPath('demo', true);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'bog_installment',
            'installment_months' => 6,
            'status' => 'processing',
        ]);

        $this->assertStringStartsWith('sandbox-demo-', $response->json('orderId'));
    }

    public function test_bog_callback_marks_paid_installment_as_processing(): void
    {
        config(['services.bog.verify_callback' => false]);

        $user = User::factory()->create();
        $order = Order::query()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => 'bog_installment',
            'payment_status' => 'created',
            'bog_order_id' => 'bog-order-123',
            'total' => 349,
            'name' => 'Test User',
            'phone' => '555123456',
            'city' => 'Tbilisi',
            'address' => 'Test street 1',
        ]);

        $this->postJson(route('bog.callback'), [
            'event' => 'order_payment',
            'body' => [
                'order_id' => 'bog-order-123',
                'external_order_id' => (string) $order->id,
                'order_status' => [
                    'key' => 'completed',
                ],
            ],
        ])->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
            'payment_status' => 'completed',
        ]);
    }

    protected function configureBog(): void
    {
        config([
            'services.bog.client_id' => 'test-client',
            'services.bog.client_secret' => 'test-secret',
            'services.bog.sandbox' => true,
            'services.bog.verify_callback' => false,
        ]);
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
