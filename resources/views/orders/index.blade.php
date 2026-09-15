<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">ჩემი შეკვეთები</h2>

                    @if($orders->isEmpty())
                        <p>შეკვეთები ჯერ არ გაქვთ.</p>
                        <a href="{{ route('home') }}" class="btn btn-brand">პროდუქტების ნახვა</a>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead>
                                <tr>
                                    <th>შეკვეთა</th>
                                    <th>თარიღი</th>
                                    <th>პროდუქტები</th>
                                    <th>ჯამი</th>
                                    <th>სტატუსი</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ $order->items->sum('quantity') }}</td>
                                        <td>{{ $order->total }} ₾</td>
                                        <td>
                                            <span class="order-status order-status--{{ $order->status }}">
                                                {{ __("order_status.{$order->status}") }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-brand">დეტალები</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.master>
