<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Fumettisti Portal')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tema Nero-Arancione Custom -->
    <style>
        /* ===== VARIABILI COLORI NERO-ARANCIONE ===== */
        :root {
            /* Colori Principali */
            --primary-black: #000000;
            --primary-orange: #ff6b35;
            --primary-orange-light: #ff8c42;
            --primary-orange-dark: #e85a2b;

            /* Gradient Background */
            --bg-gradient: linear-gradient(135deg, #000000 0%, #1a1a1a 25%, #ff6b35 75%, #ff8c42 100%);
            --bg-gradient-alt: linear-gradient(45deg, #000000 0%, #2d1810 30%, #ff6b35 70%, #ffa726 100%);
            --bg-gradient-subtle: linear-gradient(180deg, #0a0a0a 0%, #1f1f1f 50%, #2a1810 100%);

            /* Colori Neutri */
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --medium-gray: #6c757d;
            --dark-gray: #343a40;

            /* Card Colors */
            --card-bg: rgba(255, 255, 255, 0.95);
            --card-border: rgba(255, 107, 53, 0.2);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.3), 0 5px 15px rgba(255, 107, 53, 0.2);
            --card-shadow-hover: 0 25px 50px rgba(0, 0, 0, 0.4), 0 10px 25px rgba(255, 107, 53, 0.3);

            /* Text Colors */
            --text-primary: #212529;
            --text-secondary: #495057;
            --text-muted: #6c757d;
            --text-light: rgba(255, 255, 255, 0.9);
            --text-orange: #ff6b35;

            /* Button Colors */
            --btn-primary-bg: #ffffff;
            --btn-primary-text: #000000;
            --btn-primary-border: #ffffff;
            --btn-primary-hover-bg: #f8f9fa;
            --btn-primary-hover-text: #000000;

            --btn-secondary-bg: rgba(255, 255, 255, 0.1);
            --btn-secondary-text: #ffffff;
            --btn-secondary-border: rgba(255, 255, 255, 0.3);
            --btn-secondary-hover-bg: rgba(255, 255, 255, 0.2);

            /* Ombre e Bordi */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 8px 16px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.2);
            --shadow-xl: 0 25px 50px rgba(0, 0, 0, 0.25);

            /* Bordi */
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 20px;

            /* Transizioni */
            --transition: 0.3s ease-in-out;
            --transition-fast: 0.15s ease;
        }

        /* ===== RESET E BASE ===== */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-gradient);
            background-attachment: fixed;
            color: var(--text-light);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Pattern overlay per sfondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 140, 66, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 0, 0, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(0, 0, 0, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 107, 53, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            color: var(--primary-orange) !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            transition: var(--transition);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-orange) !important;
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link.active {
            color: var(--primary-orange) !important;
            position: relative;
        }

        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: var(--primary-orange);
            border-radius: 2px;
        }

        /* ===== CARDS IN RILIEVO ===== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-orange), var(--primary-orange-light));
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
        }

        .card-header {
            background: linear-gradient(135deg, var(--light-gray), #ffffff);
            border-bottom: 1px solid var(--card-border);
            color: var(--text-primary);
            font-weight: 600;
        }

        .card-body {
            color: var(--text-primary);
        }

        /* ===== BUTTON BIANCHI CON TESTO NERO ===== */
        .btn {
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: var(--btn-primary-bg);
            border: 2px solid var(--btn-primary-border);
            color: var(--btn-primary-text);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background: var(--btn-primary-hover-bg);
            border-color: var(--btn-primary-border);
            color: var(--btn-primary-hover-text);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary {
            background: var(--btn-secondary-bg);
            border: 2px solid var(--btn-secondary-border);
            color: var(--btn-secondary-text);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: var(--btn-secondary-hover-bg);
            border-color: var(--white);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--white);
            color: var(--white);
        }

        .btn-outline-primary:hover {
            background: var(--white);
            border-color: var(--white);
            color: var(--primary-black);
            transform: translateY(-2px);
        }

        .btn-success {
            background: #10b981;
            border-color: #10b981;
            color: var(--white);
        }

        .btn-danger {
            background: #ef4444;
            border-color: #ef4444;
            color: var(--white);
        }

        .btn-warning {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: var(--white);
        }

        .btn-info {
            background: #3b82f6;
            border-color: #3b82f6;
            color: var(--white);
        }

        /* ===== FORM CONTROLS ===== */
        .form-control {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: var(--border-radius);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .form-control:focus {
            background: var(--white);
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            color: var(--text-primary);
        }

        .form-label {
            color: var(--text-light);
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-md);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.9);
            color: var(--white);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.9);
            color: var(--white);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.9);
            color: var(--white);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.9);
            color: var(--white);
        }

        /* ===== BADGES ===== */
        .badge {
            border-radius: 8px;
            font-weight: 600;
        }

        .badge.bg-primary {
            background: var(--primary-orange) !important;
        }

        /* ===== LINKS ===== */
        a {
            color: var(--primary-orange);
            text-decoration: none;
            transition: var(--transition);
        }

        a:hover {
            color: var(--primary-orange-light);
            text-decoration: underline;
        }

        /* ===== UTILITIES CUSTOM ===== */
        .text-orange {
            color: var(--primary-orange) !important;
        }

        .bg-card {
            background: var(--card-bg) !important;
        }

        .bg-gradient-dark {
            background: var(--bg-gradient-subtle) !important;
        }

        .shadow-custom {
            box-shadow: var(--card-shadow);
        }

        .shadow-custom-lg {
            box-shadow: var(--card-shadow-hover);
        }

        /* ===== ANIMAZIONI ===== */
        .fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

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

        .hover-lift {
            transition: var(--transition);
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* ===== SECTION STYLING ===== */
        .section-dark {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
        }

        .section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 107, 53, 0.2);
            border-radius: var(--border-radius-lg);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 107, 53, 0.3);
            color: var(--text-light);
            margin-top: auto;
        }

        .footer a {
            color: var(--primary-orange);
            transition: var(--transition);
        }

        .footer a:hover {
            color: var(--primary-orange-light);
            transform: translateY(-1px);
        }

        .footer h5, .footer h6 {
            color: var(--white);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .card {
                margin-bottom: 1rem;
            }

            body {
                background-attachment: scroll;
            }
        }

        /* ===== SCROLLBAR CUSTOM ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-orange);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-orange-dark);
        }

        /* ===== DROPDOWN MENU ===== */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 107, 53, 0.2);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        .dropdown-item {
            color: var(--text-primary);
            transition: var(--transition-fast);
        }

        .dropdown-item:hover {
            background: rgba(255, 107, 53, 0.1);
            color: var(--text-primary);
        }
    </style>

    @yield('extra-css')
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>
                Fumettisti Portal
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: var(--primary-orange);">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fumetti.*') ? 'active' : '' }}" href="{{ route('fumetti.index') }}">
                            <i class="fas fa-book me-1"></i>Fumetti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.create') }}">
                            <i class="fas fa-envelope me-1"></i>Contatti
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Accedi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Registrati
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i>Profilo
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('fumetti.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('fumetti.create') }}">
                                    <i class="fas fa-plus me-2"></i>Nuovo Fumetto
                                </a></li>
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
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-book-open me-2 text-orange"></i>
                        Fumettisti Portal
                    </h5>
                    <p class="mb-3">La community italiana dedicata ai fumetti, dove artisti e appassionati si incontrano per condividere storie straordinarie.</p>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Navigazione</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('fumetti.index') }}">Fumetti</a></li>
                        <li class="mb-2"><a href="{{ route('contact.create') }}">Contatti</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Community</h6>
                    <ul class="list-unstyled">
                        @guest
                            <li class="mb-2"><a href="{{ route('register') }}">Registrati</a></li>
                            <li class="mb-2"><a href="{{ route('login') }}">Accedi</a></li>
                        @else
                            <li class="mb-2"><a href="{{ route('profile.show') }}">Il tuo Profilo</a></li>
                            <li class="mb-2"><a href="{{ route('fumetti.dashboard') }}">Dashboard</a></li>
                        @endguest
                    </ul>
                </div>

                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Resta connesso</h6>
                    <p class="mb-3">Seguici per non perdere le ultime novità dalla community!</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-decoration-none hover-lift">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="#" class="text-decoration-none hover-lift">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-decoration-none hover-lift">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255, 107, 53, 0.3);">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Fumettisti Portal. Tutti i diritti riservati.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small>Fatto con <span class="text-orange">❤️</span> per la community dei fumetti</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('extra-js')
</body>
</html>
