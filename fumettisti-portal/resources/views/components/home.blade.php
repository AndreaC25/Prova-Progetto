@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Hero Section -->
            <div class="text-center py-5">
                <h1 class="display-4 mb-4">🎨 Fumettisti Portal</h1>
                <p class="lead mb-4">Il portale dedicato ai fumettisti indipendenti italiani</p>

                @guest
                    <div class="mb-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-user-plus me-2"></i>Registrati
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Accedi
                        </a>
                    </div>
                @else
                    <div class="alert alert-success">
                        <h4>Benvenuto, {{ Auth::user()->name }}! 👋</h4>
                        <p class="mb-3">Il tuo Fumettisti Portal è pronto!</p>
                        <a href="{{ route('fumetti.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-plus me-2"></i>Crea Fumetto
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-success">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Features Section -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-book fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">📚 Pubblica Fumetti</h5>
                            <p class="card-text">Condividi le tue opere con una community appassionata di fumetti indipendenti.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-star fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">⭐ Recensioni</h5>
                            <p class="card-text">Ricevi feedback dai lettori e lascia recensioni sui fumetti che ami.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">❤️ Preferiti</h5>
                            <p class="card-text">Salva i fumetti che ti piacciono di più nella tua collezione personale.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">📊 Statistiche Community</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="text-primary">0</h3>
                                    <p class="text-muted">Fumetti Pubblicati</p>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="text-success">{{ $stats['total_artists'] }}</h3>
                                    <p class="text-muted">Fumettisti Registrati</p>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="text-warning">0</h3>
                                    <p class="text-muted">Recensioni</p>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="text-info">0</h3>
                                    <p class="text-muted">Categorie</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            @guest
            <div class="text-center mt-5 py-4">
                <h3>Pronto a far parte della community?</h3>
                <p class="text-muted mb-4">Unisciti a centinaia di fumettisti che hanno scelto il nostro portale</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket me-2"></i>Inizia Ora Gratis
                </a>
            </div>
            @endguest
        </div>
    </div>
</div>
@endsection
