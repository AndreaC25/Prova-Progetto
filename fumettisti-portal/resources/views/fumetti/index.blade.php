@extends('layouts.app')

@section('title', 'Tutti i Fumetti - Fumettisti Portal')

@section('content')
<div class="fumetti-index-page">
    <!-- Header Section -->
    <section class="page-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-3">
                        <i class="fas fa-book-open text-orange me-3"></i>
                        Esplora i <span class="text-orange">Fumetti</span>
                    </h1>
                    <p class="lead text-light mb-4">
                        Scopri la nostra collezione di manga e fumetti.
                        <span class="text-orange fw-bold">{{ $fumetti->total() }}</span> titoli disponibili!
                    </p>
                </div>
                <div class="col-lg-6">
                    <!-- Search Bar -->
                    <div class="search-section">
                        <form method="GET" action="{{ route('fumetti.index') }}" class="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-lg"
                                       placeholder="Cerca fumetti per titolo o trama..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <!-- Mantieni altri filtri -->
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section py-4 section-dark">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <!-- Category Filters -->
                    <div class="category-filters">
                        <h6 class="text-white mb-3">
                            <i class="fas fa-filter me-2"></i>Filtra per Categoria:
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('fumetti.index', array_merge(request()->except('category'), request()->only(['search', 'sort']))) }}"
                               class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-light' }} btn-sm">
                                Tutti
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('fumetti.index', array_merge(request()->except('category'), ['category' => $category->slug] + request()->only(['search', 'sort']))) }}"
                                   class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-light' }} btn-sm">
                                    {{ $category->name }}
                                    <span class="badge bg-orange ms-2">{{ $category->fumetti_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Sort Options -->
                    <div class="sort-options">
                        <h6 class="text-white mb-3">
                            <i class="fas fa-sort me-2"></i>Ordina per:
                        </h6>
                        <form method="GET" action="{{ route('fumetti.index') }}" class="sort-form">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Più Recenti</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Più Vecchi</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titolo A-Z</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Prezzo: Basso → Alto</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Prezzo: Alto → Basso</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Migliori Recensioni</option>
                            </select>
                            <!-- Mantieni altri filtri -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fumetti Grid -->
    <section class="fumetti-grid-section py-5">
        <div class="container">
            @if($fumetti->count() > 0)
                <!-- Results Info -->
                <div class="results-info mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="text-light mb-0">
                                <i class="fas fa-info-circle text-orange me-2"></i>
                                Mostrando {{ $fumetti->firstItem() }} - {{ $fumetti->lastItem() }}
                                di {{ $fumetti->total() }} fumetti
                                @if(request('search'))
                                    per "<span class="text-orange fw-bold">{{ request('search') }}</span>"
                                @endif
                                @if(request('category'))
                                    nella categoria "<span class="text-orange fw-bold">{{ $categories->where('slug', request('category'))->first()->name ?? 'Sconosciuta' }}</span>"
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            @auth
                                <a href="{{ route('fumetti.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Aggiungi Fumetto
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-2"></i>Registrati per Pubblicare
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Fumetti Cards Grid -->
                <div class="fumetti-grid">
                    @foreach($fumetti as $fumetto)
                        <div class="fumetto-card-wrapper">
                            <div class="fumetto-card card hover-lift fade-in">
                                <!-- Badge se ha molte recensioni -->
                                @if($fumetto->reviews_count > 10)
                                    <div class="card-badge hot">
                                        <i class="fas fa-fire me-1"></i>HOT
                                    </div>
                                @elseif($fumetto->favorites_count > 5)
                                    <div class="card-badge popular">
                                        <i class="fas fa-heart me-1"></i>POPOLARE
                                    </div>
                                @endif

                                <!-- Manga Cover Image -->
                                <div class="card-image">
                                    <a href="{{ route('fumetti.show', $fumetto) }}">
                                        <img src="{{ $fumetto->cover_image ?? 'https://picsum.photos/300/400?random=' . $fumetto->id }}"
                                             alt="{{ $fumetto->title }}"
                                             class="manga-cover"
                                             loading="lazy">
                                    </a>

                                    <!-- Quick Actions -->
                                    <div class="card-actions">
                                        @auth
                                            <x-favorite-button :fumetto="$fumetto" size="sm" class="action-btn" />
                                        @else
                                            <button class="btn btn-sm btn-outline-light action-btn"
                                                    onclick="alert('Accedi per aggiungere ai preferiti')">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        @endauth

                                        <a href="{{ route('fumetti.show', $fumetto) }}"
                                           class="btn btn-sm btn-primary action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="card-body">
                                    <!-- Categories -->
                                    <div class="manga-categories mb-2">
                                        @foreach($fumetto->categories->take(2) as $category)
                                            <span class="category-tag" style="background-color: {{ $category->color ?? '#ff6b35' }}">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Title -->
                                    <h5 class="manga-title mb-2">
                                        <a href="{{ route('fumetti.show', $fumetto) }}" class="text-decoration-none">
                                            {{ $fumetto->title }}
                                        </a>
                                    </h5>

                                    <!-- Author -->
                                    <p class="manga-author text-muted mb-2">
                                        <i class="fas fa-user me-1"></i>
                                        di <a href="{{ route('profile.public', $fumetto->user) }}" class="text-orange">
                                            {{ $fumetto->user->name }}
                                        </a>
                                    </p>

                                    <!-- Rating -->
                                    @if($fumetto->reviews_count > 0)
                                        <div class="manga-rating mb-2">
                                            <div class="stars">
                                                {!! $fumetto->rating_html !!}
                                            </div>
                                            <small class="text-muted">
                                                ({{ $fumetto->reviews_count }} {{ $fumetto->reviews_count == 1 ? 'recensione' : 'recensioni' }})
                                            </small>
                                        </div>
                                    @else
                                        <div class="manga-rating mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-star-o me-1"></i>Nessuna recensione
                                            </small>
                                        </div>
                                    @endif

                                    <!-- Price -->
                                    @if($fumetto->price)
                                        <div class="manga-price">
                                            <span class="price-tag fw-bold text-success">
                                                <i class="fas fa-tag me-1"></i>€{{ number_format($fumetto->price, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="manga-price">
                                            <span class="price-tag text-muted">
                                                <i class="fas fa-gift me-1"></i>Gratuito
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Metadata -->
                                    <div class="manga-meta mt-3 pt-3 border-top">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $fumetto->publication_year }}
                                                </small>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-heart me-1"></i>
                                                    {{ $fumetto->favorites_count }}
                                                </small>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-eye me-1"></i>
                                                    {{ rand(50, 500) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-section mt-5">
                    <div class="d-flex justify-content-center">
                        {{ $fumetti->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>

                    <!-- Pagination Info -->
                    <div class="text-center mt-3">
                        <p class="text-light">
                            Pagina {{ $fumetti->currentPage() }} di {{ $fumetti->lastPage() }}
                            ({{ $fumetti->total() }} fumetti totali)
                        </p>
                    </div>
                </div>

            @else
                <!-- No Results -->
                <div class="no-results text-center py-5">
                    <div class="section-card p-5 mx-auto" style="max-width: 600px;">
                        <i class="fas fa-search fa-4x text-orange mb-4"></i>
                        <h3 class="text-white mb-3">Nessun fumetto trovato</h3>
                        @if(request('search') || request('category'))
                            <p class="text-light mb-4">
                                Non abbiamo trovato fumetti che corrispondono ai tuoi criteri di ricerca.
                                Prova a modificare i filtri o cerca qualcos'altro.
                            </p>
                            <a href="{{ route('fumetti.index') }}" class="btn btn-primary">
                                <i class="fas fa-refresh me-2"></i>Rimuovi Filtri
                            </a>
                        @else
                            <p class="text-light mb-4">
                                Non ci sono ancora fumetti pubblicati.
                                Sii il primo a condividere la tua storia!
                            </p>
                            @auth
                                <a href="{{ route('fumetti.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Pubblica il Primo Fumetto
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Registrati per Pubblicare
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@section('extra-css')
<style>
    /* Grid Layout */
    .fumetti-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Card Styling */
    .fumetto-card {
        background: var(--card-bg);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
        height: 100%;
    }

    .fumetto-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    /* Card Image */
    .card-image {
        position: relative;
        height: 350px;
        overflow: hidden;
    }

    .manga-cover {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .fumetto-card:hover .manga-cover {
        transform: scale(1.05);
    }

    /* Card Badges */
    .card-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 3;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .card-badge.hot {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        animation: pulse 2s infinite;
    }

    .card-badge.popular {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Card Actions */
    .card-actions {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        opacity: 0;
        transform: translateX(20px);
        transition: var(--transition);
    }

    .fumetto-card:hover .card-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    /* Card Content */
    .card-body {
        padding: 1.5rem;
    }

    .manga-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .category-tag {
        background: var(--primary-orange);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .manga-title a {
        color: var(--text-primary);
        font-weight: 700;
        line-height: 1.3;
        transition: var(--transition);
    }

    .manga-title a:hover {
        color: var(--primary-orange);
    }

    .manga-author {
        font-size: 0.9rem;
    }

    .manga-author a:hover {
        text-decoration: underline !important;
    }

    /* Rating */
    .stars {
        display: inline-flex;
        gap: 2px;
        margin-right: 0.5rem;
    }

    .stars i {
        font-size: 0.9rem;
    }

    /* Price */
    .price-tag {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 20px;
        display: inline-block;
    }

    /* Metadata */
    .manga-meta {
        border-top: 1px solid rgba(0, 0, 0, 0.1) !important;
    }

    /* Filters Section */
    .section-dark {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 107, 53, 0.2);
        border-bottom: 1px solid rgba(255, 107, 53, 0.2);
    }

    .category-filters .btn,
    .sort-form .form-select {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .bg-orange {
        background-color: var(--primary-orange) !important;
    }

    /* Search Form */
    .search-form .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(255, 107, 53, 0.3);
        border-radius: 50px 0 0 50px;
    }

    .search-form .btn {
        border-radius: 0 50px 50px 0;
        padding: 0.75rem 1.5rem;
    }

    /* Pagination */
    .pagination {
        --bs-pagination-bg: rgba(255, 255, 255, 0.9);
        --bs-pagination-border-color: rgba(255, 107, 53, 0.2);
        --bs-pagination-hover-bg: var(--primary-orange);
        --bs-pagination-hover-border-color: var(--primary-orange);
        --bs-pagination-hover-color: white;
        --bs-pagination-active-bg: var(--primary-orange);
        --bs-pagination-active-border-color: var(--primary-orange);
    }

    /* No Results */
    .section-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 107, 53, 0.2);
        border-radius: var(--border-radius-lg);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .fumetti-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .card-image {
            height: 300px;
        }

        .filters-section .row {
            flex-direction: column;
            gap: 1rem;
        }

        .category-filters .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .fumetti-grid {
            grid-template-columns: 1fr;
        }

        .search-form .form-control {
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .search-form .btn {
            border-radius: 10px;
            width: 100%;
        }
    }

    /* Animation */
    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    .fumetto-card:nth-child(1) { animation-delay: 0.1s; }
    .fumetto-card:nth-child(2) { animation-delay: 0.2s; }
    .fumetto-card:nth-child(3) { animation-delay: 0.3s; }
    .fumetto-card:nth-child(4) { animation-delay: 0.4s; }
    .fumetto-card:nth-child(5) { animation-delay: 0.5s; }
</style>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading per le immagini
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

    // Smooth scroll per i filtri
    document.querySelectorAll('.category-filters a').forEach(link => {
        link.addEventListener('click', function() {
            // Aggiungi un piccolo delay per l'animazione
            setTimeout(() => {
                window.scrollTo({
                    top: document.querySelector('.fumetti-grid-section').offsetTop - 100,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });

    // Auto-submit per il sort
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // Mostra loading state
            const option = this.options[this.selectedIndex];
            option.textContent = '⏳ ' + option.textContent;
            this.form.submit();
        });
    }

    // Animation on scroll per le card
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const cardObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                cardObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Osserva tutte le card per l'animazione
    document.querySelectorAll('.fumetto-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        card.style.transitionDelay = `${index * 0.1}s`;
        cardObserver.observe(card);
    });
});
</script>
@endsection
