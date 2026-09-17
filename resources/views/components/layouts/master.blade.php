<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>პრემიუმ კლასის ვიდეო რეგისტრატორები - პროფესიონალური მონტაჟის სერვისი</title>
    <meta name="description" content="შეიძინეთ უმაღლესი ხარისხის ვიდეო რეგისტრატორები პროფესიონალური მონტაჟით. ხარისხი, საიმედოობა და მოწინავე ტექნოლოგიები ერთად.">
    <meta name="keywords" content="ვიდეო რეგისტრატორები, CCTV, ვიდეო დაკვირვება, სამონტაჟო სერვისი, HD ვიდეო, IP კამერები, უსაფრთხოების სისტემები">

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://regit.ge/">
    <meta property="og:title" content="პრემიუმ კლასის ვიდეო რეგისტრატორები - პროფესიონალური მონტაჟის სერვისი">
    <meta property="og:description" content="შეიძინეთ უმაღლესი ხარისხის ვიდეო რეგისტრატორები პროფესიონალური მონტაჟით.">
    <meta property="og:image" content="https://regit.ge/assets/logo.png">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://regit.ge/">
    <meta property="twitter:title" content="პრემიუმ კლასის ვიდეო რეგისტრატორები - პროფესიონალური მონტაჟის სერვისი">
    <meta property="twitter:description" content="შეიძინეთ უმაღლესი ხარისხის ვიდეო რეგისტრატორები პროფესიონალური მონტაჟით.">
    <meta property="twitter:image" content="https://regit.ge/assets/logo.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://regit.ge/">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Structured Data / Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "ვიდეო რეგისტრატორების პროფესიონალური მონტაჟი",
      "image": "https://regit.ge/images/logo.jpg",
      "@id": "https://regit.ge/",
      "url": "https://regit.ge/",
      "telephone": "+995 595 710 005",
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday"
        ],
        "opens": "09:00",
        "closes": "18:00"
      }
    }
    </script>

    <!-- Additional SEO Elements -->
    <meta name="author" content="regit">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <meta name="geo.region" content="GE">
    <meta name="geo.placename" content="Tbilisi">


    <!--== Favicon ==-->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"/>

    <!--== Google Fonts ==-->
    <link
        href="https://fonts.googleapis.com/css?family=Oswald:400,500,600,700%7CPoppins:400,400i,500,600&amp;display=swap"
        rel="stylesheet">

    <!-- build:css assets/css/app.min.css -->
    <!--== Leaflet Min CSS ==-->
    <link href="{{asset('/assets/css/leaflet.min.css')}}" rel="stylesheet"/>
    <!--== Nice Select Min CSS ==-->
    <link href="{{asset('/assets/css/nice-select.min.css')}}" rel="stylesheet"/>
    <!--== Slick Slider Min CSS ==-->
    <link href="{{asset('/assets/css/slick.min.css')}}" rel="stylesheet"/>
    <!--== Magnific Popup Min CSS ==-->
    <link href="{{asset('/assets/css/magnific-popup.min.css')}}" rel="stylesheet"/>
    <!--== Slicknav Min CSS ==-->
    <link href="{{asset('assets/css/slicknav.min.css')}}" rel="stylesheet"/>
    <!--== Animate Min CSS ==-->
    <link href="{{asset('assets/css/animate.min.css')}}" rel="stylesheet"/>
    <!--== Ionicons Min CSS ==-->
    <link href="{{asset('assets/css/ionicons.min.css')}}" rel="stylesheet"/>
    <!--== Font-Awesome Min CSS ==-->
    <link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <!--== Bootstrap Min CSS ==-->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet"/>

    <!--== Main Style CSS ==-->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
    <!--== Helper Min CSS ==-->
    <link href="{{asset('assets/css/helper.min.css')}}" rel="stylesheet"/>
    <!-- endbuild -->

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet"/>

    @stack('styles')
</head>

<body>

<x-header/>

{{ $slot }}
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
                    <a href="{{ route('home') }}"><img src="{{asset('assets/logo.png')}}" class="img-fluid" alt="Logo"/></a>
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
<script src="{{asset('assets/js/modernizr-3.6.0.min.js')}}"></script>
<!--=== jQuery Min Js ===-->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<!--=== jQuery Migration Min Js ===-->
<script src="{{asset('assets/js/jquery-migrate.min.js')}}"></script>
<!--=== Popper Min Js ===-->
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<!--=== Bootstrap Min Js ===-->
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<!--=== Slicknav Min Js ===-->
<script src="{{asset('assets/js/jquery.slicknav.min.js')}}"></script>
<!--=== Magnific Popup Min Js ===-->
<script src="{{asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
<!--=== Slick Slider Min Js ===-->
<script src="{{asset('assets/js/slick.min.js')}}"></script>
<!--=== Nice Select Min Js ===-->
<script src="{{asset('assets/js/jquery.nice-select.min.js')}}"></script>
<!--=== Leaflet Min Js ===-->
<script src="{{asset('assets/js/leaflet.min.js')}}"></script>
<!--=== Countdown Js ===-->
<script src="{{asset('assets/js/countdown.js')}}"></script>

<!--=== Active Js ===-->
<script src="{{asset('assets/js/active.js')}}"></script>
<!-- endbuild -->

</body>

</html>
