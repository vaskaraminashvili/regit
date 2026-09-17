<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-3">განვადება ვერ დასრულდა</h2>
                    <p class="mb-4">შეკვეთა #{{ $order->id }} განვადებით ვერ გაიფორმა. შეგიძლიათ სცადოთ თავიდან ან დაადასტუროთ ჩვეულებრივი შეკვეთა.</p>
                    <p>ჯამი: <strong>{{ $order->total }} ₾</strong></p>
                    <p>სტატუსი:
                        <span class="order-status order-status--{{ $order->status }}">
                            {{ __("order_status.{$order->status}") }}
                        </span>
                    </p>

                    <a href="{{ route('orders.index') }}" class="btn btn-brand mt-3">ჩემი შეკვეთები</a>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary mt-3">შეკვეთის ნახვა</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.master>
