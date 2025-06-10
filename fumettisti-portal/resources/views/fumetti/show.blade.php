{{-- resources/views/fumetti/show.blade.php --}}
@extends('layouts.app')

@section('title', $fumetto->title . ' - Fumettisti Portal')

@section('meta')
    <meta name="description" content="{{ Str::limit($fumetto->plot, 160) }}">
    <meta property="og:title" content="{{ $fumetto->title }} - Fumettisti Portal">
    <meta property="og:description" content="{{ Str::limit($fumetto->plot, 160) }}">
    <meta property="og:image" content="{{ $fumetto->cover_image_url }}">
    <meta property="og:type" content="book">
@endsection

@section('content')
<div class="fumetto-detail">
    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('fumetti.index') }}">Fumetti</a>
                </li>
                @if($fumetto->categories->first())
                    <li class="breadcrumb-item">
                        <a href="{{ route('fumetti.index', ['category' => $fumetto->categories->first()->slug]) }}">
                            {{ $fumetto->categories->first()->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active">{{ $fumetto->title }}</li>
            </ol>
        </nav>
    </div>

    <!-- Hero Section -->
    <section class="fumetto-hero">
        <div class="container">
            <div class="row align-items-start">
                <!-- Copertina -->
                <div class="col-lg-4 col-md-5">
                    <div class="cover-section sticky-top">
                        <div class="cover-container">
                            <img src="{{ $fumetto->cover_image_url }}"
                                 alt="{{ $fumetto->title }}"
                                 class="cover-image">

                            <!-- Badge Status -->
                            @auth
                                @if($fumetto->user_id === auth()->id())
                                    <div class="owner-badge">
                                        <i class="fas fa-crown"></i> Tuo Fumetto
                                    </div>
                                @endif
                            @endauth

                            <!-- Rating Badge -->
                            @if($fumetto->reviews_count > 0)
                                <div class="rating-badge">
                                    <div class="rating-stars">
                                        {!! $fumetto->stars_html !!}
                                    </div>
                                    <div class="rating-text">
                                        {{ number_format($fumetto->average_rating, 1) }}/5
                                        <small>({{ $fumetto->reviews_count }} recensioni)</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            @guest
                                <div class="guest-actions">
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                        <i class="fas fa-heart me-2"></i>
                                        Aggiungi ai Preferiti
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-3">
                                        <i class="fas fa-star me-2"></i>
                                        Lascia una Recensione
                                    </a>
                                    <p class="text-center text-muted small">
                                        <a href="{{ route('login') }}">Accedi</a> o
                                        <a href="{{ route('register') }}">registrati</a>
                                        per interagire con questo fumetto
                                    </p>
                                </div>
                            @else
                                <div class="user-actions">
                                    @if($fumetto->user_id === auth()->id())
                                        <!-- Azioni Proprietario -->
                                        <a href="{{ route('fumetti.edit', $fumetto) }}" class="btn btn-warning w-100 mb-2">
                                            <i class="fas fa-edit me-2"></i>
                                            Modifica Fumetto
                                        </a>
                                        @if(!$fumetto->is_published)
                                            <form action="{{ route('fumetti.publish', $fumetto) }}" method="POST" class="mb-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-rocket me-2"></i>
                                                    Pubblica Ora
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <!-- Azioni Altri Utenti -->
                                        <x-favorite-button :fumetto="$fumetto" class="btn-lg w-100 mb-2" />
                                        @if(!$userReview)
                                            <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                                <i class="fas fa-star me-2"></i>
                                                Lascia una Recensione
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endauth

                            <!-- Condivisione -->
                            <div class="share-section">
                                <h6 class="share-title">Condividi questo fumetto</h6>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                       target="_blank"
                                       class="share-btn facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($fumetto->title) }}"
                                       target="_blank"
                                       class="share-btn twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($fumetto->title . ' - ' . request()->url()) }}"
                                       target="_blank"
                                       class="share-btn whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <button class="share-btn copy" onclick="copyToClipboard('{{ request()->url() }}')">
                                        <i class="fas fa-link"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informazioni Principali -->
                <div class="col-lg-8 col-md-7">
                    <div class="fumetto-info">
                        <!-- Header -->
                        <div class="info-header">
                            <!-- Categorie -->
                            <div class="categories-list mb-3">
                                @foreach($fumetto->categories as $category)
                                    <a href="{{ route('fumetti.index', ['category' => $category->slug]) }}"
                                       class="category-tag">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Titolo -->
                            <h1 class="fumetto-title">{{ $fumetto->title }}</h1>

                            <!-- Sottotitolo -->
                            <div class="fumetto-subtitle">
                                <span class="issue-info">
                                    Volume {{ $fumetto->issue_number }}
                                    @if($fumetto->magazine)
                                        • {{ $fumetto->magazine->name }}
                                    @endif
                                </span>
                                <span class="publication-year">{{ $fumetto->publication_year }}</span>
                            </div>

                            <!-- Autore -->
                            <div class="author-section">
                                <div class="author-card">
                                    @if($fumetto->user->profile && $fumetto->user->profile->avatar)
                                        <img src="{{ $fumetto->user->profile->avatar_url }}"
                                             alt="{{ $fumetto->user->name }}"
                                             class="author-avatar">
                                    @else
                                        <div class="author-avatar-placeholder">
                                            {{ substr($fumetto->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="author-info">
                                        <div class="author-name">
                                            <a href="{{ route('profile.public', $fumetto->user) }}">
                                                {{ $fumetto->user->name }}
                                            </a>
                                        </div>
                                        <div class="author-meta">
                                            <span class="author-role">Fumettista</span>
                                            <span class="publish-date">
                                                Pubblicato {{ $fumetto->published_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if($fumetto->user->profile && $fumetto->user->profile->bio)
                                            <p class="author-bio">{{ Str::limit($fumetto->user->profile->bio, 100) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Stats Quick -->
                            <div class="quick-stats">
                                @if($fumetto->reviews_count > 0)
                                    <div class="stat-item">
                                        <i class="fas fa-star text-warning"></i>
                                        <span>{{ number_format($fumetto->average_rating, 1) }} ({{ $fumetto->reviews_count }} recensioni)</span>
                                    </div>
                                @endif

                                @if($fumetto->favorites_count > 0)
                                    <div class="stat-item">
                                        <i class="fas fa-heart text-danger"></i>
                                        <span>{{ $fumetto->favorites_count }} nei preferiti</span>
                                    </div>
                                @endif

                                @if($fumetto->price)
                                    <div class="stat-item">
                                        <i class="fas fa-tag text-success"></i>
                                        <span>€{{ number_format($fumetto->price, 2) }}</span>
                                    </div>
                                @endif

                                <div class="stat-item">
                                    <i class="fas fa-eye text-info"></i>
                                    <span>Visualizzato oggi</span>
                                </div>
                            </div>
                        </div>

                        <!-- Trama -->
                        <div class="plot-section">
                            <h3 class="section-title">
                                <i class="fas fa-book-open me-2"></i>
                                Trama
                            </h3>
                            <div class="plot-content">
                                <p class="plot-text">{{ $fumetto->plot }}</p>
                            </div>
                        </div>

                        <!-- Dettagli Tecnici -->
                        <div class="technical-details">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Dettagli Tecnici
                            </h3>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <label>Numero Volume:</label>
                                    <span>#{{ $fumetto->issue_number }}</span>
                                </div>
                                <div class="detail-item">
                                    <label>Anno di Pubblicazione:</label>
                                    <span>{{ $fumetto->publication_year }}</span>
                                </div>
                                @if($fumetto->magazine)
                                    <div class="detail-item">
                                        <label>Rivista/Casa Editrice:</label>
                                        <span>{{ $fumetto->magazine->name }}</span>
                                    </div>
                                @endif
                                <div class="detail-item">
                                    <label>Categorie:</label>
                                    <span>
                                        @foreach($fumetto->categories as $category)
                                            {{ $category->name }}@if(!$loop->last), @endif
                                        @endforeach
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label>Data Pubblicazione:</label>
                                    <span>{{ $fumetto->published_at->format('d M Y') }}</span>
                                </div>
                                @if($fumetto->price)
                                    <div class="detail-item">
                                        <label>Prezzo Consigliato:</label>
                                        <span class="price-highlight">€{{ number_format($fumetto->price, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Sezione -->
    <section class="content-tabs">
        <div class="container">
            <ul class="nav nav-tabs custom-tabs" id="fumettoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="recensioni-tab"
                            data-bs-toggle="tab" data-bs-target="#recensioni" type="button">
                        <i class="fas fa-star me-2"></i>
                        Recensioni ({{ $fumetto->reviews_count }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="autore-tab"
                            data-bs-toggle="tab" data-bs-target="#autore" type="button">
                        <i class="fas fa-user me-2"></i>
                        L'Autore
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="correlati-tab"
                            data-bs-toggle="tab" data-bs-target="#correlati" type="button">
                        <i class="fas fa-book me-2"></i>
                        Fumetti Correlati
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="fumettoTabsContent">
                <!-- Tab Recensioni -->
                <div class="tab-pane fade show active" id="recensioni" role="tabpanel">
                    @include('components.reviews-section')
                </div>

                <!-- Tab Autore -->
                <div class="tab-pane fade" id="autore" role="tabpanel">
                    <div class="author-detailed-section">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="author-profile-card">
                                    @if($fumetto->user->profile && $fumetto->user->profile->avatar)
                                        <img src="{{ $fumetto->user->profile->avatar_url }}"
                                             alt="{{ $fumetto->user->name }}"
                                             class="author-profile-image">
                                    @else
                                        <div class="author-profile-placeholder">
                                            {{ substr($fumetto->user->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <h4>{{ $fumetto->user->name }}</h4>

                                    @if($fumetto->user->profile)
                                        @if($fumetto->user->profile->bio)
                                            <p class="author-full-bio">{{ $fumetto->user->profile->bio }}</p>
                                        @endif

                                        <!-- Social Links -->
                                        @if($fumetto->user->profile->social_links && array_filter($fumetto->user->profile->social_links))
                                            <div class="author-social">
                                                @if($fumetto->user->profile->social_links['facebook'] ?? null)
                                                    <a href="{{ $fumetto->user->profile->social_links['facebook'] }}" target="_blank" class="social-btn facebook">
                                                        <i class="fab fa-facebook"></i>
                                                    </a>
                                                @endif
                                                @if($fumetto->user->profile->social_links['instagram'] ?? null)
                                                    <a href="{{ $fumetto->user->profile->social_links['instagram'] }}" target="_blank" class="social-btn instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                @endif
                                                @if($fumetto->user->profile->social_links['twitter'] ?? null)
                                                    <a href="{{ $fumetto->user->profile->social_links['twitter'] }}" target="_blank" class="social-btn twitter">
                                                        <i class="fab fa-twitter"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    @endif

                                    <a href="{{ route('profile.public', $fumetto->user) }}" class="btn btn-outline-primary w-100">
                                        Vedi Profilo Completo
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="author-stats">
                                    <h5>Statistiche Autore</h5>
                                    <div class="stats-grid">
                                        <div class="stat-card">
                                            <div class="stat-number">{{ $fumetto->user->fumetti()->published()->count() }}</div>
                                            <div class="stat-label">Fumetti Pubblicati</div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-number">
                                                {{ number_format($fumetto->user->fumetti()->published()->withAvg('approvedReviews', 'rating')->get()->avg('approved_reviews_avg_rating') ?? 0, 1) }}
                                            </div>
                                            <div class="stat-label">Rating Medio</div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-number">{{ $fumetto->user->created_at->diffInDays() }}</div>
                                            <div class="stat-label">Giorni nella Community</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Correlati -->
                <div class="tab-pane fade" id="correlati" role="tabpanel">
                    @if($relatedFumetti->count() > 0)
                        <div class="related-fumetti">
                            <h5>Altri fumetti che potrebbero interessarti</h5>
                            <div class="row">
                                @foreach($relatedFumetti as $related)
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                        <div class="related-card">
                                            <div class="related-cover">
                                                <a href="{{ route('fumetti.show', $related) }}">
                                                    <img src="{{ $related->cover_image_url }}" alt="{{ $related->title }}">
                                                </a>
                                            </div>
                                            <div class="related-info">
                                                <h6><a href="{{ route('fumetti.show', $related) }}">{{ $related->title }}</a></h6>
                                                <p class="related-meta">
                                                    #{{ $related->issue_number }} • {{ $related->publication_year }}
                                                </p>
                                                @if($related->reviews_count > 0)
                                                    <div class="related-rating">
                                                        {!! $related->stars_html !!}
                                                        <small>({{ $related->reviews_count }})</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5>Nessun fumetto correlato</h5>
                            <p class="text-muted">Esplora il <a href="{{ route('fumetti.index') }}">catalogo completo</a> per scoprire altri fumetti!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal per Ospiti -->
@guest
<div class="modal fade" id="guestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Unisciti alla Community!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h4>Per interagire con i fumetti devi essere registrato</h4>
                <p class="text-muted mb-4">
                    Registrati gratuitamente per aggiungere fumetti ai preferiti, lasciare recensioni e pubblicare i tuoi fumetti!
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Registrati Gratis
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Hai già un account? Accedi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

@endsection

@section('extra-css')
<style>
/* Variabili CSS */
:root {
    --primary-color: #6366f1;
    --primary-dark: #4f46e5;
    --accent-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
    --dark-color: #1f2937;
    --light-color: #f9fafb;
    --border-color: #e5e7eb;
    --text-muted: #6b7280;
}

/* Layout */
.fumetto-detail {
    background: var(--light-color);
    min-height: 100vh;
}

.breadcrumb {
    background: none;
    padding: 0;
}

.breadcrumb-item a {
    color: var(--text-muted);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
}

/* Hero Section */
.fumetto-hero {
    background: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.cover-section {
    top: 2rem;
}

.cover-container {
    position: relative;
    text-align: center;
    margin-bottom: 2rem;
}

.cover-image {
    width: 100%;
    max-width: 350px;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
}

.cover-image:hover {
    transform: scale(1.02);
}

.owner-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, var(--warning-color), #f97316);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
}

.rating-badge {
    background: rgba(0,0,0,0.9);
    color: white;
    padding: 1rem;
    border-radius: 12px;
    margin-top: 1rem;
    backdrop-filter: blur(10px);
}

.rating-stars {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.rating-text {
    font-size: 0.875rem;
}

/* Action Buttons */
.action-buttons {
    max-width: 350px;
    margin: 0 auto;
}

.guest-actions p {
    margin-top: 1rem;
}

.share-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.share-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 1rem;
    text-align: center;
}

.share-buttons {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
}

.share-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.share-btn.facebook { background: #1877f2; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.whatsapp { background: #25d366; }
.share-btn.copy { background: var(--dark-color); }

/* Info Section */
.fumetto-info {
    padding-left: 2rem;
}

.categories-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.category-tag {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-tag:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-1px);
}

.fumetto-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.fumetto-subtitle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    color: var(--text-muted);
}

.issue-info {
    font-size: 1.1rem;
    font-weight: 500;
}

.publication-year {
    font-size: 1rem;
    background: var(--light-color);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
}

/* Author Section */
.author-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--light-color);
    border-radius: 12px;
}

.author-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.author-avatar-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.5rem;
}

.author-info {
    flex: 1;
}

.author-name a {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-color);
    text-decoration: none;
}

.author-name a:hover {
    color: var(--primary-color);
}

.author-meta {
    display: flex;
    gap: 1rem;
    margin: 0.25rem 0;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.author-bio {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0.5rem 0 0 0;
}

/* Quick Stats */
.quick-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-muted);
}

/* Section Titles */
.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

/* Plot Section */
.plot-section {
    margin-bottom: 2rem;
}

.plot-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.plot-text {
    font-size: 1.1rem;
    line-height: 1.7;
    color: var(--dark-color);
    margin: 0;
}

/* Technical Details */
.technical-details {
    margin-bottom: 2rem;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    background: white;
    padding: 2rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item label {
    font-weight: 500;
    color: var(--text-muted);
    margin: 0;
}

.detail-item span {
    font-weight: 600;
    color: var(--dark-color);
}

.price-highlight {
    color: var(--success-color) !important;
    font-size: 1.1rem;
}

/* Tabs */
.content-tabs {
    background: white;
    padding-top: 2rem;
}

.custom-tabs {
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 2rem;
}

.custom-tabs .nav-link {
    border: none;
    color: var(--text-muted);
    font-weight: 500;
    padding: 1rem 1.5rem;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.custom-tabs .nav-link.active,
.custom-tabs .nav-link:hover {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: none;
}

.tab-content {
    padding: 2rem 0;
}

/* Author Detailed Section */
.author-detailed-section {
    padding: 2rem 0;
}

.author-profile-card {
    text-align: center;
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.author-profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
    border: 4px solid var(--primary-color);
}

.author-profile-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 2rem;
    margin: 0 auto 1rem;
    border: 4px solid var(--primary-color);
}

.author-full-bio {
    color: var(--text-muted);
    line-height: 1.6;
    margin: 1rem 0;
}

.author-social {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    margin: 1.5rem 0;
}

.social-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-btn:hover {
    transform: translateY(-2px);
    color: white;
}

.social-btn.facebook { background: #1877f2; }
.social-btn.instagram { background: #e4405f; }
.social-btn.twitter { background: #1da1f2; }

.author-stats {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.stat-card {
    text-align: center;
    padding: 1.5rem;
    background: var(--light-color);
    border-radius: 8px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Related Fumetti */
.related-fumetti {
    padding: 2rem 0;
}

.related-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.related-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.related-cover {
    height: 200px;
    overflow: hidden;
}

.related-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-card:hover .related-cover img {
    transform: scale(1.05);
}

.related-info {
    padding: 1rem;
}

.related-info h6 {
    margin-bottom: 0.5rem;
}

.related-info h6 a {
    color: var(--dark-color);
    text-decoration: none;
    font-weight: 600;
}

.related-info h6 a:hover {
    color: var(--primary-color);
}

.related-meta {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.related-rating {
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .fumetto-info {
        padding-left: 0;
        margin-top: 2rem;
    }

    .fumetto-title {
        font-size: 2rem;
    }

    .fumetto-subtitle {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .author-card {
        flex-direction: column;
        text-align: center;
    }

    .quick-stats {
        flex-direction: column;
        gap: 1rem;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }
}

@media (max-width: 576px) {
    .cover-image {
        max-width: 250px;
    }

    .share-buttons {
        flex-wrap: wrap;
    }

    .categories-list {
        justify-content: center;
    }
}

/* Animazioni */
.fumetto-detail {
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.related-card {
    animation: slideUp 0.6s ease forwards;
}

.related-card:nth-child(1) { animation-delay: 0.1s; }
.related-card:nth-child(2) { animation-delay: 0.2s; }
.related-card:nth-child(3) { animation-delay: 0.3s; }
.related-card:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard function
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const copyBtn = document.querySelector('.share-btn.copy');
            const originalIcon = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check"></i>';
            copyBtn.style.background = 'var(--success-color)';

            setTimeout(() => {
                copyBtn.innerHTML = originalIcon;
                copyBtn.style.background = 'var(--dark-color)';
            }, 2000);

            // Show toast
            showToast('Link copiato negli appunti!', 'success');
        }).catch(function() {
            showToast('Errore nel copiare il link', 'error');
        });
    };

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Smooth scroll per i tab
    document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            const contentTabs = document.querySelector('.content-tabs');
            contentTabs.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Auto-show guest modal per azioni riservate
    const guestActions = document.querySelectorAll('.guest-actions a[href*="login"]');
    guestActions.forEach(action => {
        action.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('guestModal'));
            modal.show();
        });
    });

    // Lazy loading per immagini correlate
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

    // Track view per analytics (placeholder)
    // trackFumettoView(fumettoId);
});
</script>
@endsection
