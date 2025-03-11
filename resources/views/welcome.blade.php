<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Home 2 :: Lukas - Car Parts Store eCommerce HTML Template</title>

    <!--== Favicon ==-->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />

    <!--== Google Fonts ==-->
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,500,600,700%7CPoppins:400,400i,500,600&amp;display=swap" rel="stylesheet">

    <!-- build:css assets/css/app.min.css -->
    <!--== Leaflet Min CSS ==-->
    <link href="assets/css/leaflet.min.css" rel="stylesheet" />
    <!--== Nice Select Min CSS ==-->
    <link href="assets/css/nice-select.min.css" rel="stylesheet" />
    <!--== Slick Slider Min CSS ==-->
    <link href="assets/css/slick.min.css" rel="stylesheet" />
    <!--== Magnific Popup Min CSS ==-->
    <link href="assets/css/magnific-popup.min.css" rel="stylesheet" />
    <!--== Slicknav Min CSS ==-->
    <link href="assets/css/slicknav.min.css" rel="stylesheet" />
    <!--== Animate Min CSS ==-->
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <!--== Ionicons Min CSS ==-->
    <link href="assets/css/ionicons.min.css" rel="stylesheet" />
    <!--== Font-Awesome Min CSS ==-->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <!--== Bootstrap Min CSS ==-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!--== Main Style CSS ==-->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!--== Helper Min CSS ==-->
    <link href="assets/css/helper.min.css" rel="stylesheet" />
    <!-- endbuild -->

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <x-header/>

    <!--== Start Slider Area Wrapper ==-->
    <div class="slider-area-wrapper home-2">
        <div class="slider-content-active">
            <div class="slider-slide-item bg-img" >
                <div class="container-fluid h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-lg-12 order-0 order-lg-1">
                            <div class="slide-img">
                                <img src="assets/banner1.jpg" alt="Slider" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-slide-item bg-img" >
                <div class="container-fluid h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-lg-12 order-0 order-lg-1">
                            <div class="slide-img">
                                <img src="assets/banner2.jpg" alt="Slider" />
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
                            <a href="{{route('product', ['product' => $product->id])}}">
                                <img class="thumb-primary" src="assets/img/product/{{$product->id}}/{{$product->images['product_images'][0]}}" alt="Product" />
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
                            <h4 class="title"><a href="{{route('product', ['product' => $product->id])}}">{{$product->title}}</a></h4>
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


    <x-footer/>

    <!-- Scroll Top Button -->
    <button class="btn-scroll-top"><i class="ion-chevron-up"></i></button>


    <!--== Start Responsive Menu Wrapper ==-->
    <aside class="off-canvas-wrapper off-canvas-menu">
        <div class="off-canvas-overlay"></div>
        <div class="off-canvas-inner">
            <!-- Start Off Canvas Content -->
            <div class="off-canvas-content">
                <div class="off-canvas-header">
                    <div class="logo">
                        <a href="index.html"><img src="assets/logo.png" class="img-fluid w-50" alt="Logo" /></a>
                    </div>
                    <div class="close-btn">
                        <button class="btn-close"><i class="ion-android-close"></i></button>
                    </div>
                </div>

                <!-- Content Auto Generate Form Main Menu Here -->
                <div class="res-mobile-menu mobile-menu">

                </div>
            </div>
        </div>
    </aside>
    <!--== End Responsive Menu Wrapper ==-->


    <!--=======================Javascript============================-->
    <!-- build:js assets/js/app.min.js -->
    <!--=== Modernizr Min Js ===-->
    <script src="assets/js/modernizr-3.6.0.min.js"></script>
    <!--=== jQuery Min Js ===-->
    <script src="assets/js/jquery.min.js"></script>
    <!--=== jQuery Migration Min Js ===-->
    <script src="assets/js/jquery-migrate.min.js"></script>
    <!--=== Popper Min Js ===-->
    <script src="assets/js/popper.min.js"></script>
    <!--=== Bootstrap Min Js ===-->
    <script src="assets/js/bootstrap.min.js"></script>
    <!--=== Slicknav Min Js ===-->
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <!--=== Magnific Popup Min Js ===-->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!--=== Slick Slider Min Js ===-->
    <script src="assets/js/slick.min.js"></script>
    <!--=== Nice Select Min Js ===-->
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <!--=== Leaflet Min Js ===-->
    <script src="assets/js/leaflet.min.js"></script>
    <!--=== Countdown Js ===-->
    <script src="assets/js/countdown.js"></script>

    <!--=== Active Js ===-->
    <script src="assets/js/active.js"></script>
    <!-- endbuild -->

</body>

</html>
