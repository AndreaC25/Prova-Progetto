@extends('layouts.app')

@section('title', 'Fumettisti Portal - La Community dei Fumetti')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <div class="hero-content fade-in">
                    <h1 class="display-3 fw-bold text-white mb-4">
                        Benvenuto su
                        <span class="text-orange">Fumettisti Portal</span>
                    </h1>
                    <p class="lead text-light mb-4">
                        La community italiana dedicata ai fumetti, dove artisti e appassionati si incontrano per condividere storie straordinarie.
                    </p>
                    <div class="hero-buttons d-flex flex-wrap gap-3">
                        <a href="{{ route('fumetti.index') }}" class="btn btn-primary btn-lg hover-lift">
                            <i class="fas fa-book me-2"></i>Esplora Fumetti
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg hover-lift">
                                <i class="fas fa-user-plus me-2"></i>Unisciti a Noi
                            </a>
                        @else
                            <a href="{{ route('fumetti.create') }}" class="btn btn-outline-primary btn-lg hover-lift">
                                <i class="fas fa-plus me-2"></i>Pubblica Fumetto
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-illustration float-animation">
                    <i class="fas fa-book-open text-orange" style="font-size: 12rem; opacity: 0.8; text-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-4 fw-bold text-white mb-3">
                    Perché scegliere
                    <span class="text-orange">Fumettisti Portal</span>?
                </h2>
                <p class="lead text-light">Una piattaforma completa per fumettisti e appassionati</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 hover-lift fade-in">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-orange mb-3">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Community Attiva</h5>
                        <p class="card-text text-muted">
                            Connettiti con fumettisti e appassionati da tutta Italia. Condividi le tue opere e scopri nuovi talenti.
                        </p>
                        <a href="{{ route('fumetti.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-right me-2"></i>Scopri di più
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 hover-lift fade-in" style="animation-delay: 0.2s;">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-orange mb-3">
                            <i class="fas fa-palette fa-3x"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Pubblica le tue Opere</h5>
                        <p class="card-text text-muted">
                            Carica i tuoi fumetti, ricevi feedback dalla community e fai crescere la tua popolarità come artista.
                        </p>
                        @auth
                            <a href="{{ route('fumetti.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Inizia ora
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-user-plus me-2"></i>Registrati
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 hover-lift fade-in" style="animation-delay: 0.4s;">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-orange mb-3">
                            <i class="fas fa-star fa-3x"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Sistema di Recensioni</h5>
                        <p class="card-text text-muted">
                            Recensisci i fumetti che ami, valuta le opere e aiuta altri lettori a scoprire nuove storie.
                        </p>
                        <a href="{{ route('fumetti.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-search me-2"></i>Esplora
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 hover-lift fade-in" style="animation-delay: 0.6s;">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-orange mb-3">
                            <i class="fas fa-search fa-3x"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Ricerca Avanzata</h5>
                        <p class="card-text text-muted">
                            Trova facilmente fumetti per categoria, autore, anno di pubblicazione e molto altro.
                        </p>
                        <a href="{{ route('fumetti.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-filter me-2"></i>Prova ora
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 hover-lift fade-in" style="animation-delay: 0.8s;">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-orange mb-3">
                            <i class="fas fa-heart fa-3x"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Lista Preferiti</h5>
                        <p class="card-text text-muted">
                            Salva i tuoi fumetti preferiti e crea la tua collezione personale per non perdere mai di vista le opere che ami.
                        </p>
                        @auth
                            <a href="{{ route('profile.show') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-user me-2"></i>Il tuo profilo
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Accedi
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 hover-lift fade-in" style="animation-delay: 1s;">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-orange mb-3">
                            <i class="fas fa-mobile-alt fa-3x"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Mobile Friendly</h5>
                        <p class="card-text text-muted">
                            Accedi alla piattaforma da qualsiasi dispositivo. Design responsive ottimizzato per mobile e tablet.
                        </p>
                        <a href="{{ route('contact.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-envelope me-2"></i>Contattaci
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5 mt-5">
    <div class="container">
        <div class="section-card p-5">
            <div class="row text-center">
                <div class="col-12 mb-5">
                    <h2 class="display-5 fw-bold text-white mb-3">
                        La nostra <span class="text-orange">Community</span> in numeri
                    </h2>
                    <p class="lead text-light">Unisciti a migliaia di appassionati di fumetti</p>
                </div>
            </div>

            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item p-4">
                        <div class="stat-icon text-orange mb-3">
                            <i class="fas fa-book fa-3x"></i>
                        </div>
                        <h3 class="stat-number display-4 fw-bold text-white">{{ number_format($stats['total_fumetti']) }}</h3>
                        <p class="stat-label text-light mb-0 fs-5">Fumetti Pubblicati</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item p-4">
                        <div class="stat-icon text-orange mb-3">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <h3 class="stat-number display-4 fw-bold text-white">{{ number_format($stats['total_artists']) }}</h3>
                        <p class="stat-label text-light mb-0 fs-5">Artisti Registrati</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item p-4">
                        <div class="stat-icon text-orange mb-3">
                            <i class="fas fa-star fa-3x"></i>
                        </div>
                        <h3 class="stat-number display-4 fw-bold text-white">{{ number_format($stats['total_reviews']) }}</h3>
                        <p class="stat-label text-light mb-0 fs-5">Recensioni Scritte</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item p-4">
                        <div class="stat-icon text-orange mb-3">
                            <i class="fas fa-tags fa-3x"></i>
                        </div>
                        <h3 class="stat-number display-4 fw-bold text-white">{{ number_format($stats['total_categories']) }}</h3>
                        <p class="stat-label text-light mb-0 fs-5">Categorie Disponibili</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="cta-content section-card p-5">
                    <h2 class="display-4 fw-bold text-white mb-4">
                        Pronto a <span class="text-orange">iniziare</span>?
                    </h2>
                    <p class="lead text-light mb-4">
                        Unisciti alla nostra community di fumettisti e appassionati.
                        Condividi le tue storie e scopri nuovi mondi da esplorare.
                    </p>
                    <div class="cta-buttons d-flex flex-wrap justify-content-center gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg hover-lift">
                                <i class="fas fa-user-plus me-2"></i>Registrati Gratis
                            </a>
                            <a href="{{ route('fumetti.index') }}" class="btn btn-outline-primary btn-lg hover-lift">
                                <i class="fas fa-eye me-2"></i>Esplora Fumetti
                            </a>
                        @else
                            <a href="{{ route('fumetti.create') }}" class="btn btn-primary btn-lg hover-lift">
                                <i class="fas fa-plus me-2"></i>Pubblica il tuo Fumetto
                            </a>
                            <a href="{{ route('fumetti.dashboard') }}" class="btn btn-outline-primary btn-lg hover-lift">
                                <i class="fas fa-tachometer-alt me-2"></i>Vai alla Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonianze -->
<section class="testimonials-section py-5 mt-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold text-white mb-3">
                    Cosa dicono gli <span class="text-orange">artisti</span>
                </h2>
                <p class="lead text-light">Le opinioni della nostra community</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-quote-left fa-2x text-orange"></i>
                        </div>
                        <p class="card-text mb-4">
                            "Fumettisti Portal mi ha permesso di condividere le mie opere con una community fantastica. I feedback ricevuti mi hanno aiutato a crescere come artista."
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-3x text-orange"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Marco R.</h6>
                                <small class="text-muted">Fumettista</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-quote-left fa-2x text-orange"></i>
                        </div>
                        <p class="card-text mb-4">
                            "La piattaforma è intuitiva e ben progettata. Ho scoperto tantissimi talenti emergenti e nuove storie incredibili."
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-3x text-orange"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Laura B.</h6>
                                <small class="text-muted">Lettrice appassionata</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-quote-left fa-2x text-orange"></i>
                        </div>
                        <p class="card-text mb-4">
                            "Finalmente un posto dove i fumettisti italiani possono incontrarsi e crescere insieme. Consiglio a tutti di provarlo!"
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-3x text-orange"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Giuseppe V.</h6>
                                <small class="text-muted">Artista digitale</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-css')
<style>
    /* Hero specific styles */
    .min-vh-75 {
        min-height: 75vh;
    }

    /* Animazioni personalizzate */
    .fade-in {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .fade-in:nth-child(1) { animation-delay: 0s; }
    .fade-in:nth-child(2) { animation-delay: 0.2s; }
    .fade-in:nth-child(3) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats hover effect */
    .stat-item {
        transition: var(--transition);
        border-radius: var(--border-radius);
    }

    .stat-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 107, 53, 0.1);
    }

    .stat-number {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* Testimonianze */
    .avatar {
        opacity: 0.8;
        transition: var(--transition);
    }

    .card:hover .avatar {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-buttons,
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .display-3 {
            font-size: 2.5rem;
        }

        .display-4 {
            font-size: 2rem;
        }

        .display-5 {
            font-size: 1.8rem;
        }
    }
</style>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Parallax effect per l'hero
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.hero-illustration');
        if (parallax) {
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });

    // Intersection Observer per animazioni
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Osserva tutti gli elementi che devono animarsi
    document.querySelectorAll('.card, .stat-item').forEach(card => {
        observer.observe(card);
    });

    // Smooth scroll per i link interni
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
