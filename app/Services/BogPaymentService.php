<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class BogPaymentService
{
    public function isConfigured(): bool
    {
        return filled(config('services.bog.client_id')) && filled(config('services.bog.client_secret'));
    }

    public function createInstallmentOrder(array $payload, int $months, ?string $discountCode = null): string
    {
        $loan = [
            'type' => filled($discountCode) ? $discountCode : 'standard',
            'month' => $months,
        ];

        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->withHeaders([
                'Accept-Language' => 'ka',
                'Idempotency-Key' => (string) Str::uuid(),
            ])
            ->timeout(20)
            ->post($this->ordersUrl(), array_merge($payload, [
                'payment_method' => ['bog_loan'],
                'config' => [
                    'loan' => $loan,
                ],
            ]));

        if ($response->failed()) {
            throw new RuntimeException($this->errorMessage($response->json(), $response->body()));
        }

        $orderId = $response->json('id');

        if (! is_string($orderId) || $orderId === '') {
            throw new RuntimeException('ბანკიდან შეკვეთის იდენტიფიკატორი ვერ მოვიდა.');
        }

        return $orderId;
    }

    public function signatureIsValid(string $payload, ?string $signature): bool
    {
        if (! config('services.bog.verify_callback')) {
            return true;
        }

        if (! filled($signature)) {
            return false;
        }

        $publicKey = openssl_pkey_get_public((string) config('services.bog.callback_public_key'));

        if ($publicKey === false) {
            return false;
        }

        $decoded = base64_decode($signature, true);

        if ($decoded === false) {
            return false;
        }

        return openssl_verify($payload, $decoded, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    protected function accessToken(): string
    {
        return Cache::remember('bog.access_token.'.($this->isSandbox() ? 'sandbox' : 'live'), 50, function () {
            $response = Http::asForm()
                ->withBasicAuth(
                    (string) config('services.bog.client_id'),
                    (string) config('services.bog.client_secret')
                )
                ->timeout(15)
                ->post($this->tokenUrl(), [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->failed()) {
                throw new RuntimeException('საქართველოს ბანკთან ავტორიზაცია ვერ მოხერხდა.');
            }

            $token = $response->json('access_token');

            if (! is_string($token) || $token === '') {
                throw new RuntimeException('საქართველოს ბანკთან ავტორიზაცია ვერ მოხერხდა.');
            }

            return $token;
        });
    }

    public function tokenUrl(): string
    {
        if (filled(config('services.bog.token_url'))) {
            return (string) config('services.bog.token_url');
        }

        return $this->isSandbox()
            ? 'https://oauth2-sandbox.bog.ge/auth/realms/bog/protocol/openid-connect/token'
            : 'https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token';
    }

    public function ordersUrl(): string
    {
        if (filled(config('services.bog.orders_url'))) {
            return (string) config('services.bog.orders_url');
        }

        return $this->isSandbox()
            ? 'https://api-sandbox.bog.ge/payments/v1/ecommerce/orders'
            : 'https://api.bog.ge/payments/v1/ecommerce/orders';
    }

    public function isSandbox(): bool
    {
        return (bool) config('services.bog.sandbox');
    }

    protected function errorMessage(mixed $json, string $fallback): string
    {
        if (is_array($json)) {
            foreach (['message', 'error_description', 'title', 'detail'] as $key) {
                if (isset($json[$key]) && is_string($json[$key]) && $json[$key] !== '') {
                    return $json[$key];
                }
            }
        }

        return $fallback !== '' ? $fallback : 'განვადების შეკვეთა ვერ შეიქმნა.';
    }
}
