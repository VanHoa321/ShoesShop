@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Giỏ hàng</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Giỏ hàng</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="shop-cart py-100">
            <div class="container">
                <div class="shop-cart-wrap">
                    @if (!empty($cartItems))
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="cart-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Hình ảnh</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th style="width:100px">Giá bán</th>
                                                    <th>Số lượng</th>
                                                    <th style="width:130px">Thành tiền</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cartItems as $item)
                                                    <tr data-cart-key="{{ $item['cart_key'] }}">
                                                        <td>
                                                            <div class="shop-cart-img">
                                                                <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="shop-cart-content">
                                                                <h5 class="shop-cart-name"><a href="{{ route('frontend.product.details', $item['product_id']) }}">{{ $item['name'] }}</a></h5>
                                                                <div class="shop-cart-info">
                                                                    <p><span>Kích thước:</span>{{ $item['size'] }}</p>
                                                                    <p><span>Màu sắc:</span>{{ $item['color'] }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="shop-cart-price">
                                                                <span>{{ number_format($item['price'], 0, ',', '.') }} đ</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="shop-cart-qty">
                                                                <button class="minus-btn-cart"><i class="fal fa-minus"></i></button>
                                                                <input class="quantity" type="text" value="{{ $item['quantity'] }}" disabled="">
                                                                <button class="plus-btn-cart"><i class="fal fa-plus"></i></button>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="shop-cart-subtotal">
                                                                <span>{{ number_format($item['total'], 0, ',', '.') }} đ</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="shop-cart-remove"><i class="far fa-times"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="shop-cart-footer">
                                    <div class="row">
                                        <div class="col-md-7 col-lg-6">
                                            <div class="shop-cart-coupon">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Nhập mã giảm giá" id="voucherInput">
                                                    <button class="theme-btn apply-voucher-btn" type="submit">Áp dụng</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-lg-6">
                                            <div class="shop-cart-btn text-md-end">
                                                <a href="{{ route('frontend.product.index') }}" class="theme-btn"><span class="fas fa-arrow-left"></span> Quay lại cửa hàng</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="shop-cart-summary">
                                    <h5>Tổng tiền giỏ hàng</h5>
                                    <ul>
                                        <li><strong>Thành tiền:</strong> <span>{{ number_format(collect($cartItems)->sum('total'), 0, ',', '.') }} đ</span></li>
                                        <li><strong>Giảm giá:</strong> <span>{{ session('voucher') ? number_format(session('voucher.discount'), 0, ',', '.') . ' đ' : '0 đ' }}</span></li>
                                        @if (session('voucher'))
                                            <li class="applied-voucher"><strong>Mã áp dụng:</strong> <span>{{ session('voucher.code') }} <a href="#" class="remove-voucher text-danger"><i class="far fa-times"></i></a></span></li>
                                        @endif
                                        <li><strong>Phí ship:</strong> <span>Miễn phí</span></li>
                                        <li class="shop-cart-total"><strong>Tổng tiền:</strong> <span>{{ number_format(collect($cartItems)->sum('total') - (session('voucher') ? session('voucher.discount') : 0), 0, ',', '.') }} đ</span></li>
                                    </ul>
                                    <div class="text-end mt-40">
                                        <a href="{{ route("frontend.checkout.index") }}" class="theme-btn">Thanh toán<i class="fas fa-arrow-right-long"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <img style='width:300px; display: block; margin: auto; margin-top:20px' src='http://127.0.0.1:8000/storage/photos/1/Avatar/8183434.jpg'/>
                            <p class="text-danger text-center mt-4">Chưa có sản phẩm trong giỏ hàng, hãy thêm sản phẩm trước khi tiến hành đặt mua!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            function formatVND(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' đ';
            }

            function updateCartSummary(cart) {
                let subtotal = 0;
                for (let id in cart) {
                    subtotal += cart[id].total;
                }

                let discountTextElement = $('.shop-cart-summary ul li strong:contains("Giảm giá:")').next('span');
                let discount = parseFloat(discountTextElement.text().replace(/[^0-9]/g, '')) || 0;

                $('.shop-cart-summary ul li strong:contains("Thành tiền:")').next('span').text(formatVND(subtotal));

                let finalTotal = subtotal - discount;
                $('.shop-cart-summary ul li.shop-cart-total span').text(formatVND(finalTotal));
            }

            function showEmptyCart() {
                $('.shop-cart-wrap').html(`
                    <div>
                        <img style='width:300px; display: block; margin: auto; margin-top:20px' src='http://127.0.0.1:8000/storage/photos/1/Avatar/8183434.jpg'/>
                        <p class="text-danger text-center mt-4">Chưa có sản phẩm trong giỏ hàng, hãy thêm sản phẩm trước khi tiến hành đặt mua!</p>
                    </div>
                `);
            }

            $('.plus-btn-cart').on('click', function () {
                let $row = $(this).closest('tr');
                let cartKey = $row.data('cart-key');
                let $quantityInput = $row.find('.quantity');
                let quantity = parseInt($quantityInput.val()) + 1;

                $.ajax({
                    url: '{{ route("frontend.cart.update") }}',
                    method: 'POST',
                    data: {
                        cart_key: cartKey,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'updated') {
                            $quantityInput.val(quantity);
                            let subtotal = response.cart[cartKey].total;
                            $row.find('.shop-cart-subtotal span').text(formatVND(subtotal));
                            updateCartSummary(response.cart);
                            document.getElementById('cart-item-count').textContent = response.count;
                        }
                    },
                    error: function () {
                        toastr.error('Lỗi khi cập nhật số lượng');
                    }
                });
            });

            $('.minus-btn-cart').on('click', function () {
                let $row = $(this).closest('tr');
                let cartKey = $row.data('cart-key');
                let $quantityInput = $row.find('.quantity');
                let quantity = parseInt($quantityInput.val()) - 1;

                if (quantity < 1) return;

                $.ajax({
                    url: '{{ route("frontend.cart.update") }}',
                    method: 'POST',
                    data: {
                        cart_key: cartKey,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'updated') {
                            $quantityInput.val(quantity);
                            let subtotal = response.cart[cartKey].total;
                            $row.find('.shop-cart-subtotal span').text(formatVND(subtotal));
                            updateCartSummary(response.cart);
                            document.getElementById('cart-item-count').textContent = response.count;
                        }
                    },
                    error: function () {
                        toastr.error('Lỗi khi cập nhật số lượng');
                    }
                });
            });

            $('.shop-cart-remove').on('click', function (e) {
                e.preventDefault();
                let $row = $(this).closest('tr');
                let cartKey = $row.data('cart-key');

                $.ajax({
                    url: '{{ route("frontend.cart.remove") }}',
                    method: 'POST',
                    data: {
                        cart_key: cartKey,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'removed') {
                            $row.remove();
                            document.getElementById('cart-item-count').textContent = response.count;
                            if (Object.keys(response.cart).length === 0) {
                                showEmptyCart();
                            } else {
                                updateCartSummary(response.cart);
                            }
                        }
                    },
                    error: function () {
                        toastr.error('Lỗi khi xóa sản phẩm');
                    }
                });
            });

            $('.apply-voucher-btn').on('click', function (e) {
                e.preventDefault();
                let voucherCode = $('#voucherInput').val();

                $.ajax({
                    url: '{{ route("frontend.cart.applyVoucher") }}',
                    method: 'POST',
                    data: {
                        voucher_code: voucherCode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('.shop-cart-summary ul li strong:contains("Giảm giá:")').next('span').text(formatVND(response.discount));
                            $('.shop-cart-summary ul li.shop-cart-total span').text(formatVND(response.new_total));

                            if ($('.applied-voucher').length === 0) {
                                $('.shop-cart-summary ul').find('li:has(strong:contains("Giảm giá:"))').after(`
                                    <li class="applied-voucher">
                                        <strong>Mã áp dụng:</strong>
                                        <span>${voucherCode} <a href="#" class="remove-voucher text-danger"><i class="far fa-times"></i></a></span>
                                    </li>
                                `);
                            } else {
                                $('.applied-voucher span').html(`${voucherCode} <a href="#" class="remove-voucher text-danger"><i class="far fa-times"></i></a>`);
                            }
                            $('#voucherInput').val('');
                            toastr.success(response.message);
                        }
                        else {
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        toastr.error('Lỗi khi áp dụng voucher');
                    }
                });
            });

            $(document).on('click', '.remove-voucher', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route("frontend.cart.removeVoucher") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('.shop-cart-summary ul li strong:contains("Giảm giá:")').next('span').text('0 đ');
                            $('.shop-cart-summary ul li.shop-cart-total span').text(formatVND(response.new_total));
                            $('.applied-voucher').remove();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        toastr.error('Lỗi khi xóa voucher');
                    }
                });
            });
        });
    </script>
@endsection