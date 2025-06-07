@extends('layout/web_layout')

@section('content')
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Chi tiết bài viết</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                <li class="active">Chi tiết bài viết</li>
            </ul>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="themeht-blogs-detail">
        <div class="blog-single-area py-100">
            <div class="container">
                <div class="row">
                    <!-- Cột bên trái: Nội dung bài viết -->
                    <div class="col-lg-8">
                        <div class="blog-single-wrapper">
                            <div class="blog-single-content">
                                <div class="blog-thumb-img">
                                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" style="width:100%">
                                </div>
                                <div class="blog-info">
                                    <div class="blog-meta">
                                        <div class="blog-meta-left">
                                            <ul>
                                                <li><i class="far fa-user"></i><a href="#">{{ $post->user->name ?? 'Không hiện thị tác giả' }}</a></li>
                                                <li><i class="far fa-calendar"></i> {{ $post->created_at }}</li>
                                            </ul>
                                        </div>
                                        <div class="blog-meta-right">
                                            <a href="#" class="share-link"><i class="far fa-share-alt"></i>Share</a>
                                        </div>
                                    </div>
                                    <div class="blog-details">
                                        <h3 class="blog-details-title mb-20">{{ $post->title }}</h3>
                                        <p class="mb-10">
                                            {{ $post->abstract }}
                                        </p>
                                        <p class="mb-10">
                                            {!! $post->content !!}
                                        </p>
                                        <blockquote class="blockqoute">
                                            <p>{{ $post->title }}</p>
                                            <h6 class="blockqoute-author">{{ $post->user->name ?? 'Tác giả không rõ' }}</h6>
                                            <i class="far fa-quote-right"></i>
                                        </blockquote>
                                        <hr>
                                        <div class="blog-details-tags pb-20">
                                            <h5>Danh mục bài viết : </h5>
                                            <ul>
                                                @foreach ($post->tags as $tag)
                                                    <li><a href="{{ route('frontend.post.index', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Cột bên phải: Sidebar -->
                    <div class="col-lg-4">
                        <aside class="sidebar">
                            <!-- Search -->
                            <div class="widget search">
                                <h5 class="widget-title">Search</h5>
                                <form class="search-form" method="GET" action="{{ route('frontend.post.index') }}">
                                    <input type="text" class="form-control" name="search" placeholder="Search Here..." value="{{ old('search') }}">
                                    <button type="submit"><i class="far fa-search"></i></button>
                                </form>
                            </div>
                            <!-- Category -->
                            <div class="widget category">
                                <h5 class="widget-title">Category</h5>
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
                            <!-- Recent Post -->
                            <div class="widget recent-post">
                                <h5 class="widget-title">Recent Post</h5>
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
