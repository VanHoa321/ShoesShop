@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Đặt hàng</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Đặt hàng</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="shop-checkout py-100">
            <div class="container">
                @if (session('messenger'))
                    <div class="alert alert-{{ session('messenger.style') }} alert-dismissible fade show" role="alert" id="session-alert">
                        {{ session('messenger.msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                            style="box-shadow: none; outline: none; border: none;">
                        </button>
                    </div>
                @endif
                <div class="shop-checkout-wrap">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="shop-checkout-form">
                                <form id="quickForm" action="{{ route("frontend.checkout.store")}}" method="POST">
                                    @csrf
                                    <h5 class="mb-4">Thông tin giao hàng</h5>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Họ và tên</label>
                                                <input type="text" name="customer_name" value="{{ Auth::user()->name ?? "" }}" class="form-control" placeholder="Họ và tên của bạn" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Số điện thoại</label>
                                                <input type="number" name="customer_phone" value="{{ Auth::user()->phone ?? "" }}" class="form-control" placeholder="Số điện thoại" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="customer_email" value="{{ Auth::user()->email ?? "" }}" class="form-control" placeholder="Địa chỉ Email" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Địa chỉ nhận hàng</label>
                                                <input type="text" name="customer_address" value="{{ Auth::user()->address ?? "" }}" class="form-control" placeholder="Số nhà, tên đường, phường/xã" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Ghi chú cho đơn hàng (Không bắt buộc)</label>
                                                <textarea name="notes" cols="30" rows="4" class="form-control" placeholder="Ghi chú thêm"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-4 mb-4">Phương thức thanh toán</h5>
                                    <div class="shop-checkout-payment">
                                        <ul class="nav nav-pills mb-2">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pills-tab-1" data-bs-toggle="pill" data-bs-target="#pills-1" type="button" role="tab" aria-controls="pills-1" aria-selected="true" data-payment-method="cod">
                                                    <div class="checkout-payment-img mb-2">
                                                        <img style="width:78px" src="/web-assets/img/payment/cod.png" alt="">
                                                    </div>
                                                    <span>Thanh toán khi nhận hàng (COD)</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-tab-2" data-bs-toggle="pill" data-bs-target="#pills-2" type="button" role="tab" aria-controls="pills-2" aria-selected="false" data-payment-method="momo">
                                                    <div class="checkout-payment-img mb-2">
                                                        <img style="width:60px" src="/web-assets/img/payment/momo.png" alt="">
                                                    </div>
                                                    <span>Thanh toán qua Momo</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="d-none">
                                            <input type="radio" name="payment_method" value="cod" id="payment-cod" checked>
                                            <input type="radio" name="payment_method" value="momo" id="payment-momo">
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route("frontend.cart.index") }}" class="theme-btn"><i class="fas fa-arrow-left-long"></i> Giỏ hàng</a>
                                        <button type="submit" class="theme-btn">Đặt hàng<i class="fas fa-arrow-right-long"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h5 class="mb-4" style="margin-left:18px">Thông tin đơn hàng</h5>
                            <div class="shop-cart-summary">
                                <h5>Danh sách sản phẩm</h5>
                                @foreach ($cartItems as $item)
                                    <div class="product-item-row" style="display: flex; align-items: center; margin-bottom: 15px;">
                                        <div class="product-thumbnail-wrapper" style="position: relative; margin-right: 15px;">
                                            <img src="{{ $item['thumbnail'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                        </div>

                                        <div class="product-details" style="flex-grow: 1;">
                                            <div class="product-name" style="font-weight: normal; font-size: 1rem; line-height: 1.2;">
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="product-variant" style="color: #6c757d; font-size: 0.875rem; line-height: 1.2;">
                                                {{ $item['color'] }} / {{ $item['size'] }}
                                            </div>
                                            <div class="product-sku" style="color: #6c757d; font-size: 0.875rem; line-height: 1.2;">
                                                {{ $item['quantity'] }}
                                            </div>
                                        </div>

                                        <div class="product-price" style="margin-left: auto; font-weight: normal; font-size: 1rem;">
                                            {{ number_format($item['total'], 0, ',', '.') }} đ
                                        </div>
                                    </div>
                                @endforeach
                                <h5>Tổng tiền đơn hàng</h5>
                                <ul>
                                    <li><strong>Thành tiền:</strong> <span>{{ number_format(collect($cartItems)->sum('total'), 0, ',', '.') }} đ</span></li>
                                    <li><strong>Giảm giá:</strong> <span>{{ session('voucher') ? number_format(session('voucher.discount'), 0, ',', '.') . ' đ' : '0 đ' }}</span></li>
                                    <li><strong>Phí ship:</strong> <span>Miễn phí</span></li>
                                    <li class="shop-cart-total"><strong>Tổng tiền:</strong> <span>{{ number_format(collect($cartItems)->sum('total') - (session('voucher') ? session('voucher.discount') : 0), 0, ',', '.') }} đ</span></li>
                                </ul>
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
        $(document).ready(function() {
            function selectPaymentMethod(paymentMethod) {
                $(`#payment-${paymentMethod}`).prop('checked', true);
            }

            const activePaymentMethod = $('.nav-link.active').data('payment-method');
            if (activePaymentMethod) {
                selectPaymentMethod(activePaymentMethod);
            }

            $('.nav-link').on('click', function() {
                const paymentMethod = $(this).data('payment-method');
                selectPaymentMethod(paymentMethod);
            });
        });
    </script>

    <script>
        $(function () {
            $('#quickForm').validate({
                rules: {
                    customer_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    customer_email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    },
                    customer_phone: {
                        required: true,
                        pattern: /^0[0-9]{9}$/,
                        maxlength: 10
                    },
                    customer_address: {
                        required: true
                    }
                },
                messages: {
                    customer_name: {
                        required: "Tên người nhận không được để trống!",
                        minlength: "Tên người nhận phải có ít nhất {0} ký tự!",
                        maxlength: "Tên người nhận tối đa {0} ký tự!"
                    },
                    customer_email: {
                        required: "Email không được để trống!",
                        email: "Email không hợp lệ!",
                        maxlength: "Email tối đa {0} ký tự!"
                    },
                    customer_phone: {
                        required: "Số điện thoại không được để trống!",
                        pattern: "Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0!",
                        maxlength: "Số điện thoại tối đa {0} ký tự!"
                    },
                    customer_address: {
                        required: "Địa chỉ nhận hàng không được để trống!",
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