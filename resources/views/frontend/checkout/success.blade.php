@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Đặt hàng thành công</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Đặt hàng thành công</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="shop-checkout-complete py-120">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 mx-auto">
                        <div class="checkout-complete-content">
                            <div class="checkout-complete-icon"><i class="far fa-check"></i></div>
                            <h3>Cảm ơn bạn đã đặt hàng!</h3>
                            <p>Đơn hàng của bạn đã được đặt và sẽ được xử lý trong thời gian sớm nhất, nhân viên sẽ sớm liên lạc để xác nhận đơn hàng của bạn!
                            </p>
                            <a href="{{ route("frontend.product.index") }}" class="theme-btn">Quay lại cửa hàng<i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection