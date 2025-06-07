@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Hồ sơ cá nhân</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Hồ sơ cá nhân</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="user-area bg py-100">
            <div class="container">
                @if (session('messenger'))
                    <div class="alert alert-{{ session('messenger.style') }} alert-dismissible fade show" role="alert" id="session-alert">
                        {{ session('messenger.msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                            style="box-shadow: none; outline: none; border: none;">
                        </button>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-3">
                        <div class="sidebar">
                            <div class="sidebar-top">
                                <div class="sidebar-profile-img">
                                    <img id="holder" src="">
                                    <button type="button" id="lfm" data-input="thumbnail" data-preview="holder" class="profile-img-btn"><i class="far fa-camera"></i></button>
                                </div>
                                <h5>{{ auth()->user()->name }}</h5>
                                <p><a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a></p>
                            </div>
                            <ul class="sidebar-list">
                                <li><a class="active" href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ cá nhân</a></li>
                                <li><a href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="user-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="user-card">
                                        <h4 class="user-card-title">Thông tin cá nhân</h4>
                                        <div class="user-form">
                                            <form action="{{ route('frontend.update-profile') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Họ Tên</label>
                                                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" placeholder="VD: Võ Văn Hòa">
                                                            @error('name')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" placeholder="VD: vanhoa12092003@gmail.com">
                                                            @error('email')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Số Điện Thoại</label>
                                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="VD: 0349191123">
                                                            @error('phone')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Địa Chỉ</label>
                                                            <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}" placeholder="VD: 123 - Trần Phú - Tp. Vinh - Nghệ An">
                                                            @error('address')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Mô Tả</label>
                                                            <textarea name="description" class="form-control" placeholder="VD: Thích đọc sách, chơi thể thao">{{ old('description', auth()->user()->description) }}</textarea>
                                                            @error('description')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="avatar" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                                    </div>
                                                </div>
                                                <button type="submit" class="theme-btn">Lưu thay đổi</button>
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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                let alert = document.getElementById('session-alert');
                if (alert) {
                    let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    </script>
@endsection
