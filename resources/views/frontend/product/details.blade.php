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
                            <div class="shop-single-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span class="rating-count"> (4 Customer Reviews)</span>
                            </div>
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
                                    @if($relatedProducts->isNotEmpty())
                                        <div class="col-md-12 col-lg-12 col-xl-6 mb-2">
                                            <div class="shop-single-color">
                                                <h6>Màu sắc khác</h6>
                                                <div class="row g-3 align-items-center" id="colorOptions">
                                                    @foreach($relatedProducts as $relatedProduct)
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
                                            <h6 id="sizeHeading">Kích thước <span id="quantitySpan"></span></h6>
                                            <input type="hidden" id="selectedProductSizeId" value="">
                                            <div class="size-options d-flex flex-wrap gap-2 mt-2" id="sizeOptions">
                                                @php
                                                    $firstSize = $product->sizes->sortBy('size.name')->first();
                                                @endphp
                                                @foreach($product->sizes->sortBy('size.name') as $productSize)
                                                    <button type="button" class="btn btn-outline-secondary @if($productSize->quantity == 0) disabled @endif size-box"
                                                            data-product-size-id="{{ $productSize->id }}"
                                                            data-size-id="{{ $productSize->size->id }}"
                                                            data-quantity="{{ $productSize->quantity }}"
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
                                            <a href="#" class="theme-btn theme-btn2" data-tooltip="tooltip" title="Thêm vào yêu thích"><span class="far fa-heart"></span></a>
                                            <a href="#" class="theme-btn theme-btn2" data-tooltip="tooltip" title="Thêm vào so sánh"><span class="far fa-arrows-repeat"></span></a>
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
                                type="button" role="tab" aria-controls="tab3" aria-selected="false">Bình luận
                                (05)</button>
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
                                    <h5>Reviews (05)</h5>
                                    <div class="blog-comments-wrapper">
                                        <div class="blog-comments-single mt-0">
                                            <img src="/web-assets/img/blog/com-1.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Sinkler Denola</h5>
                                                <span><i class="far fa-clock"></i> 31 January, 2025</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blog-comments-single ms-md-5">
                                            <img src="/web-assets/img/blog/com-2.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Daniel Wellman</h5>
                                                <span><i class="far fa-clock"></i> 31 January, 2025</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blog-comments-single">
                                            <img src="/web-assets/img/blog/com-3.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Kenneth Evans</h5>
                                                <span><i class="far fa-clock"></i> 31 January, 2025</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-comments-form">
                                        <h4 class="mb-4">Leave A Review</h4>
                                        <form action="#">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Your Name*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" placeholder="Your Email*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Your Subject*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select class="form-control form-select">
                                                            <option value="">Your Rating</option>
                                                            <option value="5">5 Stars</option>
                                                            <option value="4">4 Stars</option>
                                                            <option value="3">3 Stars</option>
                                                            <option value="2">2 Stars</option>
                                                            <option value="1">1 Star</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="5" placeholder="Your Review*"></textarea>
                                                    </div>
                                                    <button type="submit" class="theme-btn"><span class="far fa-paper-plane"></span> Submit Review</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
                                    <h2 class="site-title">Related Items</h2>
                                    <a href="#">View More <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type new">New</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p7.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type hot">Hot</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p8.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type oos">Out Of Stock</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p12.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type discount">10% Off</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p14.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <del>$120.00</del>
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- related item end -->
            </div>
        </div>
    </main>
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
            const quantity = element.getAttribute('data-quantity');
            const productSizeId = element.getAttribute('data-product-size-id');
            document.getElementById('sizeHeading').firstChild.nodeValue = `Kích thước ${sizeName} `;
            document.getElementById('quantitySpan').textContent = `(Số lượng còn: ${quantity})`;
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
                        else {
                            toastr.error('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                        }
                    },
                    error: function(xhr) {
                        console.error("Lỗi AJAX:", xhr.responseText);
                        alert('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng.');
                    }
                });
            });
        });
    </script>
@endsection