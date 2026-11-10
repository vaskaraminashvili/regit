<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TbcInstallmentService
{
    public function isConfigured(): bool
    {
        return filled(config('services.tbc.api_key'))
            && filled(config('services.tbc.api_secret'))
            && filled(config('services.tbc.merchant_key'))
            && filled(config('services.tbc.campaign_id'));
    }

    /**
     * Creates the application and returns [sessionId, redirectUrl].
     *
     * @return array{0: string, 1: string}
     */
    public function createApplication(Order $order): array
    {
        $order->loadMissing('items');

        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->timeout(20)
            ->post($this->baseUrl().'/v1/online-installments/applications', [
                'merchantKey' => config('services.tbc.merchant_key'),
                'campaignId' => (string) config('services.tbc.campaign_id'),
                'invoiceId' => (string) $order->id,
                'priceTotal' => $order->total,
                'products' => $order->items->map(fn ($item) => [
                    'name' => $item->title,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ])->values()->all(),
            ]);

        $sessionId = $response->json('sessionId');
        $redirectUrl = $response->header('Location');

        if ($response->failed() || ! filled($sessionId) || ! filled($redirectUrl)) {
            throw new RuntimeException($response->json('detail') ?: $response->json('title') ?: 'TBC განვადების განაცხადი ვერ შეიქმნა.');
        }

        return [(string) $sessionId, $redirectUrl];
    }

    protected function accessToken(): string
    {
        // Docs say expires_in is ~90 days (ms); cache far shorter to stay safe.
        return Cache::remember('tbc.access_token.'.(config('services.tbc.sandbox') ? 'test' : 'live'), 3600, function () {
            $response = Http::asForm()
                ->withBasicAuth(config('services.tbc.api_key'), config('services.tbc.api_secret'))
                ->timeout(15)
                ->post($this->baseUrl().'/oauth/token', [
                    'grant_type' => 'client_credentials',
                    'scope' => 'online_installments',
                ]);

            $token = $response->json('access_token');

            if ($response->failed() || ! is_string($token) || $token === '') {
                throw new RuntimeException('TBC-სთან ავტორიზაცია ვერ მოხერხდა.');
            }

            return $token;
        });
    }

    protected function baseUrl(): string
    {

        return config('services.tbc.sandbox') ? 'https://test-api.tbcbank.ge' : 'https://api.tbcbank.ge';
    }
}
