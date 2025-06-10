<div class="reviews-section">
    <!-- Reviews Header -->
    <div class="reviews-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="section-title">
                    <i class="fas fa-star me-2"></i>
                    Recensioni ({{ $fumetto->reviews_count }})
                </h4>
            </div>
            <div class="col-md-6 text-md-end">
                @auth
                    @if(!isset($userReview) && $fumetto->user_id !== auth()->id())
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            <i class="fas fa-plus me-2"></i>Scrivi Recensione
                        </button>
                    @endif
                @else
                    <button class="btn btn-outline-primary" onclick="alert('Accedi per scrivere una recensione')">
                        <i class="fas fa-sign-in-alt me-2"></i>Accedi per Recensire
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Reviews Summary -->
    @if($fumetto->reviews_count > 0)
        <div class="reviews-summary">
            <div class="row">
                <div class="col-md-4">
                    <div class="rating-overview">
                        <div class="overall-rating">
                            <span class="rating-number">{{ number_format($fumetto->average_rating, 1) }}</span>
                            <div class="rating-stars">
                                {!! $fumetto->stars_html !!}
                            </div>
                            <p class="rating-text">{{ $fumetto->reviews_count }} recensioni</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="rating-breakdown">
                        @for($i = 5; $i >= 1; $i--)
                            @php
                                $count = $fumetto->reviews()->where('rating', $i)->count();
                                $percentage = $fumetto->reviews_count > 0 ? ($count / $fumetto->reviews_count) * 100 : 0;
                            @endphp
                            <div class="rating-bar">
                                <span class="rating-label">{{ $i }} stelle</span>
                                <div class="progress">
                                    <div class="progress-bar"
                                         style="width: {{ $percentage }}%"
                                         role="progressbar">
                                    </div>
                                </div>
                                <span class="rating-count">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Reviews List -->
    <div class="reviews-list">
        @if($fumetto->reviews()->where('is_approved', true)->count() > 0)
            @foreach($fumetto->reviews()->where('is_approved', true)->get() as $review)
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar-placeholder">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div class="reviewer-details">
                                <h6>{{ $review->user->name }}</h6>
                                <div class="review-rating">
                                    {!! str_repeat('<i class="fas fa-star text-warning"></i>', $review->rating) !!}
                                    {!! str_repeat('<i class="far fa-star text-muted"></i>', 5 - $review->rating) !!}
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="review-content">
                        <p>{{ $review->comment }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-reviews">
                <div class="text-center py-4">
                    <i class="fas fa-star-half-alt fa-3x text-muted mb-3"></i>
                    <h5>Nessuna recensione ancora</h5>
                    <p class="text-muted">
                        @auth
                            @if($fumetto->user_id !== auth()->id())
                                Sii il primo a lasciare una recensione per questo fumetto!
                            @else
                                Le recensioni degli utenti appariranno qui.
                            @endif
                        @else
                            Accedi per essere il primo a lasciare una recensione!
                        @endauth
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.reviews-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.reviews-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.reviews-summary {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.rating-overview {
    text-align: center;
}

.rating-number {
    font-size: 3rem;
    font-weight: 700;
    color: #6366f1;
    display: block;
}

.rating-stars {
    font-size: 1.5rem;
    margin: 0.5rem 0;
}

.rating-text {
    color: #6c757d;
    margin: 0;
}

.rating-breakdown {
    padding-left: 2rem;
}

.rating-bar {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    gap: 1rem;
}

.rating-label {
    min-width: 60px;
    font-size: 0.9rem;
    color: #6c757d;
}

.progress {
    flex: 1;
    height: 8px;
    background: #e9ecef;
}

.progress-bar {
    background: #ffc107;
}

.rating-count {
    min-width: 30px;
    text-align: right;
    font-size: 0.9rem;
    color: #6c757d;
}

.review-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background: white;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.reviewer-avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #6366f1;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.reviewer-details h6 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
}

.review-rating {
    margin-bottom: 0.25rem;
}

.review-content p {
    margin: 0;
    line-height: 1.6;
    color: #333;
}

.no-reviews {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .reviews-section {
        padding: 1.5rem;
    }

    .rating-breakdown {
        padding-left: 0;
        margin-top: 2rem;
    }
}
</style>
