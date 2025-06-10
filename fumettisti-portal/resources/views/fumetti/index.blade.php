@extends('layouts.app')

@section('title', 'Catalogo Fumetti - Fumettisti Portal')

@section('meta')
    <meta name="description" content="Scopri il catalogo completo dei fumetti indipendenti italiani. Manga, graphic novel e fumetti originali creati da artisti emergenti.">
    <meta property="og:title" content="Catalogo Fumetti - Fumettisti Portal">
    <meta property="og:description" content="Scopri il catalogo completo dei fumetti indipendenti italiani">
    <meta property="og:type" content="website">
@endsection

@section('content')
<div class="fumetti-catalog">
    <!-- Hero Section -->
    <section class="catalog-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title">Catalogo Fumetti</h1>
                    <p class="hero-subtitle">
                        Scopri le opere dei migliori fumettisti indipendenti italiani.
                        Ogni storia è unica, ogni disegno è originale.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters & Search -->
    <section class="catalog-filters">
        <div class="container">
            <div class="filters-card">
                <form method="GET" action="{{ route('fumetti.index') }}" class="filters-form">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="search" class="form-label">
                                    <i class="fas fa-search me-2"></i>Cerca fumetti
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Titolo, autore, trama...">
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label for="category" class="form-label">
                                    <i class="fas fa-tags me-2"></i>Categoria
                                </label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Tutte le categorie</option>
                                    <!-- Qui dovresti caricare le categorie dal database -->
                                    <option value="manga" {{ request('category') == 'manga' ? 'selected' : '' }}>Manga</option>
                                    <option value="graphic-novel" {{ request('category') == 'graphic-novel' ? 'selected' : '' }}>Graphic Novel</option>
                                    <option value="superhero" {{ request('category') == 'superhero' ? 'selected' : '' }}>Supereroi</option>
                                    <option value="fantasy" {{ request('category') == 'fantasy' ? 'selected' : '' }}>Fantasy</option>
                                    <option value="sci-fi" {{ request('category') == 'sci-fi' ? 'selected' : '' }}>Fantascienza</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label for="sort" class="form-label">
                                    <i class="fas fa-sort me-2"></i>Ordina per
                                </label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Più recenti</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Meno recenti</option>
                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titolo A-Z</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Migliori recensioni</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Più popolari</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Button -->
                        <div class="col-md-2">
                            <div class="filter-group">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Filtra
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Results Info -->
    <section class="results-info">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="results-count">
                        @if($fumetti->total() > 0)
                            <span class="count-text">
                                Trovati <strong>{{ $fumetti->total() }}</strong> fumetti
                                @if(request('search'))
                                    per "<strong>{{ request('search') }}</strong>"
                                @endif
                            </span>
                        @else
                            <span class="count-text">Nessun fumetto trovato</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    @if(request()->hasAny(['search', 'category', 'sort']))
                        <a href="{{ route('fumetti.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-2"></i>Rimuovi filtri
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Fumetti Grid -->
    <section class="fumetti-grid">
        <div class="container">
            @if($fumetti->count() > 0)
                <div class="row">
                    @foreach($fumetti as $fumetto)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="fumetto-card">
                                <!-- Cover Image -->
                                <div class="fumetto-cover">
                                    <a href="{{ route('fumetti.show', $fumetto) }}">
                                        @if($fumetto->cover_image)
                                            <img src="{{ $fumetto->cover_image_url }}"
                                                 alt="{{ $fumetto->title }}"
                                                 class="cover-image">
                                        @else
                                            <div class="cover-placeholder">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                        @endif
                                    </a>

                                    <!-- Badges -->
                                    @if($fumetto->categories->count() > 0)
                                        <div class="category-badge">
                                            {{ $fumetto->categories->first()->name }}
                                        </div>
                                    @endif

                                    @if($fumetto->reviews_count > 0)
                                        <div class="rating-badge">
                                            <i class="fas fa-star"></i>
                                            {{ number_format($fumetto->average_rating, 1) }}
                                        </div>
                                    @endif

                                    <!-- Favorite Button for Auth Users -->
                                    @auth
                                        <div class="favorite-overlay">
                                            <x-favorite-button :fumetto="$fumetto" size="sm" />
                                        </div>
                                    @endauth
                                </div>

                                <!-- Fumetto Info -->
                                <div class="fumetto-info">
                                    <!-- Title & Link -->
                                    <h3 class="fumetto-title">
                                        <a href="{{ route('fumetti.show', $fumetto) }}">
                                            {{ $fumetto->title }}
                                        </a>
                                    </h3>

                                    <!-- Author -->
                                    <div class="fumetto-author">
                                        <a href="{{ route('profile.public', $fumetto->user) }}" class="author-link">
                                            <i class="fas fa-user-circle me-1"></i>
                                            {{ $fumetto->user->name }}
                                        </a>
                                    </div>

                                    <!-- Plot Preview -->
                                    <p class="fumetto-plot">
                                        {{ Str::limit($fumetto->plot, 100) }}
                                    </p>

                                    <!-- Meta Info -->
                                    <div class="fumetto-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <span>{{ $fumetto->publication_year }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-hashtag me-1"></i>
                                            <span>Vol. {{ $fumetto->issue_number }}</span>
                                        </div>
                                        @if($fumetto->magazine)
                                            <div class="meta-item">
                                                <i class="fas fa-bookmark me-1"></i>
                                                <span>{{ $fumetto->magazine->name }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Stats -->
                                    <div class="fumetto-stats">
                                        @if($fumetto->reviews_count > 0)
                                            <div class="stat-item">
                                                <i class="fas fa-star text-warning"></i>
                                                <span>{{ number_format($fumetto->average_rating, 1) }} ({{ $fumetto->reviews_count }})</span>
                                            </div>
                                        @endif

                                        @if($fumetto->favorites_count > 0)
                                            <div class="stat-item">
                                                <i class="fas fa-heart text-danger"></i>
                                                <span>{{ $fumetto->favorites_count }}</span>
                                            </div>
                                        @endif

                                        <div class="stat-item">
                                            <i class="fas fa-clock text-muted"></i>
                                            <span>{{ $fumetto->published_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    @if($fumetto->price)
                                        <div class="fumetto-price">
                                            <span class="price-label">Prezzo consigliato:</span>
                                            <span class="price-value">€{{ number_format($fumetto->price, 2) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="fumetto-actions">
                                    <a href="{{ route('fumetti.show', $fumetto) }}" class="btn btn-primary flex-fill">
                                        <i class="fas fa-eye me-2"></i>Leggi Dettagli
                                    </a>

                                    @guest
                                        <button class="btn btn-outline-secondary"
                                                onclick="showLoginModal()"
                                                title="Accedi per aggiungere ai preferiti">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $fumetti->appends(request()->query())->links() }}
                </div>

            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Nessun fumetto trovato</h3>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'category']))
                            Prova a modificare i filtri di ricerca o
                            <a href="{{ route('fumetti.index') }}">visualizza tutti i fumetti</a>.
                        @else
                            Non ci sono ancora fumetti pubblicati.
                            @auth
                                <a href="{{ route('fumetti.create') }}">Pubblica il primo fumetto!</a>
                            @else
                                <a href="{{ route('register') }}">Registrati per pubblicare il tuo fumetto!</a>
                            @endauth
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section for Guests -->
    @guest
        <section class="cta-section">
            <div class="container">
                <div class="cta-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3>Vuoi pubblicare il tuo fumetto?</h3>
                            <p class="mb-0">Unisciti alla community di fumettisti indipendenti e condividi le tue opere con migliaia di appassionati!</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-rocket me-2"></i>Inizia Ora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endguest
</div>

<!-- Login Modal for Guests -->
@guest
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Accesso Richiesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                <h4>Effettua l'accesso per continuare</h4>
                <p class="text-muted">Per aggiungere fumetti ai preferiti e lasciare recensioni devi essere registrato.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('login') }}" class="btn btn-primary">Accedi</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">Registrati</a>
            </div>
        </div>
    </div>
</div>
@endguest
@endsection

@section('extra-css')
<style>
/* Catalog Hero */
.catalog-hero {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 4rem 0 3rem;
    margin-bottom: 2rem;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
    line-height: 1.6;
}

/* Filters */
.catalog-filters {
    margin-bottom: 2rem;
}

.filters-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.filter-group label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
}

/* Results Info */
.results-info {
    margin-bottom: 2rem;
}

.results-count {
    font-size: 1.1rem;
    color: var(--text-muted);
}

.count-text strong {
    color: var(--primary-color);
}

/* Fumetti Grid */
.fumetti-grid {
    margin-bottom: 3rem;
}

.fumetto-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border-color);
}

.fumetto-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

/* Cover */
.fumetto-cover {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.fumetto-card:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 3rem;
}

/* Badges */
.category-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.rating-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.rating-badge i {
    color: var(--warning-color);
}

.favorite-overlay {
    position: absolute;
    bottom: 10px;
    right: 10px;
}

/* Info */
.fumetto-info {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.fumetto-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.fumetto-title a {
    color: var(--dark-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.fumetto-title a:hover {
    color: var(--primary-color);
}

.fumetto-author {
    margin-bottom: 1rem;
}

.author-link {
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.author-link:hover {
    color: var(--primary-color);
}

.fumetto-plot {
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    flex: 1;
}

/* Meta & Stats */
.fumetto-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.meta-item {
    display: flex;
    align-items: center;
}

.fumetto-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Price */
.fumetto-price {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: var(--light-color);
    border-radius: 8px;
}

.price-label {
    font-size: 0.85rem;
    color: var(--text-muted);
    display: block;
}

.price-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--success-color);
}

/* Actions */
.fumetto-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color);
    background: var(--light-color);
    display: flex;
    gap: 0.75rem;
}

.fumetto-actions .btn {
    border-radius: 8px;
    font-weight: 500;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 2rem;
}

.empty-state h3 {
    color: var(--dark-color);
    margin-bottom: 1rem;
}

/* CTA Section */
.cta-section {
    background: var(--light-color);
    padding: 3rem 0;
    margin-top: 3rem;
}

.cta-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.cta-card h3 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .filters-card {
        padding: 1.5rem;
    }

    .fumetto-cover {
        height: 200px;
    }

    .fumetto-meta,
    .fumetto-stats {
        flex-direction: column;
        gap: 0.5rem;
    }

    .fumetto-actions {
        flex-direction: column;
    }

    .cta-card {
        text-align: center;
    }

    .cta-card .col-md-4 {
        margin-top: 1rem;
    }
}

/* Loading states */
.fumetto-card.loading {
    opacity: 0.7;
    pointer-events: none;
}

.fumetto-card.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
</style>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filters on change
    const filterForm = document.querySelector('.filters-form');
    const selects = filterForm.querySelectorAll('select');

    selects.forEach(select => {
        select.addEventListener('change', function() {
            filterForm.submit();
        });
    });

    // Show login modal for guests
    window.showLoginModal = function() {
        const modal = new bootstrap.Modal(document.getElementById('loginModal'));
        modal.show();
    };

    // Smooth scroll to results after filter
    if (window.location.search) {
        setTimeout(() => {
            document.querySelector('.fumetti-grid').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    }

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});
</script>
@endsection
