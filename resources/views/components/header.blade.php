<!--== Start Header Area ==-->
<header class="header-area">
    <div class="container container-wide">
        <div class="row align-items-center">
            <div class="col-2 col-sm-4 col-lg-2">
                <div class="site-logo text-center text-sm-start">
                    <a href="{{ route('home') }}"><img src="{{asset('assets/logo.png')}}" class="logo_img img-fluid" alt="Logo"/></a>
                </div>
            </div>

            <div class="col-8 col-lg-6 offset-lg-0">
                <div class="site-navigation ms-4 ms-lg-0">
                    <ul class="main-menu nav">
                        <li><a href="{{ route('home') }}">მთავარი</a></li>
                        <li><a href="{{route('pages.installation')}}">მონტაჟის სერვისი</a></li>
                        <li><a href="{{route('pages.installment')}}">განვადება</a></li>
                        <li><a href="{{route('pages.contact')}}">კონტაქტი</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-2 col-sm-4 col-lg-4">
                <div class="site-action d-flex justify-content-end align-items-center gap-2">
                    <ul class="login-reg-nav nav d-none d-md-flex align-items-center">
                        @auth
                            <li class="dropdown user-account-dropdown">
                                <a href="#"
                                   class="dropdown-toggle user-account-toggle"
                                   id="userAccountDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    {{ auth()->user()->name }}
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end user-account-menu" aria-labelledby="userAccountDropdown">
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}">შეკვეთები</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">პროფილი</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item logout-dropdown-btn">
                                                <i class="ion-power"></i> გასვლა
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">შესვლა</a></li>
                            <li><a href="{{ route('register') }}">რეგისტრაცია</a></li>
                        @endauth
                    </ul>

                    <div class="mini-cart-wrap">
                        <a href="{{ route('cart.index') }}" class="btn-mini-cart">
                            <i class="ion-bag"></i>
                            <span class="cart-total">{{ $cartCount ?? 0 }}</span>
                        </a>
                    </div>

                    <div class="responsive-menu d-lg-none">
                        <button class="btn-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--== End Header Area ==-->
