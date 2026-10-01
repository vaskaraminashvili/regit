<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h2 class="mb-0">შეკვეთა #{{ $order->id }}</h2>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">ყველა შეკვეთა</a>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1"><strong>თარიღი:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                        <p class="mb-1">
                            <strong>სტატუსი:</strong>
                            <span class="order-status order-status--{{ $order->status }}">
                                {{ __("order_status.{$order->status}") }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>გადახდა:</strong>
                            @if($order->payment_method === 'bog_installment')
                                განვადება საქართველოს ბანკით
                                @if($order->installment_months)
                                    ({{ $order->installment_months }} თვე)
                                @endif
                            @else
                                სტანდარტული შეკვეთა
                            @endif
                        </p>
                        <p class="mb-1"><strong>მიმღები:</strong> {{ $order->name }}, {{ $order->phone }}</p>
                        <p class="mb-0"><strong>მისამართი:</strong> {{ $order->city }}, {{ $order->address }}</p>
                        @if($order->notes)
                            <p class="mt-2 mb-0"><strong>შენიშვნა:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>პროდუქტი</th>
                                <th>SKU</th>
                                <th>ფასი</th>
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
                                    <td>{{ $item->price }} ₾</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->line_total }} ₾</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">სულ</th>
                                <th>{{ $order->total }} ₾</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.master>
