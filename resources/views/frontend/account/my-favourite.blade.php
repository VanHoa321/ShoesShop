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
                            <li><a href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi Mật Khẩu</a></li>
                            <li><a class="active" href="{{ route('frontend.my-favourite') }}"><i class="far fa-heart"></i> Danh sách yêu thích</a></li>
                            <li><a href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="user-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="user-card">
                                    <h4 class="user-card-title">Danh sách tài liệu yêu thích</h4>
                                    <div class="row mt-20">
                                        @if ($favourites->count() > 0)
                                        @foreach ($favourites as $item)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    @if($item->product->is_sale)
                                                    <span class="type hot">Giảm giá</span>
                                                    @endif
                                                    <a href="{{ route("frontend.product.details", $item->product->id) }}"><img src="{{ $item->product->thumbnail }}" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action ms-3">
                                                            <a class="mb-2" href="{{ route("frontend.product.details", $item->product->id) }}" data-tooltip="tooltip" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                                            <a class="mb-2 remove-favourite" href="javascript:void(0);" data-id="{{ $item->product->id }}" data-tooltip="tooltip" title="Bỏ yêu thích">
                                                                <i class="far fa-heart-broken"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="{{ route('frontend.product.details', $item->product->id) }}">{{ $item->product->name }}</a></h3>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            @if($item->product->discount > 0)
                                                            <del>{{ number_format($item->product->price, 0, ',', '.') }} <sup>đ</sup></del>
                                                            @endif
                                                            <span>{{ number_format($item->product->price - $item->product->discount, 0, ',', '.') }} <sup>đ</sup></span>
                                                        </div>
                                                    </div>
                                                    @if ($item->product->rating_count > 0)
                                                    <div class="shop-single-rating">
                                                        @php
                                                        $integer_rating = floor($item->product->average_rating);
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <=$integer_rating)
                                                            <i class="fas fa-star" style="color: #ffc107;"></i>
                                                            @else
                                                            <i class="far fa-star" style="color: #ffc107;"></i>
                                                            @endif
                                                            @endfor
                                                    </div>
                                                    @else
                                                    <div class="product-rate">
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    @endif

                                                    <div class="col-md-12">
                                                        <input type="hidden" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="pagination-area mb-0">
                                            {{ $favourites->links('vendor.pagination.bootstrap-4') }}
                                        </div>
                                        @else
                                        <p>Chưa có sản phẩm yêu thích!</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="avatar" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const removeFavouriteButtons = document.querySelectorAll('.remove-favourite');

        removeFavouriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const url = `/account/favourite/${productId}`;

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Bạn muốn bỏ yêu thích sản phẩm này?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, bỏ yêu thích!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        'Thành công!',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                         window.location.href = "/account/my-favourite";
                                    });
                                } else {
                                    Swal.fire(
                                        'Lỗi!',
                                        data.message,
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire(
                                    'Lỗi!',
                                    'Đã xảy ra lỗi, vui lòng thử lại!',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    });
</script>
@endsection