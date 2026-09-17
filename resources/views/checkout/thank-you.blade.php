<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-3">მადლობა შეკვეთისთვის!</h2>
                    <p class="mb-4">შეკვეთის ნომერი: #{{ $order->id }}</p>
                    <p>ჯამი: <strong>{{ $order->total }} ₾</strong></p>
                    <p>სტატუსი:
                        <span class="order-status order-status--{{ $order->status }}">
                            {{ __("order_status.{$order->status}") }}
                        </span>
                    </p>

                    <div class="table-responsive mt-4 text-start">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>პროდუქტი</th>
                                <th>SKU</th>
                                <th>რაოდენობა</th>
                                <th>ჯამი</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->title }}
                                        <br>
                                        <x-installation-status :with-installation="$item->with_installation" />
                                    </td>
                                    <td>{{ $item->sku ?? '—' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->line_total }} ₾</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ route('orders.index') }}" class="btn btn-brand mt-3">ჩემი შეკვეთები</a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">მთავარზე დაბრუნება</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.master>
