@extends('layout/web_layout')

@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Đổi mật khẩu</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Đổi mật khẩu</li>
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
                                </div>
                                <h5>{{ auth()->user()->name }}</h5>
                                <p><a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a></p>
                            </div>
                            <ul class="sidebar-list">
                                <li><a href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ cá nhân</a></li>
                                <li><a class="active" href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi Mật Khẩu</a></li>
                                <li><a href="{{ route('frontend.my-favourite') }}"><i class="far fa-heart"></i> Danh sách yêu thích</a></li>
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
                                            <form id="quickForm" action="{{ route('frontend.update-password') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Mật Khẩu Cũ</label>
                                                            <input type="password" name="old_password" value="{{ old("old_password") }}" class="form-control" placeholder="Mật Khẩu Cũ">
                                                            @error('old_password')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Mật Khẩu Mới</label>
                                                            <input type="password" id="new_password" name="new_password" value="{{ old("new_password") }}" class="form-control" placeholder="Mật Khẩu Mới">
                                                            @error('new_password')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Nhập Lại Mật Khẩu</label>
                                                            <input type="password" name="new_password_confirmation" value="{{ old("new_password_confirmation") }}" class="form-control" placeholder="Nhập Lại Mật Khẩu">
                                                            @error('new_password_confirmation')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
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
@section('scripts')
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script>
        $(function () {
            $('#quickForm').validate({
                rules: {
                    old_password: {
                        required: true
                    },
                    new_password: {
                        required: true,
                        minlength: 6,
                        maxlength: 50
                    },
                    new_password_confirmation: {
                        required: true,
                        equalTo: '#new_password'
                    }
                },
                messages: {
                    old_password: {
                        required: "Mật khẩu cũ không được để trống!"
                    },
                    new_password: {
                        required: "Mật khẩu mới không được để trống!",
                        minlength: "Mật khẩu mới phải có ít nhất {0} ký tự!",
                        maxlength: "Mật khẩu mới tối đa {0} ký tự!"
                    },
                    new_password_confirmation: {
                        required: "Xác nhận mật khẩu không được để trống!",
                        equalTo: "Xác nhận mật khẩu không khớp với mật khẩu mới!"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection