<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Fumettisti Portal')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('extra-css')
</head>
<body>
    <!-- Header Top Bar -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <small><i class="fas fa-book-open me-2"></i>Portale per Fumettisti Indipendenti</small>
                </div>
                <div class="col-md-6 text-end">
                    <small><i class="fas fa-envelope me-2"></i>info@fumettistiportal.it</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-book-open me-2"></i>
                    FUMETTISTI PORTAL
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                               href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('fumetti.*') ? 'active' : '' }}"
                               href="{{ route('fumetti.index') }}">
                                <i class="fas fa-book me-1"></i> Fumetti
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact.create') }}">
                                <i class="fas fa-envelope me-1"></i> Contatti
                            </a>
                        </li>

                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user-edit me-2"></i>Il Mio Profilo
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('fumetti.create') }}">
                                            <i class="fas fa-plus me-2"></i>Nuovo Fumetto
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('favorites.index') }}">
                                            <i class="fas fa-heart me-2"></i>I Miei Preferiti
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> Accedi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> Registrati
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="container">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Fumettisti Portal</h5>
                    <p class="mb-3">Il portale dedicato ai fumettisti indipendenti italiani.
                       Condividi le tue opere, scopri nuovi talenti e fai parte della community!</p>
                    <div class="footer-links">
                        <a href="{{ route('contact.create') }}" class="me-3">
                            <i class="fas fa-envelope me-1"></i>Contattaci
                        </a>
                        <a href="#" class="me-3">
                            <i class="fas fa-info-circle me-1"></i>Chi Siamo
                        </a>
                        <a href="#">
                            <i class="fas fa-shield-alt me-1"></i>Privacy
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Seguici sui Social</h6>
                    <div class="social-links mb-3">
                        <a href="#" class="text-light me-3" title="Instagram">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>
                        <a href="#" class="text-light me-3" title="Facebook">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        <a href="#" class="text-light" title="Twitter">
                            <i class="fab fa-twitter fa-2x"></i>
                        </a>
                    </div>
                    <p class="mb-0">&copy; {{ date('Y') }} Fumettisti Portal. Tutti i diritti riservati.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @yield('extra-js')
</body>
</html>
