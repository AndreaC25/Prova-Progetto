<x-layout title="Portale Fumettisti Indipendenti">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Il Portale dei <span class="text-warning">Fumettisti</span> Indipendenti
                    </h1>
                    <p class="lead mb-4">
                        Scopri, condividi e celebra il mondo del fumetto indipendente italiano.
                        Unisciti alla community dei creatori più talentuosi del panorama nazionale.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Unisciti Ora
                            </a>
                            <a href="{{ route('fumetti.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-book me-2"></i>
                                Esplora Fumetti
                            </a>
                        @else
                            <a href="{{ route('fumetti.create') }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Pubblica Fumetto
                            </a>
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-user me-2"></i>
                                Il Mio Profilo
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-illustration">
                        <i class="fas fa-book-open fa-10x text-warning opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistiche -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-book text-primary fa-3x mb-3"></i>
                        <h3 class="display-6 fw-bold text-primary">{{ $stats['total_fumetti'] }}</h3>
                        <p class="text-muted">Fumetti Pubblicati</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-users text-success fa-3x mb-3"></i>
                        <h3 class="display-6 fw-bold text-success">{{ $stats['total_fumettisti'] }}</h3>
                        <p class="text-muted">Fumettisti Attivi</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-user-friends text-info fa-3x mb-3"></i>
                        <h3 class="display-6 fw-bold text-info">{{ $stats['total_users'] }}</h3>
                        <p class="text-muted">Utenti Registrati</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ultimi Fumetti -->
    @if($latestFumetti->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="h3 fw-bold">
                    <i class="fas fa-star text-warning me-2"></i>
                    Ultimi Fumetti Pubblicati
                </h2>
                <a href="{{ route('fumetti.index') }}" class="btn btn-outline-primary">
                    Vedi Tutti <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row">
                @foreach($latestFumetti as $fumetto)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card manga-card h-100">
                            @if($fumetto->cover_image)
                                <img src="{{ asset('storage/' . $fumetto->cover_image) }}"
                                     class="card-img-top"
                                     alt="{{ $fumetto->title }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height: 250px;">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $fumetto->title }}</h5>
                                <p class="card-text text-muted small">
                                    <i class="fas fa-user me-1"></i>
                                    <a href="{{ route('profile.public', $fumetto->user->id) }}"
                                       class="text-decoration-none">
                                        {{ $fumetto->user->name }}
                                    </a>
                                </p>

                                <div class="mb-2">
                                    <span class="badge bg-secondary">N° {{ $fumetto->issue_number }}</span>
                                    <span class="badge bg-info">{{ $fumetto->publication_year }}</span>
                                </div>

                                @if($fumetto->categories && $fumetto->categories->count() > 0)
                                    <div class="mb-2">
                                        @foreach($fumetto->categories->take(2) as $category)
                                            <span class="badge bg-outline-primary">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($fumetto->plot)
                                    <p class="card-text">
                                        {{ Str::limit($fumetto->plot, 80) }}
                                    </p>
                                @endif
                            </div>

                            <div class="card-footer bg-transparent">
                                <a href="{{ route('fumetti.show', $fumetto) }}"
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-2"></i>Leggi Dettagli
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Fumetti in Evidenza -->
    @if($popularFumetti->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="h3 fw-bold text-center mb-5">
                <i class="fas fa-fire text-danger me-2"></i>
                Fumetti in Evidenza
            </h2>

            <div class="row">
                @foreach($popularFumetti as $fumetto)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card manga-card h-100">
                            @if($fumetto->cover_image)
                                <img src="{{ asset('storage/' . $fumetto->cover_image) }}"
                                     class="card-img-top"
                                     alt="{{ $fumetto->title }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                                     style="height: 200px; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                                    <i class="fas fa-book fa-2x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($fumetto->title, 20) }}</h6>
                                <p class="card-text small text-muted">
                                    di {{ $fumetto->user->name }}
                                </p>
                            </div>

                            <div class="card-footer bg-transparent p-2">
                                <a href="{{ route('fumetti.show', $fumetto) }}"
                                   class="btn btn-sm btn-outline-primary w-100">
                                    Visualizza
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Fumettisti in Evidenza -->
    @if($activeFumettisti->count() > 0)
    <section class="py-5" id="fumettisti">
        <div class="container">
            <h2 class="h3 fw-bold text-center mb-5">
                <i class="fas fa-palette text-success me-2"></i>
                Fumettisti della Community
            </h2>

            <div class="row">
                @foreach($activeFumettisti as $fumettista)
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                        <div class="card text-center border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <img src="{{ $fumettista->profile->profile_image_url }}"
                                     alt="{{ $fumettista->name }}"
                                     class="rounded-circle mb-2"
                                     style="width: 60px; height: 60px; object-fit: cover;">

                                <h6 class="card-title mb-1">{{ Str::limit($fumettista->name, 15) }}</h6>
                                <p class="card-text small text-muted">
                                    {{ $fumettista->fumetti_count }} fumett{{ $fumettista->fumetti_count == 1 ? 'o' : 'i' }}
                                </p>

                                <a href="{{ route('profile.public', $fumettista->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Profilo
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    @guest
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="h3 fw-bold mb-4">Pronto a Condividere la Tua Arte?</h2>
                    <p class="lead mb-4">
                        Unisciti alla community più vivace di fumettisti indipendenti in Italia.
                        Condividi le tue storie, connettiti con altri artisti e fai crescere la tua audience.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-rocket me-2"></i>
                            Inizia Subito
                        </a>
                        <a href="{{ route('fumetti.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Esplora Prima
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endguest

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="h3 fw-bold text-center mb-5">Perché Scegliere il Nostro Portale?</h2>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-upload fa-3x text-primary"></i>
                        </div>
                        <h5>Pubblicazione Semplice</h5>
                        <p class="text-muted">
                            Carica i tuoi fumetti in pochi click. Aggiungi copertine,
                            descrizioni e categorizza le tue opere facilmente.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-users fa-3x text-success"></i>
                        </div>
                        <h5>Community Attiva</h5>
                        <p class="text-muted">
                            Connettiti con altri fumettisti, condividi esperienze
                            e fai crescere la tua rete professionale.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-eye fa-3x text-info"></i>
                        </div>
                        <h5>Visibilità Garantita</h5>
                        <p class="text-muted">
                            Le tue opere saranno visibili a tutti gli utenti del portale,
                            aumentando la tua audience e le opportunità.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
