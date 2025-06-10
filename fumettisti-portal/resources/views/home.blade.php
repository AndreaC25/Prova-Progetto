@extends('layouts.app')

@section('title', 'Fumettisti Portal - La Community dei Fumetti')

@section('content')
<!-- Hero Banner principale -->
<section class="hero-banner">
    <div class="container-fluid p-0">
        <div class="hero-image-container">
            <img src="{{ asset('images/hero-fumetti.jpg') }}" alt="Fumetti in Evidenza" class="hero-image">
            <div class="hero-overlay">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="hero-badge">🎨 FUMETTI IN EVIDENZA</div>
                            <h1 class="hero-title">Scopri i migliori fumetti della community!</h1>
                            <p class="hero-subtitle">Leggi, condividi e pubblica i tuoi fumetti preferiti</p>
                            <div class="hero-buttons">
                                <a href="{{ route('fumetti.index') }}" class="btn btn-hero-primary">
                                    <i class="fas fa-book me-2"></i>Sfoglia Fumetti
                                </a>
                                @guest
                                    <a href="{{ route('register') }}" class="btn btn-hero-secondary">
                                        <i class="fas fa-user-plus me-2"></i>Registrati Gratis
                                    </a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sezione Fumetti in Evidenza -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">⭐ IN EVIDENZA</div>
            <h2 class="section-title">I fumetti più votati dalla community</h2>
            <p class="section-subtitle">Scopri le storie che hanno conquistato i lettori</p>
        </div>

        <div class="fumetti-grid">
            @forelse($featured_fumetti as $fumetto)
                <div class="fumetto-card">
                    @if($fumetto->reviews_count > 10)
                        <div class="card-badge hot">🔥 HOT</div>
                    @endif
                    <div class="card-image">
                        <a href="{{ route('fumetti.show', $fumetto) }}">
                            <img src="{{ $fumetto->cover_image_url }}" alt="{{ $fumetto->title }}">
                        </a>
                    </div>
                    <div class="card-content">
                        <div class="card-categories">
                            @foreach($fumetto->categories->take(2) as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <h3 class="card-title">
                            <a href="{{ route('fumetti.show', $fumetto) }}">{{ $fumetto->title }}</a>
                        </h3>
                        <p class="card-author">di {{ $fumetto->user->name }}</p>
                        <div class="card-meta">
                            <div class="rating">
                                {!! $fumetto->stars_html !!}
                                <span>({{ $fumetto->reviews_count }})</span>
                            </div>
                            @if($fumetto->price)
                                <div class="price">€{{ number_format($fumetto->price, 2) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Placeholder cards per quando non ci sono fumetti -->
                @for($i = 1; $i <= 4; $i++)
                    <div class="fumetto-card placeholder">
                        <div class="card-image">
                            <div class="placeholder-image">
                                <i class="fas fa-book fa-3x"></i>
                                <p>Fumetto #{{ $i }}</p>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-categories">
                                <span class="category-tag">Manga</span>
                            </div>
                            <h3 class="card-title">Le Avventure di Esempio {{ $i }}</h3>
                            <p class="card-author">di Autore Esempio</p>
                            <div class="card-meta">
                                <div class="rating">
                                    ⭐⭐⭐⭐⭐ ({{ rand(5, 50) }})
                                </div>
                                <div class="price">€{{ number_format(rand(5, 15) + 0.99, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('fumetti.index') }}" class="btn btn-outline-primary btn-lg">
                Vedi Tutti i Fumetti <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Banner Promozionale Categorie -->
<section class="promo-banner">
    <div class="container-fluid p-0">
        <div class="promo-image-container">
            <img src="{{ asset('images/categorie-banner.jpg') }}" alt="Esplora per Categoria" class="promo-image">
            <div class="promo-overlay">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h2 class="promo-title">Esplora per Categoria</h2>
                            <p class="promo-subtitle">Trova il tuo genere preferito tra manga, superhero, fantasy e molto altro</p>
                            <a href="{{ route('fumetti.index') }}" class="btn btn-promo">
                                Scopri le Categorie
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sezione Ultimi Fumetti -->
<section class="latest-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">🆕 NUOVE USCITE</div>
            <h2 class="section-title">Ultimi fumetti pubblicati</h2>
            <p class="section-subtitle">Le novità fresche dalla community</p>
        </div>

        <div class="fumetti-grid">
            @forelse($latest_fumetti as $fumetto)
                <div class="fumetto-card">
                    <div class="card-badge new">🆕 NUOVO</div>
                    <div class="card-image">
                        <a href="{{ route('fumetti.show', $fumetto) }}">
                            <img src="{{ $fumetto->cover_image_url }}" alt="{{ $fumetto->title }}">
                        </a>
                    </div>
                    <div class="card-content">
                        <div class="card-categories">
                            @foreach($fumetto->categories->take(2) as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <h3 class="card-title">
                            <a href="{{ route('fumetti.show', $fumetto) }}">{{ $fumetto->title }}</a>
                        </h3>
                        <p class="card-author">di {{ $fumetto->user->name }}</p>
                        <div class="card-meta">
                            <div class="rating">
                                {!! $fumetto->stars_html !!}
                                <span>({{ $fumetto->reviews_count }})</span>
                            </div>
                            <div class="published-date">{{ $fumetto->published_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            @empty
                @for($i = 1; $i <= 4; $i++)
                    <div class="fumetto-card placeholder">
                        <div class="card-badge new">🆕 NUOVO</div>
                        <div class="card-image">
                            <div class="placeholder-image">
                                <i class="fas fa-book fa-3x"></i>
                                <p>Nuovo Fumetto #{{ $i }}</p>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-categories">
                                <span class="category-tag">Fantasy</span>
                            </div>
                            <h3 class="card-title">Nuovo Racconto {{ $i }}</h3>
                            <p class="card-author">di Nuovo Autore</p>
                            <div class="card-meta">
                                <div class="rating">
                                    ⭐⭐⭐⭐☆ ({{ rand(1, 10) }})
                                </div>
                                <div class="published-date">{{ rand(1, 7) }} giorni fa</div>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>

<!-- Sezione Statistiche -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-number">{{ number_format($stats['total_fumetti']) }}</div>
                    <div class="stat-label">Fumetti Pubblicati</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">{{ number_format($stats['total_artists']) }}</div>
                    <div class="stat-label">Artisti Attivi</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-number">{{ number_format($stats['total_reviews']) }}</div>
                    <div class="stat-label">Recensioni</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">🏷️</div>
                    <div class="stat-number">{{ number_format($stats['total_categories']) }}</div>
                    <div class="stat-label">Categorie</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sezione CTA per Artisti -->
<section class="artist-cta-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="cta-card">
                    <div class="cta-icon">🎨</div>
                    <h2 class="cta-title">Sei un fumettista?</h2>
                    <p class="cta-description">
                        Unisciti alla nostra community! Pubblica i tuoi fumetti, ricevi feedback dalla community
                        e connettiti con altri artisti appassionati.
                    </p>
                    <div class="cta-buttons">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-cta-primary">
                                <i class="fas fa-user-plus me-2"></i>Registrati Ora
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-cta-secondary">
                                Hai già un account?
                            </a>
                        @else
                            <a href="{{ route('fumetti.create') }}" class="btn btn-cta-primary">
                                <i class="fas fa-plus me-2"></i>Pubblica il tuo Fumetto
                            </a>
                            <a href="{{ route('fumetti.dashboard') }}" class="btn btn-cta-secondary">
                                Dashboard Fumettista
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('extra-css')
<style>
:root {
    --primary-color: #6366f1;
    --primary-dark: #4f46e5;
    --accent-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --dark-color: #1f2937;
    --light-color: #f9fafb;
    --border-color: #e5e7eb;
    --text-muted: #6b7280;
}

/* Hero Banner */
.hero-banner {
    position: relative;
    margin-bottom: 3rem;
}

.hero-image-container {
    position: relative;
    height: 500px;
    overflow: hidden;
}

.hero-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    background: linear-gradient(45deg, rgba(99, 102, 241, 0.8), rgba(139, 92, 246, 0.6));
}

.hero-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-block;
    margin-bottom: 1rem;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-hero-primary {
    background: white;
    color: var(--primary-color);
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid white;
}

.btn-hero-primary:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
}

.btn-hero-secondary {
    background: transparent;
    color: white;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid white;
    transition: all 0.3s ease;
}

.btn-hero-secondary:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-2px);
}

/* Sections */
.featured-section, .latest-section {
    padding: 4rem 0;
    background: white;
}

.latest-section {
    background: var(--light-color);
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-badge {
    background: linear-gradient(135deg, var(--accent-color), #f97316);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-block;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-muted);
}

/* Fumetti Grid */
.fumetti-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.fumetto-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.fumetto-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.fumetto-card.placeholder {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
}

.card-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 2;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-badge.hot {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.card-badge.new {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.card-image {
    height: 300px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.fumetto-card:hover .card-image img {
    transform: scale(1.05);
}

.placeholder-image {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
}

.card-content {
    padding: 1.5rem;
}

.card-categories {
    margin-bottom: 0.75rem;
}

.category-tag {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.card-title a {
    color: var(--dark-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.card-title a:hover {
    color: var(--primary-color);
}

.card-author {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.rating {
    font-size: 0.875rem;
}

.price {
    font-weight: 600;
    color: var(--success-color);
    font-size: 1.1rem;
}

.published-date {
    font-size: 0.875rem;
    color: var(--text-muted);
}

/* Promo Banner */
.promo-banner {
    margin: 4rem 0;
}

.promo-image-container {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.promo-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.6);
}

.promo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    background: linear-gradient(45deg, rgba(245, 158, 11, 0.8), rgba(251, 146, 60, 0.6));
}

.promo-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 1rem;
}

.promo-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
}

.btn-promo {
    background: white;
    color: var(--accent-color);
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-promo:hover {
    background: var(--accent-color);
    color: white;
    transform: translateY(-2px);
}

/* Stats Section */
.stats-section {
    padding: 4rem 0;
    background: white;
}

.stat-card {
    text-align: center;
    padding: 2rem;
    border-radius: 12px;
    background: var(--light-color);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-muted);
    font-weight: 500;
}

/* CTA Section */
.artist-cta-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
}

.cta-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.cta-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 1rem;
}

.cta-description {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta-primary {
    background: white;
    color: var(--primary-color);
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-cta-primary:hover {
    background: var(--accent-color);
    color: white;
    transform: translateY(-2px);
}

.btn-cta-secondary {
    background: transparent;
    color: white;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 500;
    text-decoration: none;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-cta-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .promo-title {
        font-size: 2rem;
    }

    .cta-title {
        font-size: 2rem;
    }

    .fumetti-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .hero-buttons,
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 576px) {
    .fumetti-grid {
        grid-template-columns: 1fr;
    }

    .hero-image-container {
        height: 400px;
    }

    .cta-card {
        padding: 2rem;
    }
}
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

    // Animazioni al scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.fumetto-card, .stat-card');

        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    // Inizializza elementi per animazione
    document.querySelectorAll('.fumetto-card, .stat-card').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
    });

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Esegui al caricamento
});
</script>
@endsection
