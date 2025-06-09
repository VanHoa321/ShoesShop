@extends('layout/web_layout')
@section('content')
    <main class="main">

        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Chi tiết sản phẩm</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="index-2.html"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Chi tiết sản phẩm</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="shop-single py-100">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-lg-6 col-xxl-5">
                        <div class="shop-single-gallery">
                            <div class="flexslider">
                                <ul class="slides">
                                    @foreach($product->images as $image)
                                        <li data-thumb="{{ $image->image }}">
                                            <img src="{{ $image->image }}" alt="#">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-6">
                        <div class="shop-single-info">
                            <h4 class="shop-single-title">{{ $product->name }}</h4>
                            @if ($product->rating_count > 0)
                                <div class="shop-single-rating">
                                    @php
                                        $integer_rating = floor($product->average_rating);
                                    @endphp

                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <=$integer_rating)
                                            <i class="fas fa-star" style="color: #ffc107;"></i>
                                        @else
                                            <i class="far fa-star" style="color: #ffc107;"></i>
                                        @endif
                                    @endfor
                                    <span class="rating-count"> ({{ $product->rating_count }} đánh giá của khách hàng)</span>
                                </div>
                            <span> Điểm đánh giá: {{ number_format($product->average_rating, 1, '.', '') }}/5 </span>
                            @else
                            <div class="shop-single-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: #ffc107;"></i>
                                @endfor
                                <span class="rating-count"> (Chưa có đánh giá)</span>
                            </div>
                            @endif
                            <div class="shop-single-price">
                                @if($product->discount > 0)
                                    <del>{{ number_format($product->price) }} đ</del>
                                    <span class="amount">{{ number_format($product->price - $product->discount) }} đ</span>
                                    <span class="discount-percentage">
                                        GIẢM {{ round(($product->discount / $product->price) * 100) }}%
                                    </span>
                                @else
                                    <span class="amount">{{ number_format($product->price) }} đ</span>
                                @endif
                            </div>
                            <p class="mb-3">
                                {{ $product->summary }}
                            </p>
                            <div class="shop-single-cs">
                                <div class="row">
                                    @if($groupProducts->isNotEmpty())
                                        <div class="col-md-12 col-lg-12 col-xl-6 mb-2">
                                            <div class="shop-single-color">
                                                <h6>Màu sắc khác</h6>
                                                <div class="row g-3 align-items-center" id="colorOptions">
                                                    @foreach($groupProducts as $relatedProduct)
                                                        <div class="col-auto">
                                                            <a href="{{ route('frontend.product.details', $relatedProduct->id) }}" class="text-decoration-none">
                                                                <img src="{{ $relatedProduct->thumbnail }}" alt="{{ $relatedProduct->color->name }}" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;">
                                                                <p class="text-center mb-0" style="font-size: 12px;">{{ $relatedProduct->color->name }}</p>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12 col-lg-12 col-xl-12 mb-2">
                                        <div class="shop-single-size">
                                            <h6 id="sizeHeading">Kích thước</h6>
                                            <div class="size-options d-flex flex-wrap gap-2 mt-2" id="sizeOptions">
                                                @foreach($product->sizes->sortBy('size.name') as $productSize)
                                                    <button type="button" class="btn btn-outline-secondary size-box"
                                                            data-product-size-id="{{ $productSize->id }}"
                                                            onclick="selectSize(this)">
                                                        {{ $productSize->size->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shop-single-sortinfo">
                                <ul>
                                    <li>Mã sản phẩm: <span>{{ $product->code }}</span></li>
                                    <li>Thương hiệu: <span>{{ $product->brand->name }}</span></li>
                                    <li>Màu sắc: <span>{{ $product->color->name }}</span></li>
                                    @if($product->categories && count($product->categories))
                                        <li>Danh mục:
                                            @foreach($product->categories as $category)
                                                <a href="#">{{ $category->name }}</a>{{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="shop-single-action">
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-lg-4 col-xl-3">
                                        <div class="shop-single-size">
                                            <div class="shop-cart-qty">
                                                <button class="minus-btn"><i class="fal fa-minus"></i></button>
                                                <input class="quantity" type="text" value="1" disabled="">
                                                <button class="plus-btn"><i class="fal fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-lg-12 col-xl-9">
                                        <div class="shop-single-btn">
                                            <a href="#" class="theme-btn add-to-cart" data-price="{{ $product->discount > 0 ? $product->price - $product->discount : $product->price }}"><span class="far fa-shopping-bag"></span>Thêm giỏ hàng</a>
                                            <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $product->is_favourite ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                                class="favourite-btn theme-btn theme-btn2 {{ Auth::check() && $product->is_favourite ? 'favourited' : '' }}"
                                                data-product-id="{{ $product->id }}"
                                                data-is-favourited="{{ Auth::check() && $product->is_favourite ? 'true' : 'false' }}"
                                                @if (!Auth::check()) data-requires-login="true" @endif>
                                                <i class="far fa-heart" style="margin-left:0"></i>
                                            </a>
                                            <a href="#" class="theme-btn theme-btn2" data-tooltip="tooltip" title="Thêm vào so sánh"><span class="far fa-arrows-repeat"></span></a>
                                            <input type="hidden" id="selectedProductSizeId" value="">
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shop-single-details">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-tab1" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">Thông tin sản phẩm</button>
                            <!-- <button class="nav-link" id="nav-tab2" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">Additional
                                Info</button> -->
                            <button class="nav-link" id="nav-tab3" data-bs-toggle="tab" data-bs-target="#tab3"
                                type="button" role="tab" aria-controls="tab3" aria-selected="false">Đánh giá ({{ $product->rating_count }})</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="nav-tab1">
                            <div class="shop-single-desc">
                                <p>
                                    {!! $product->description !!}
                                </p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="nav-tab2">
                            <div class="shop-single-additional">
                                <p>
                                    There are many variations of passages of Lorem Ipsum available, but the majority
                                    have suffered alteration in some form, by injected humour, or randomised words which
                                    don't look even slightly believable. If you are going to use a passage of Lorem
                                    Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of
                                    text. All the Lorem Ipsum generators on the Internet tend to repeat predefined
                                    chunks as necessary, making this the first true generator on the Internet.
                                </p>
                                <p>
                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                    doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                                    veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam
                                    voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur
                                    magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est,
                                    qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.
                                </p>
                                <div class="shop-single-list">
                                    <h5 class="title">Shipping Options:</h5>
                                    <ul>
                                        <li><span>Standard:</span> 6-7 Days, Shipping Cost - Free</li>
                                        <li><span>Express:</span> 1-2 Days, Shipping Cost - $20</li>
                                        <li><span>Courier:</span> 2-3 Days, Shipping Cost - $30</li>
                                        <li><span>Fastgo:</span> 1-3 Days, Shipping Cost - $15</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="nav-tab3">
                        <div class="shop-single-review">
                            <div class="blog-comments">
                                <h5>Reviews (<span id="review-count">{{ $product->rating_count}}</span>)</h5>
                                <div class="blog-comments-wrapper">
                                    @if($ratings->isEmpty())
                                    <div class="text-center p-4" id="no-reviews-message" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: .25rem;">
                                        <h4 class="mb-3">Chưa có đánh giá nào</h4>
                                        <p>Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                                    </div>
                                    @else
                                    @foreach($ratings as $rating)
                                    @include('layout.partial.single_review', ['review' => $rating])
                                    @endforeach
                                    @endif
                                </div>
                                <div class="pagination-area mb-0">
                                    {{ $ratings->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                            <div class="blog-comments-form">
                                @auth
                                <h4 class="mb-4">Để lại đánh giá</h4>
                                <form id="review-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select name="rating" class="form-control form-select">
                                                    <option value="">Chọn đánh giá của bạn</option>
                                                    <option value="5">5 Sao</option>
                                                    <option value="4">4 Sao</option>
                                                    <option value="3">3 Sao</option>
                                                    <option value="2">2 Sao</option>
                                                    <option value="1">1 Sao</option>
                                                </select>
                                                <span id="rating-error" class="text-danger" style="font-size: 14px;"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="content" class="form-control" rows="5" placeholder="Viết đánh giá của bạn*"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="theme-btn" id="submit-review-btn">
                                                <span class="far fa-paper-plane"></span> Gửi đánh giá
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @else
                                <div class="text-center p-4" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: .25rem;">
                                    <h4 class="mb-3">Bạn cần đăng nhập để đánh giá</h4>
                                    <p>Vui lòng <a href="{{ route('login') }}" class="font-weight-bold">đăng nhập</a> để chia sẻ cảm nhận của bạn về sản phẩm.</p>
                                </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- related item -->
                <div class="product-area related-item">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="site-heading-inline">
                                    <h2 class="site-title">Sản phẩm liên quan</h2>
                                    <a href="{{ route('frontend.product.index') }}">Xem thêm <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($relatedProducts as $item)
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        @if($item->is_sale)
                                        <span class="type hot">Giảm giá</span>
                                        @endif
                                        <a href="{{ route("frontend.product.details", $item->id) }}"><img src="{{ $item->thumbnail }}" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $item->is_favourite ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                                            class="favourite-btn {{ Auth::check() && $item->is_favourite ? 'favourited' : '' }}"
                                                            data-product-id="{{ $item->id }}"
                                                            data-is-favourited="{{ Auth::check() && $item->is_favourite ? 'true' : 'false' }}"
                                                            @if (!Auth::check()) data-requires-login="true" @endif>
                                                            <i class="far fa-heart"></i>
                                                        </a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="{{ route("frontend.product.details", $item->id) }}">{{ $item->name }}</a></h3>
                                        @if ($item->rating_count > 0)
                                        <div class="shop-single-rating">
                                            @php
                                            $integer_rating = floor($item->average_rating);
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

                                        <div class="product-bottom">
                                            <div class="product-price">
                                                @if($item->discount > 0)
                                                <del>{{ number_format($item->price, 0, ',', '.') }} <sup>đ</sup></del>
                                                @endif
                                                <span>{{ number_format($item->price - $item->discount, 0, ',', '.') }} <sup>đ</sup></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- related item end -->
            </div>
        </div>
    </main>
    <meta name="auth-status" content="{{ Auth::check() ? 'true' : 'false' }}">
@endsection
@section('scripts')
    <script>
        function selectSize(element) {
            if (element.classList.contains('disabled')) return;

            const sizeBoxes = document.querySelectorAll('.size-box');

            sizeBoxes.forEach(box => {
                if (box !== element && box.classList.contains('theme-btn')) {
                    box.classList.remove('theme-btn');
                    box.classList.add('btn-outline-secondary');
                }
            });

            element.classList.remove('btn-outline-secondary');
            element.classList.add('theme-btn');

            const sizeName = element.textContent.trim();
            const productSizeId = element.getAttribute('data-product-size-id');
            document.getElementById('sizeHeading').firstChild.nodeValue = `Kích thước ${sizeName} `;
            document.getElementById('selectedProductSizeId').value = productSizeId;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sizeBoxes = document.querySelectorAll('.size-box');
            const firstAvailable = Array.from(sizeBoxes).find(box => !box.classList.contains('disabled'));
            if (firstAvailable) {
                selectSize(firstAvailable);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.add-to-cart').on('click', function(e) {
                e.preventDefault();

                var productSizeId = $('#selectedProductSizeId').val();
                var quantity = parseInt($('.quantity').val());
                var price = $(this).data('price');
                
                if (!productSizeId) {
                    toastr.error('Vui lòng chọn kích thước!');
                    return;
                }

                $.ajax({
                    url: "{{ route('frontend.cart.add') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_size_id: productSizeId,
                        quantity: quantity,
                        price: price
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success('Sản phẩm đã được thêm vào giỏ hàng!');
                            document.getElementById('cart-item-count').textContent = response.count;
                        } 
                        else if (response.status === 'error') {
                            toastr.error(response.message || 'Không thể thêm sản phẩm vào giỏ!');
                        }
                        else {
                            toastr.error('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!');
                        }
                    },
                    error: function(xhr) {
                        console.error("Lỗi AJAX:", xhr.responseText);
                        toastr.error('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng.');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#review-form').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var submitButton = $('#submit-review-btn');
                var formData = form.serialize();

                $('#rating-error').text('');

                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang gửi...');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("frontend.product.rating") }}',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);

                            $('.blog-comments-wrapper').prepend(response.review_html);

                            $('#no-reviews-message').remove();

                            form.trigger('reset');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.rating) {
                                $('#rating-error').text(errors.rating[0]);
                            }
                            if (errors.content) {
                                toastr.error(errors.content[0]);
                            }
                        } else {
                            toastr.error('Đã xảy ra lỗi không mong muốn. Vui lòng thử lại.');
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).html('<span class="far fa-paper-plane"></span> Gửi đánh giá');
                    }
                });
            });
        });
    </script>
@endsection