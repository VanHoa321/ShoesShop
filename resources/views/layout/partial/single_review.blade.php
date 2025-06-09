<div class="blog-comments-single"> 
    <img src="{{ $review->user->avatar ?? '/web-assets/img/blog/com-1.jpg' }}" alt="avatar" style="width: 70px; height: 70px; border-radius: 50%;">
    <div class="blog-comments-content">
        <h5>{{ $review->user->name }}</h5>
        <span><i class="far fa-clock"></i> {{ $review->created_at->format('d F, Y') }}
        </span>
        <p>{{ $review->content }}</p>
        <div class="review-rating">
            @for ($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
            @endfor
        </div>
    </div>
</div>
