<header class="header">
    <div class="header-top">
            <div class="container">
                <div class="header-top-wrapper">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6 col-xl-5">
                            <div class="header-top-left">
                                <ul class="header-top-list">
                                    <li><a href="https://live.themewild.com/cdn-cgi/l/email-protection#1871767e77587d60797568747d367b7775"><i class="far fa-envelopes"></i>
                                            <span class="__cf_email__" data-cfemail="ddb4b3bbb29db8a5bcb0adb1b8f3beb2b0"></span></a></li>
                                    <li><a href="tel:+21236547898"><i class="far fa-headset"></i> +2 123 654
                                            7898</a></li>
                                    <li class="help"><a href="#"><i class="far fa-comment-question"></i> Need Help?</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-7">
                            <div class="header-top-right">
                                <ul class="header-top-list">
                                    <li><a href="#"><i class="far fa-alarm-clock"></i> Daily Deal</a></li>
                                    <li class="account"><a href="#"><i class="far fa-user-vneck"></i> Account</a></li>
                                    <li class="login"><a href="#"><i class="far fa-arrow-right-to-arc"></i> Login</a></li>
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="far fa-usd"></i> USD
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">USD</a>
                                                <a class="dropdown-item" href="#">EUR</a>
                                                <a class="dropdown-item" href="#">AUD</a>
                                                <a class="dropdown-item" href="#">CUD</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="far fa-globe-americas"></i> EN
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">EN</a>
                                                <a class="dropdown-item" href="#">FR</a>
                                                <a class="dropdown-item" href="#">DE</a>
                                                <a class="dropdown-item" href="#">RU</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="social">
                                        <div class="header-top-social">
                                            <span>Follow Us: </span>
                                            <a href="#"><i class="fab fa-facebook"></i></a>
                                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                            <a href="#"><i class="fab fa-linkedin"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="header-middle">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-5 col-lg-3 col-xl-3">
                    <div class="header-middle-logo">
                        <a class="navbar-brand" href="{{ route('frontend.home.index') }}">
                            <h2>Shoes<span style="color:#11B76B">Shop</span></h2>
                        </a>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-6 col-xl-5">
                    <div class="header-middle-search">
                        <form action="{{ route("frontend.product.index") }}">
                            <div class="search-content">
                                <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm...">
                                <button type="submit" class="search-btn"><i class="far fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-7 col-lg-3 col-xl-4">
                    <div class="header-middle-right">
                        <ul class="header-middle-list">
                            @auth
                                <li>
                                    <div class="header-middle-account">
                                        <div class="dropdown">
                                            <div data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{{ auth()->user()->avatar ? auth()->user()->avatar : asset('web-assets/img/account/user.jpg') }}" alt="{{ auth()->user()->name }}">
                                            </div>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <div class="dropdown-user">
                                                        <h5>Xin chào! {{ auth()->user()->name }}</h5>
                                                    </div>
                                                </li>
                                                <li><a class="dropdown-item" href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ của tôi</a></li>
                                                <li><a class="dropdown-item" href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi mật khẩu</a></li>
                                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endauth
                            @auth
                                <li><a href="{{ route('frontend.home.index') }}" class="list-item"><i class="far fa-heart"></i></a></li>
                            @endauth
                            <li><a href="{{ route("frontend.cart.index") }}" class="list-item"><i class="far fa-cart-shopping"></i><span id="cart-item-count">{{ $totalItemsInCart ?? 0 }}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                <a class="navbar-brand" href="{{ route('frontend.home.index') }}">
                    <h2 class="logo-scrolled">Shoes<span style="color:#11B76B">Shop</span></h2>
                </a>
                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <button type="button" class="nav-right-link"><i class="far fa-search"></i></button>
                        <div class="mobile-search-form">
                            <form action="#">
                                <input type="text" class="form-control" placeholder="Tìm kiếm...">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-mobile-icon"><i class="far fa-bars"></i></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route("frontend.home.index") }}">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route("frontend.product.index") }}">Sản phẩm</a></li>
                        @auth
                            <li class="nav-item"><a class="nav-link" href="{{ route('frontend.home.index') }}">Yêu thích</a></li>
                        @endauth
                        <li class="nav-item"><a class="nav-link" href="{{ route("frontend.post.index") }}">Bài viết</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route("frontend.contact.index") }}">Liên hệ</a></li>
                    </ul>
                    @guest
                        <div class="nav-right">
                            <div class="nav-right-btn">
                                <a href="{{ route('login') }}" class="theme-btn">Đăng nhập</a>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>
    </div>
</header>