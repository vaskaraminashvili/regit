<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">კალათა</h2>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($items->isEmpty())
                        <p>კალათა ცარიელია.</p>
                        <a href="{{ route('home') }}" class="btn btn-brand">პროდუქტების ნახვა</a>
                    @else
                        <div class="shopping-cart-list-area">
                            <div class="shopping-cart-table table-responsive">
                                <table class="table table-bordered text-center mb-0">
                                    <thead>
                                    <tr>
                                        <th>პროდუქტი</th>
                                        <th>ფასი</th>
                                        <th>რაოდენობა</th>
                                        <th>ჯამი</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                        @php($product = $item['product'])
                                        <tr>
                                            <td class="product-list">
                                                <div class="cart-product-item d-flex align-items-center">
                                                    <div class="remove-icon">
                                                        <form method="POST" action="{{ route('cart.remove', $product) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="with_installation" value="{{ $item['with_installation'] ? 1 : 0 }}">
                                                            <button type="submit"><i class="fa fa-trash-o"></i></button>
                                                        </form>
                                                    </div>
                                                    <a href="{{ route('product.show', $product) }}" class="product-thumb">
                                                        @if($product->getFirstMediaUrl('products'))
                                                            <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->title }}" style="max-width:70px;">
                                                        @endif
                                                    </a>
                                                    <a href="{{ route('product.show', $product) }}" class="product-name">
                                                        {{ $product->title }}
                                                        @if($product->sku)
                                                            <br><small>SKU: {{ $product->sku }}</small>
                                                        @endif
                                                        <br>
                                                        <x-installation-status :with-installation="$item['with_installation']" />
                                                    </a>
                                                </div>
                                            </td>
                                            <td><span class="price">{{ $item['unit_price'] }} ₾</span></td>
                                            <td>
                                                <form method="POST" action="{{ route('cart.update', $product) }}" class="d-inline-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="with_installation" value="{{ $item['with_installation'] ? 1 : 0 }}">
                                                    <input type="number" name="quantity" min="1" max="99" value="{{ $item['quantity'] }}" class="form-control" style="width:80px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">განახლება</button>
                                                </form>
                                            </td>
                                            <td><span class="price">{{ $item['line_total'] }} ₾</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="cart-calculate-area mt-4">
                                <h4>ჯამი: {{ $total }} ₾</h4>
                                <a href="{{ route('checkout.create') }}" class="btn btn-brand mt-3">შეკვეთის გაფორმება</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.master>
