@extends('layout/web_layout')

@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Hồ sơ của tôi</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Hồ sơ của tôi</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="user-area bg py-100">
            <div class="container">
                @if (session('messenge2'))
                    <div class="alert alert-{{ session('messenge.style') }}">
                        {{ session('messenge.msg') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-3">
                        <div class="sidebar">
                            <div class="sidebar-top">
                                <div class="sidebar-profile-img">
                                    <img id="holder" src="">
                                </div>
                                <h5>{{ auth()->user()->name }}</h5>
                                <p><a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a></p>
                            </div>
                            <ul class="sidebar-list">
                                <li><a href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ của tôi</a></li>
                                <li><a class="active" href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi Mật Khẩu</a></li>
                                <li><a href="{{ route('frontend.my-favourite') }}"><i class="far fa-heart"></i> Danh sách yêu thích</a></li>
                                <li><a href="{{ route('frontend.mydocument') }}"><i class="far fa-upload"></i> Danh sách tài liệu</a></li>
                                <li><a href="{{ route('frontend.tran-history') }}"><i class="far fa-money-bill-transfer"></i> Lịch sử giao dịch</a></li>
                                <li><a href="{{ route('frontend.point') }}"><i class="far fa-coins"></i> Nạp coin</a></li>
                                <li><a href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="user-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="user-card">
                                        <h4 class="user-card-title">Đổi Mật Khẩu</h4>
                                        <div class="user-form">
                                            <form action="{{ route('frontend.update-password') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Mật Khẩu Cũ</label>
                                                            <input type="password" name="old_password" class="form-control" placeholder="Mật Khẩu Cũ" required>
                                                            @error('old_password')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Mật Khẩu Mới</label>
                                                            <input type="password" name="new_password" class="form-control" placeholder="Mật Khẩu Mới" required>
                                                            @error('new_password')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                            @error('new_password_confirmation')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Nhập Lại Mật Khẩu</label>
                                                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Nhập Lại Mật Khẩu" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input type="hidden" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                                    </div>
                                                </div>
                                                <button type="submit" class="theme-btn"><span class="far fa-key"></span> Đổi Mật Khẩu</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection