<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portale Fumettisti' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>
                Fumettisti Portal
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fumetti.*') ? 'active' : '' }}"
                           href="{{ route('fumetti.index') }}">Fumetti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}"
                           href="{{ route('contact.create') }}">Contatti</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i>Profilo
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('fumetti.create') }}">
                                    <i class="fas fa-plus me-2"></i>Nuovo Fumetto
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
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
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Registrati
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5><i class="fas fa-book-open me-2"></i>Fumettisti Portal</h5>
                    <p>La piattaforma dedicata ai fumettisti indipendenti italiani.</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5>Esplora</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('fumetti.index') }}" class="text-light text-decoration-none">
                            <i class="fas fa-book me-2"></i>Tutti i Fumetti
                        </a></li>
                        <li><a href="{{ route('home') }}#fumettisti" class="text-light text-decoration-none">
                            <i class="fas fa-users me-2"></i>Fumettisti
                        </a></li>
                        <li><a href="#" class="text-light text-decoration-none">
                            <i class="fas fa-star me-2"></i>Più Popolari
                        </a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Supporto</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('contact.create') }}" class="text-light text-decoration-none">
                            <i class="fas fa-envelope me-2"></i>Contattaci
                        </a></li>
                        <li><a href="#" class="text-light text-decoration-none">
                            <i class="fas fa-question-circle me-2"></i>FAQ
                        </a></li>
                        <li><a href="#" class="text-light text-decoration-none">
                            <i class="fas fa-shield-alt me-2"></i>Privacy Policy
                        </a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contatti</h5>
                    <p><i class="fas fa-envelope me-2"></i>info@fumettistiportal.it</p>
                    <p><i class="fas fa-phone me-2"></i>+39 123 456 7890</p>
                    @auth
                        @if(auth()->user()->email === 'mario.rossi@fumettisti.it')
                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-cogs me-2"></i>Admin Panel
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Fumettisti Portal. Tutti i diritti riservati.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
