<?php

namespace App\Http\Controllers;

use App\Services\BogPaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class BogCallbackController extends Controller
{
    public function __construct(
        protected BogPaymentService $bog,
        protected OrderService $orders
    ) {}

    public function __invoke(Request $request): Response
    {
        $payload = $request->getContent();

        if (! $this->bog->signatureIsValid($payload, $request->header('Callback-Signature'))) {
            Log::warning('Invalid BOG callback signature.');

            return response('Invalid signature', 400);
        }

        $data = $request->json()->all();

        if (($data['event'] ?? null) !== 'order_payment') {
            return response('OK', 200);
        }

        $this->orders->applyBogCallback($data);

        return response('OK', 200);
    }
}
