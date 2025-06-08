@extends('layout/web_layout')
@section('content')
    <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">Các bài viết đăng tải</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                    <li class="active">Bài viết</li>
                </ul>
            </div>
        </div>
    </div>

   <div class="page-content">
    <section class="themeht-blogs">
        <div class="blog-area py-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-12">
                        <div class="row">
                            @foreach ($posts as $post)
                            <div class="col-md-6">
                                <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                                    <div class="blog-item-img">
                                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" style="height: 400px;">
                                    </div>
                                    <div class="blog-item-info">
                                        <div class="blog-item-meta">
                                            <ul>
                                                <li><a href="{{ route('frontend.posts.show', $post->id ) }}"><i class="far fa-user-circle"></i> {{ $post->user->name ?? 'Không hiển thị tác giả' }}</a></li>
                                                <li><a href="{{ route('frontend.posts.show', $post->id ) }}"><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('F d, Y') }}</a></li>
                                            </ul>
                                        </div>
                                        <h4 class="blog-title">
                                            <a href="{{ route('frontend.posts.show', $post->id ) }}">{{ $post->title }}</a>
                                        </h4>
                                        <p>{{ Str::limit($post->abstract, 100) }}</p>
                                        <a class="theme-btn" href="{{ route('frontend.posts.show', $post->id) }}">Đọc thêm<i class="fas fa-arrow-right-long"></i></a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- Pagination -->
                        <div class="pagination-area mb-lg-0">
                            <div aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    @if ($posts->onFirstPage())
                                        <li class="page-item disabled">
                                            <a class="page-link ms-0" href="#" aria-label="Previous">
                                                <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link ms-0" href="{{ $posts->previousPageUrl() }}" aria-label="Previous">
                                                <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                            </a>
                                        </li>
                                    @endif

                                    @for ($i = 1; $i <= $posts->lastPage(); $i++)
                                        <li class="page-item {{ $posts->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if ($posts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $posts->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!-- Pagination end -->
                        </div>
                        <div class="col-lg-4 col-12">
                            <aside class="sidebar">
                                <!-- search-->
                                <div class="widget search">
                                    <h5 class="widget-title">Tìm kiếm</h5>
                                    <form class="search-form" method="GET" action="{{ route('frontend.post.index') }}">
                                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm ở đây..." value="{{ $search ?? '' }}">
                                        <button type="submit"><i class="far fa-search"></i></button>
                                    </form>
                                </div>
                                <!-- category -->
                                <div class="widget category">
                                    <h5 class="widget-title">Lọc theo danh mục</h5>
                                    <div class="category-list">
                                        @foreach ($tags as $tag)
                                            <a href="{{ route('frontend.post.index', ['tag_id' => $tag->id]) }}">
                                                <i class="far fa-arrow-right"></i>
                                                {{ $tag->name }}
                                                <span>({{ $tag->posts()->where('is_active', 1)->count() }})</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- recent post -->
                                <div class="widget recent-post">
                                    <h5 class="widget-title">Bài viết mới nhất</h5>
                                    @foreach ($recentPosts as $recentPost)
                                    <div class="recent-post-single">
                                        <div class="recent-post-img">
                                            <img src="{{ asset($recentPost->image) }}" alt="{{ $recentPost->title }}">
                                        </div>
                                        <div class="recent-post-bio">
                                            <h6><a href="{{ route('frontend.posts.show', $recentPost->id) }}">{{ $recentPost->title }}</a></h6>
                                            <span><i class="far fa-clock"></i>{{ $recentPost->created_at->format('F d, Y') }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
