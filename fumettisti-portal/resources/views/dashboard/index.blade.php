 @extends('layouts.app')

@section('title', 'Dashboard - Fumettisti Portal')

@section('content')
<div class="container">
    <!-- Header Dashboard -->
    <div class="page-header">
        <h1 class="page-title">Benvenuto, {{ $user->name }}!</h1>
        <p class="page-subtitle">Gestisci i tuoi fumetti e il tuo profilo</p>
    </div>

    <!-- Statistiche -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                    <h3 class="display-6 fw-bold text-primary">{{ $myFumetti }}</h3>
                    <p class="text-muted mb-0">Fumetti Totali</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="fas fa-eye fa-3x text-success mb-3"></i>
                    <h3 class="display-6 fw-bold text-success">{{ $publishedFumetti }}</h3>
                    <p class="text-muted mb-0">Pubblicati</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="fas fa-edit fa-3x text-warning mb-3"></i>
                    <h3 class="display-6 fw-bold text-warning">{{ $draftFumetti }}</h3>
                    <p class="text-muted mb-0">Bozze</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                    <h3 class="display-6 fw-bold text-danger">0</h3>
                    <p class="text-muted mb-0">Like Ricevuti</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Azioni Rapide -->
    <div class="content-container mb-5">
        <h3 class="mb-4">Azioni Rapide</h3>
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('fumetti.create') }}" class="btn-primary text-decoration-none d-block p-4 text-center rounded-3">
                    <i class="fas fa-plus fa-2x mb-3"></i>
                    <h5 class="mb-2">Nuovo Fumetto</h5>
                    <p class="mb-0 small">Pubblica un nuovo fumetto</p>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('profile.edit') }}" class="btn-outline text-decoration-none d-block p-4 text-center rounded-3">
                    <i class="fas fa-user-edit fa-2x mb-3"></i>
                    <h5 class="mb-2">Modifica Profilo</h5>
                    <p class="mb-0 small">Aggiorna le tue informazioni</p>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('fumetti.index') }}" class="btn-outline text-decoration-none d-block p-4 text-center rounded-3">
                    <i class="fas fa-search fa-2x mb-3"></i>
                    <h5 class="mb-2">Esplora Fumetti</h5>
                    <p class="mb-0 small">Scopri nuovi fumetti</p>
                </a>
            </div>
        </div>
    </div>

    <!-- I Miei Fumetti -->
    @if($latestFumetti->count() > 0)
    <div class="content-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>I Miei Ultimi Fumetti</h3>
            <a href="{{ route('fumetti.my') }}" class="btn-outline">Vedi Tutti</a>
        </div>

        <div class="product-grid">
            @foreach($latestFumetti as $fumetto)
                <div class="product-card">
                    <div class="product-image">
                        @if($fumetto->cover_image)
                            <img src="{{ $fumetto->cover_image }}" alt="{{ $fumetto->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-book-open"></i>
                        @endif
                        @if(!$fumetto->is_published)
                            <span class="product-badge" style="background: #ffc107;">BOZZA</span>
                        @endif
                    </div>

                    <div class="product-info">
                        <h3 class="product-title">{{ $fumetto->title }}</h3>
                        <p class="product-description">{{ strlen($fumetto->plot) > 50 ? substr($fumetto->plot, 0, 50) . '...' : $fumetto->plot }}</p>

                        <div class="product-meta">
                            <span><i class="fas fa-calendar-alt me-1"></i>{{ $fumetto->publication_year }}</span>
                            <span><i class="fas fa-hashtag me-1"></i>{{ $fumetto->issue_number }}</span>
                        </div>
                    </div>

                    <div class="product-footer">
                        <div class="product-details">
                            <small class="text-muted">
                                @if($fumetto->is_published)
                                    <i class="fas fa-eye text-success me-1"></i>Pubblicato
                                @else
                                    <i class="fas fa-edit text-warning me-1"></i>Bozza
                                @endif
                            </small>
                        </div>
                        <a href="{{ route('fumetti.edit', $fumetto) }}" class="btn-primary">MODIFICA</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="content-container text-center py-5">
        <i class="fas fa-book fa-4x text-muted mb-4"></i>
        <h4>Nessun fumetto ancora</h4>
        <p class="text-muted mb-4">Inizia a condividere le tue storie con la community!</p>
        <a href="{{ route('fumetti.create') }}" class="btn-primary">
            <i class="fas fa-plus me-2"></i>Pubblica il tuo Primo Fumetto
        </a>
    </div>
    @endif
</div>
@endsection
