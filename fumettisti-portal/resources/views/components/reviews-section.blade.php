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
                    @if(!$userReview && $fumetto->user_id !== auth()->id())
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            <i class="fas fa-plus me-2"></i>Scrivi Recensione
                        </button>
                    @endif
                @else
                    <button class="btn btn-outline-primary" onclick="showLoginModal()">
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
                                <small class="text-muted">{{ $userReview->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="review-actions">
                            <button class="btn btn-sm btn-outline-secondary me-2"
                                    onclick="editReview({{ $userReview->id }})">
                                <i class="fas fa-edit"></i> Modifica
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="deleteReview({{ $userReview->id }})">
                                <i class="fas fa-trash"></i> Elimina
                            </button>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>{{ $userReview->comment }}</p>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <!-- Reviews List -->
    <div class="reviews-list">
        @if($fumetto->approvedReviews->count() > 0)
            @foreach($fumetto->approvedReviews->where('id', '!=', $userReview?->id ?? 0) as $review)
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            @if($review->user->profile && $review->user->profile->avatar)
                                <img src="{{ $review->user->profile->avatar_url }}"
                                     alt="{{ $review->user->name }}"
                                     class="reviewer-avatar">
                            @else
                                <div class="reviewer-avatar-placeholder">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="reviewer-details">
                                <h6>
                                    <a href="{{ route('profile.public', $review->user) }}">
                                        {{ $review->user->name }}
                                    </a>
                                </h6>
                                <div class="review-rating">
                                    {!! str_repeat('<i class="fas fa-star text-warning"></i>', $review->rating) !!}
                                    {!! str_repeat('<i class="far fa-star text-muted"></i>', 5 - $review->rating) !!}
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        <!-- Helpful votes -->
                        <div class="review-votes">
                            @auth
                                <button class="btn btn-sm btn-outline-success vote-btn"
                                        data-review-id="{{ $review->id }}"
                                        data-type="helpful">
                                    <i class="fas fa-thumbs-up"></i>
                                    <span>{{ $review->helpful_votes ?? 0 }}</span>
                                </button>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-thumbs-up me-1"></i>{{ $review->helpful_votes ?? 0 }}
                                </span>
                            @endauth
                        </div>
                    </div>

                    <div class="review-content">
                        <p>{{ $review->comment }}</p>
                    </div>

                    @if($review->updated_at != $review->created_at)
                        <div class="review-meta">
                            <small class="text-muted">
                                <i class="fas fa-edit me-1"></i>Modificata {{ $review->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Load More Reviews -->
            @if($fumetto->approvedReviews->count() > 5)
                <div class="text-center mt-3">
                    <button class="btn btn-outline-primary" onclick="loadMoreReviews()">
                        <i class="fas fa-chevron-down me-2"></i>Carica altre recensioni
                    </button>
                </div>
            @endif
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
                            <a href="{{ route('login') }}">Accedi</a> per essere il primo a lasciare una recensione!
                        @endauth
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Review Modal -->
@auth
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="modal-title">Scrivi una Recensione</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm" method="POST" action="{{ route('reviews.store', $fumetto) }}">
                @csrf
                <input type="hidden" id="review-method" name="_method" value="">
                <input type="hidden" id="review-id" value="">

                <div class="modal-body">
                    <!-- Rating Selection -->
                    <div class="mb-4">
                        <label class="form-label">Valutazione *</label>
                        <div class="rating-input">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                <label for="star{{ $i }}" class="star-label">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                        <small class="text-muted">Clicca sulle stelle per dare un voto</small>
                    </div>

                    <!-- Comment -->
                    <div class="mb-3">
                        <label for="comment" class="form-label">Commento *</label>
                        <textarea class="form-control"
                                  id="comment"
                                  name="comment"
                                  rows="5"
                                  placeholder="Condividi la tua opinione su questo fumetto..."
                                  required></textarea>
                        <div class="form-text">Minimo 10 caratteri, massimo 1000</div>
                    </div>

                    <!-- Guidelines -->
                    <div class="review-guidelines">
                        <h6>Linee guida per le recensioni:</h6>
                        <ul class="small text-muted">
                            <li>Sii rispettoso e costruttivo nei tuoi commenti</li>
                            <li>Concentrati sul contenuto dell'opera</li>
                            <li>Evita spoiler significativi</li>
                            <li>Non utilizzare linguaggio offensivo</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>
                        <span id="submit-text">Pubblica Recensione</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

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
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-color);
    margin: 0;
}

/* Reviews Summary */
.reviews-summary {
    background: var(--light-color);
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
    color: var(--primary-color);
    display: block;
}

.rating-stars {
    font-size: 1.5rem;
    margin: 0.5rem 0;
}

.rating-text {
    color: var(--text-muted);
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
    color: var(--text-muted);
}

.progress {
    flex: 1;
    height: 8px;
    background: #e9ecef;
}

.progress-bar {
    background: var(--warning-color);
}

.rating-count {
    min-width: 30px;
    text-align: right;
    font-size: 0.9rem;
    color: var(--text-muted);
}

/* Review Cards */
.user-review-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid var(--primary-color);
}

.review-card {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background: white;
}

.user-review {
    background: linear-gradient(135deg, #f8f9ff, #f0f2ff);
    border-color: var(--primary-color);
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

.reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.reviewer-avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
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

.reviewer-details h6 a {
    color: var(--dark-color);
    text-decoration: none;
}

.reviewer-details h6 a:hover {
    color: var(--primary-color);
}

.review-rating {
    margin-bottom: 0.25rem;
}

.review-content p {
    margin: 0;
    line-height: 1.6;
    color: var(--dark-color);
}

.review-actions {
    display: flex;
    gap: 0.5rem;
}

.review-votes {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.vote-btn {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.review-meta {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

/* Review Modal */
.rating-input {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
}

.star-label:hover,
.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input input[type="radio"]:checked + .star-label {
    color: var(--warning-color);
}

.review-guidelines {
    background: var(--light-color);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.review-guidelines h6 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.review-guidelines ul {
    margin: 0;
    padding-left: 1.2rem;
}

/* No Reviews State */
.no-reviews {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 768px) {
    .reviews-section {
        padding: 1.5rem;
    }

    .rating-breakdown {
        padding-left: 0;
        margin-top: 2rem;
    }

    .review-header {
        flex-direction: column;
        gap: 1rem;
    }

    .reviewer-info {
        width: 100%;
    }

    .review-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating interaction
    const starLabels = document.querySelectorAll('.star-label');
    starLabels.forEach((label, index) => {
        label.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });

        label.addEventListener('click', function() {
            selectStars(index + 1);
        });
    });

    const ratingInput = document.querySelector('.rating-input');
    if (ratingInput) {
        ratingInput.addEventListener('mouseleave', function() {
            const checked = document.querySelector('input[name="rating"]:checked');
            if (checked) {
                highlightStars(parseInt(checked.value));
            } else {
                highlightStars(0);
            }
        });
    }

    function highlightStars(count) {
        starLabels.forEach((label, index) => {
            if (index < count) {
                label.style.color = 'var(--warning-color)';
            } else {
                label.style.color = '#ddd';
            }
        });
    }

    function selectStars(count) {
        document.getElementById(`star${count}`).checked = true;
        highlightStars(count);
    }

    // Edit review function
    window.editReview = function(reviewId) {
        // Fetch review data and populate modal
        fetch(`/reviews/${reviewId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-title').textContent = 'Modifica Recensione';
                document.getElementById('submit-text').textContent = 'Aggiorna Recensione';
                document.getElementById('review-method').value = 'PUT';
                document.getElementById('review-id').value = reviewId;
                document.getElementById('reviewForm').action = `/reviews/${reviewId}`;

                // Set rating
                document.getElementById(`star${data.rating}`).checked = true;
                highlightStars(data.rating);

                // Set comment
                document.getElementById('comment').value = data.comment;

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
                modal.show();
            });
    };

    // Delete review function
    window.deleteReview = function(reviewId) {
        if (confirm('Sei sicuro di voler eliminare questa recensione?')) {
            fetch(`/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Errore durante l\'eliminazione della recensione');
                }
            });
        }
    };

    // Vote for review
    document.querySelectorAll('.vote-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            const type = this.dataset.type;

            fetch(`/reviews/${reviewId}/vote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.querySelector('span').textContent = data.votes;
                }
            });
        });
    });

    // Reset modal on close
    document.getElementById('reviewModal')?.addEventListener('hidden.bs.modal', function() {
        document.getElementById('modal-title').textContent = 'Scrivi una Recensione';
        document.getElementById('submit-text').textContent = 'Pubblica Recensione';
        document.getElementById('review-method').value = '';
        document.getElementById('review-id').value = '';
        document.getElementById('reviewForm').action = '{{ route("reviews.store", $fumetto) }}';
        document.getElementById('reviewForm').reset();
        highlightStars(0);
    });
});
</script>
                                </div>
                                <span class="rating-count">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- User's Review (if exists) -->
    @auth
        @if($userReview)
            <div class="user-review-section">
                <h5>La Tua Recensione</h5>
                <div class="review-card user-review">
                    <div class="review-header">
                        <div class="reviewer-info">
                            @if(auth()->user()->profile && auth()->user()->profile->avatar)
                                <img src="{{ auth()->user()->profile->avatar_url }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="reviewer-avatar">
                            @else
                                <div class="reviewer-avatar-placeholder">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="reviewer-details">
                                <h6>{{ auth()->user()->name }} <small class="text-muted">(Tu)</small></h6>
                                <div class="review-rating">
                                    {!! str_repeat('<i class="fas fa-star text-warning"></i>', $userReview->rating) !!}
                                    {!! str_repeat('<i class="far fa-star text-muted"></i>', 5 - $userReview->rating) !!}
                                </div>
