<x-layouts.master>
    <!--== Start Slider Area Wrapper ==-->
    <div class="slider-area-wrapper home-2">
        <div class="slider-content-active">
            <div class="slider-slide-item bg-img">
                <div class="container-fluid h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-lg-12 order-0 order-lg-1 h-100 ">
                            <div class="slide-img h-100">
                                <img class="img-fluid w-100 h-100" src="assets/banner1.jpg" alt="Slider"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-slide-item bg-img">
                <div class="container-fluid h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-lg-12 order-0 order-lg-1 h-100">
                            <div class="slide-img h-100">
                                <img class="img-fluid w-100 h-100" src="assets/banner2.jpg" alt="Slider"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-dots-arrow-area">
            <div class="container container-wide">
                <div class="slider-dots-arrow">
                </div>
            </div>
        </div>
    </div>
    <!--== End Slider Area Wrapper ==-->

    <!--== Start Products Area Wrapper ==-->
    <div class="products-area-wrapper sm-top mb-5">
        <div class="container container-wide">
            <div class="row">
                <div class="col-lg-5 m-auto text-center">
                    <div class="section-title">
                        <h2 class="h3">All Of Our Products</h2>
                        <p>All best seller product are now available for you and your can buy
                            this product from here any time any where so sop now</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($products as $product)
                    <!-- Start Product Item -->
                    <div class="col-xl-4">
                        <div class="product-item">
                            <div class="product-item__thumb">
                                <a href="{{route('product.show', ['product' => $product->id])}}">
                                    <img class="thumb-primary"
                                         src="{{$product->getFirstMediaUrl('products')}}"
                                         alt="Product"/>
                                </a>
                            </div>

                            <div class="product-item__content">
                                <div class="ratting">
                                    <span><i class="ion-android-star"></i></span>
                                    <span><i class="ion-android-star"></i></span>
                                    <span><i class="ion-android-star"></i></span>
                                    <span><i class="ion-android-star"></i></span>
                                    <span><i class="ion-android-star"></i></span>
                                </div>
                                <h4 class="title"><a
                                        href="{{route('product.show', ['product' => $product->id])}}">{{$product->title}}</a>
                                </h4>
                                <span class="price">სრულად ნახვა</span>
                            </div>


                        </div>
                    </div>
                    <!-- End Product Item -->
                @endforeach

            </div>
        </div>
    </div>
    <!--== End Products Area Wrapper ==-->


</x-layouts.master>
