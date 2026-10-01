<x-layouts.master>
    <!--== Start Page Content Wrapper ==-->
    <div class="page-content-wrapper sp-y">
        <div class="product-details-page-content">
            <div class="container container-wide">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <!-- Start Product Thumbnail Area -->
                            <div class="col-md-5">
                                <div class="product-thumb-area">
                                    <div class="product-details-thumbnail">
                                        <div class="product-thumbnail-slider" id="thumb-gallery">
                                            @foreach($product->getMedia('products') as $image)
                                                <figure class="pro-thumb-item"
                                                        data-mfp-src="{{$image->getUrl()}}">
                                                    <img src="{{$image->getUrl()}}"
                                                         alt="Product Details"/>
                                                </figure>
                                            @endforeach
                                        </div>

                                        <a href="#thumb-gallery" class="btn-large-view btn-gallery-popup">View Larger <i
                                                class="fa fa-search-plus"></i></a>
                                    </div>

                                    <div class="product-details-thumbnail-nav">
                                        @foreach($product->getMedia('products') as $image)
                                            <figure class="pro-thumb-item">
                                                <img src="{{$image->getUrl()}}"
                                                     alt="Product Details"/>
                                            </figure>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <!-- End Product Thumbnail Area -->

                            <!-- Start Product Info Area -->
                            <div class="col-md-7">
                                <div class="product-details-info-content-wrap">
                                    <div class="prod-details-info-content">
                                        <h2>{{$product->title}}</h2>
                                        @if($product->sku)
                                            <p class="mb-2"><strong>SKU:</strong> {{ $product->sku }}</p>
                                        @endif
                                        <p
                                            @class([
                                                'text-success' => $product->in_stock,
                                                'text-danger' => !$product->in_stock,
                                            ])
                                        >
                                            {{$product->in_stock ? 'მარაგშია' : 'არ არის მარაგში'}}
                                        </p>
                                        <h5 class="price"><strong>ფასი:</strong> <span class="price-amount" data-base-price="{{ $product->price }}">{{ $product->price }} ₾</span>
                                        </h5>
                                        <div>
                                            {!! $product->description !!}
                                        </div>

                                        @if($product->in_stock)
                                            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-4 product-add-to-cart-form" data-installation-fee="{{ config('shop.installation_fee') }}">
                                                @csrf

                                                <div id="install-block">
                                                    <span class="r-title">ვიდეო რეგისტრატორის პროფესიონალური მონტაჟი</span>

                                                    <div class="r-cards">
                                                        <label class="r-card" data-value="1">
                                                            <input type="radio" name="with_installation" value="1">
                                                            <span class="r-card-name">მონტაჟის სერვისი</span>
                                                            <span class="r-card-right">
                                                                <span class="r-card-price">{{ config('shop.installation_fee') }}.00 ₾</span>
                                                                <span class="r-card-check">✓</span>
                                                            </span>
                                                        </label>

                                                        <label class="r-card selected" data-value="0">
                                                            <input type="radio" name="with_installation" value="0" checked>
                                                            <span class="r-card-name">მონტაჟის გარეშე</span>
                                                            <span class="r-card-right">
                                                                <span class="r-card-check">✓</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center gap-3 mt-4">
                                                    <input type="number" name="quantity" value="1" min="1" max="99" class="form-control" style="width:100px;">
                                                    <button type="submit" class="btn btn-brand">კალათაში დამატება</button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Info Area -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Page Content Wrapper ==-->

    <script>
        (function () {
            var form = document.querySelector('.product-add-to-cart-form');
            if (!form) return;

            var cards = form.querySelectorAll('#install-block .r-card');
            var priceEl = document.querySelector('.price-amount');
            var fee = parseInt(form.getAttribute('data-installation-fee'), 10) || 0;

            function selectedWithInstallation() {
                var checked = form.querySelector('input[name="with_installation"]:checked');
                return checked && checked.value === '1';
            }

            function updatePrice() {
                if (!priceEl) return;
                var base = parseInt(priceEl.getAttribute('data-base-price'), 10) || 0;
                priceEl.textContent = (base + (selectedWithInstallation() ? fee : 0)) + ' ₾';
            }

            cards.forEach(function (card, idx) {
                setTimeout(function () {
                    card.classList.add('visible', 'animate-in');
                }, idx * 100);

                card.addEventListener('click', function () {
                    cards.forEach(function (c) { c.classList.remove('selected'); });
                    card.classList.add('selected');
                    var radio = card.querySelector('input');
                    if (radio) radio.checked = true;
                    updatePrice();
                });
            });

            updatePrice();
        })();
    </script>
</x-layouts.master>
