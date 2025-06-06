@extends('layout/web_layout')
@section('content')
    <main class="main">

        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Liên hệ</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Liên hệ</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="contact-area pt-100 pb-10">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="contact-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="contact-info">
                                            <div class="contact-info-icon">
                                                <i class="fal fa-map-location-dot"></i>
                                            </div>
                                            <div class="contact-info-content">
                                                <h5>Địa chỉ văn phòng</h5>
                                                <p>Số 99, đường BBB, Vinh, Nghệ An, Việt Nam</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-info">
                                            <div class="contact-info-icon">
                                                <i class="fal fa-headset"></i>
                                            </div>
                                            <div class="contact-info-content">
                                                <h5>Gọi cho chúng tôi</h5>
                                                <p>+84904786893</p>
                                                <p>+84987654321</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-info">
                                            <div class="contact-info-icon">
                                                <i class="fal fa-envelopes"></i>
                                            </div>
                                            <div class="contact-info-content">
                                                <h5>Email</h5>
                                                <p>vanhoa12092003@gmail.com</p>
                                                <p>anhphuc2003@gmail.comm</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-info">
                                            <div class="contact-info-icon">
                                                <i class="fal fa-alarm-clock"></i>
                                            </div>
                                            <div class="contact-info-content">
                                                <h5>Giờ mở cửa</h5>
                                                <p>T2 - T7 (10AM - 05PM)</p>
                                                <p>CN - <span class="text-danger">Đóng</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="contact-form">
                                <div class="contact-form-header">
                                    <h2>Liên hệ với chúng tôi</h2>
                                    <p>Đây là một thực tế đã được thiết lập từ lâu rằng người đọc sẽ bị phân tâm bởi nội dung
                                        dễ đọc của một trang, ngay cả khi chỉ nhìn vào bố cục của nó.</p>
                                </div>
                                <form method="post" action="{{ route('frontend.contact.send') }}" id="contact-form">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="message" cols="30" rows="4" class="form-control" placeholder="Nhập nội dung phản hồi"></textarea>
                                    </div>
                                    <button type="submit" class="theme-btn">Gửi tin nhắn <i class="far fa-paper-plane"></i></button>
                                    <div class="col-md-12 my-3">
                                        @if(session('success'))
                                            <div class="form-messege text-success">{{ session('success') }}</div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.110348171744!2d105.69316941046928!3d18.659043582388414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cddf0bf20f23%3A0x86154b56a284fa6d!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBWaW5o!5e0!3m2!1svi!2s!4v1747152688538!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </main>
@endsection