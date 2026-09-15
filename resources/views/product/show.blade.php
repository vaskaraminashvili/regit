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
                                        <h5 class="price"><strong>ფასი:</strong> <span class="price-amount">{{$product->price}} ₾</span>
                                        </h5>
                                        <div>
                                            {!! $product->description !!}
                                        </div>

                                        @if($product->in_stock)
                                            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-4 d-flex align-items-center gap-3">
                                                @csrf
                                                <input type="number" name="quantity" value="1" min="1" max="99" class="form-control" style="width:100px;">
                                                <button type="submit" class="btn btn-brand">კალათაში დამატება</button>
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
    
</x-layouts.master>
