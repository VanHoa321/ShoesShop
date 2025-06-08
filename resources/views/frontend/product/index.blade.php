@extends('layout/web_layout')
@section('content')
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Danh sách sản phẩm</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                <li class="active">Sản phẩm</li>
            </ul>
        </div>
    </div>
</div>

<div class="shop-area py-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="shop-sidebar">
                    <div class="shop-widget">
                        <div class="shop-search-form">
                            <h4 class="shop-widget-title">Tìm kiếm</h4>
                            <form action="{{ route('frontend.product.index') }}" method="GET">
                                <div class="form-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search">
                                    <button type="submit"><i class="far fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('frontend.product.index') }}" method="GET">
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Danh mục</h4>
                            <ul class="shop-checkbox-list">
                                @foreach ($categories as $category)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="category-{{ $category->id }}" name="categories[]" value="{{ $category->id }}" @if(in_array($category->id, request('categories', []))) checked @endif>
                                        <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }} ({{ $category->products->count() ?? 0 }})</label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Thương hiệu</h4>
                            <ul class="shop-category-list">
                                @foreach ($brands as $brand)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="brand-{{ $brand->id }}" name="brands[]" value="{{ $brand->id }}" @if(in_array($brand->id, request('brands', []))) checked @endif>
                                        <label class="form-check-label" for="brand-{{ $brand->id }}">{{ $brand->name }} ({{ $brand->products->count() ?? 0 }})</label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Khoảng giá</h4>
                            <div class="price-range-box">
                                <div class="price-range-input">
                                    <input type="number" name="min_price" value="{{ request('min_price', 0) }}" placeholder="Min" class="mb-2">
                                    <input type="number" name="max_price" value="{{ request('max_price', 99999999) }}" placeholder="Max">
                                </div>
                            </div>
                        </div>
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Đặc biệt</h4>
                            <ul class="shop-checkbox-list">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" id="new" type="checkbox" name="is_new" value="1" @if(request('is_new')) checked @endif>
                                        <label class="form-check-label" for="new">Sản phẩm mới</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" id="bestseller" type="checkbox" name="is_bestseller" value="1" @if(request('is_bestseller')) checked @endif>
                                        <label class="form-check-label" for="bestseller">Sản phẩm bán chạy</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" id="sale" type="checkbox" name="is_sale" value="1" @if(request('is_sale')) checked @endif>
                                        <label class="form-check-label" for="sale">Giảm giá</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Đánh giá</h4>
                            <ul class="shop-checkbox-list rating">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rate1">
                                        <label class="form-check-label" for="rate1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rate2">
                                        <label class="form-check-label" for="rate2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fal fa-star"></i>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rate3">
                                        <label class="form-check-label" for="rate3">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rate4">
                                        <label class="form-check-label" for="rate4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rate5">
                                        <label class="form-check-label" for="rate5">
                                            <i class="fas fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Màu sắc</h4>
                            <ul class="shop-checkbox-list color">
                                @foreach ($colors as $color)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="color-{{ $color->id }}" name="colors[]" value="{{ $color->id }}" @if(in_array($color->id, request('colors', []))) checked @endif>
                                        <label class="form-check-label" for="color-{{ $color->id }}"><span style="background-color:  <?php echo $color->code ?>"></span></label>

                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="shop-widget">
                            <h4 class="shop-widget-title">Kích cỡ</h4>
                            <ul class="shop-checkbox-list">
                                @foreach ($sizes as $size)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="size-{{ $size->id }}" name="sizes[]" value="{{ $size->id }}" @if(in_array($size->id, request('sizes', []))) checked @endif>
                                        <label class="form-check-label" for="size-{{ $size->id }}">{{ $size->name }}</label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success w-100">Áp dụng bộ lọc</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="shop-item-wrapper">
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-md-6 col-lg-4">
                                <div class="product-item">
                                    <div class="product-img">
                                        @if($product->is_sale)
                                            <span class="type hot">Giảm giá</span>
                                        @endif
                                        <a href="{{ route("frontend.product.details", $product->id) }}"><img src="{{ $product->thumbnail }}" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="{{ route("frontend.product.details", $product->id) }}" title="Chi tiết"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="{{ route("frontend.product.details", $product->id) }}">{{ $product->name }}</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                @if($product->discount > 0)
                                                    <del>{{ number_format($product->price, 0, ',', '.') }} <sup>đ</sup></del>
                                                @endif
                                                <span>{{ number_format($product->price - $product->discount, 0, ',', '.') }} <sup>đ</sup></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- pagination -->
                <div class="pagination-area mb-0">
                    {{ $products->links('vendor.pagination.bootstrap-4') }}
                </div>
                <!-- pagination end -->
            </div>
        </div>
    </div>
</div>
@endsection