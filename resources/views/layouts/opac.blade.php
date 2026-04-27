<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OPAC') — PERPUSQU</title>
    <meta name="description" content="@yield('meta-description', 'Online Public Access Catalog — Perpustakaan PERPUSQU')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --opac-primary: #1a365d;
            --opac-accent: #2b6cb0;
            --opac-soft: #ebf4ff;
            --opac-warm: #f7fafc;
            --opac-hero-from: #1a365d;
            --opac-hero-to: #2c5282;
        }
        body { font-family: 'Inter', sans-serif; color: #2d3748; background: var(--opac-warm); }

        /* Navbar */
        .opac-navbar { background: var(--opac-primary); padding: .75rem 0; }
        .opac-navbar .brand-text { font-weight: 700; font-size: 1.2rem; color: #fff; text-decoration: none; }
        .opac-navbar .brand-text i { color: #63b3ed; }
        .opac-navbar .nav-link { color: rgba(255,255,255,.8); font-size: .9rem; font-weight: 500; }
        .opac-navbar .nav-link:hover, .opac-navbar .nav-link.active { color: #fff; }

        /* Hero */
        .opac-hero {
            background: linear-gradient(135deg, var(--opac-hero-from), var(--opac-hero-to));
            padding: 4rem 0 3rem;
            color: #fff;
            text-align: center;
        }
        .opac-hero h1 { font-weight: 800; font-size: 2.5rem; margin-bottom: .5rem; }
        .opac-hero p { font-size: 1.1rem; opacity: .85; }
        .opac-hero .search-box { max-width: 600px; margin: 1.5rem auto 0; }
        .opac-hero .search-box .form-control { border: none; border-radius: 50px 0 0 50px; padding: .75rem 1.5rem; font-size: 1rem; }
        .opac-hero .search-box .btn { border-radius: 0 50px 50px 0; padding: .75rem 1.5rem; background: #ed8936; border: none; font-weight: 600; }
        .opac-hero .search-box .btn:hover { background: #dd6b20; }

        /* Content */
        .opac-content { padding: 2rem 0 3rem; min-height: 60vh; }

        /* Cards */
        .record-card { border: 1px solid #e2e8f0; border-radius: 12px; transition: all .2s ease; background: #fff; }
        .record-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); transform: translateY(-2px); }
        .record-card .card-title { font-weight: 600; font-size: 1rem; color: var(--opac-primary); }
        .record-card .card-title a { color: inherit; text-decoration: none; }
        .record-card .card-title a:hover { color: var(--opac-accent); }

        /* Pill Links */
        .pill-link { display: inline-block; padding: .4rem 1rem; border-radius: 50px; background: var(--opac-soft); color: var(--opac-accent); font-weight: 500; font-size: .85rem; text-decoration: none; transition: .2s; }
        .pill-link:hover { background: var(--opac-accent); color: #fff; }

        /* Footer */
        .opac-footer { background: var(--opac-primary); color: rgba(255,255,255,.7); padding: 2rem 0; font-size: .85rem; }
        .opac-footer a { color: rgba(255,255,255,.8); text-decoration: none; }
        .opac-footer a:hover { color: #fff; }

        /* Badge */
        .badge-available { background: #c6f6d5; color: #22543d; }
        .badge-unavailable { background: #fed7d7; color: #742a2a; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="opac-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('opac.home') }}" class="brand-text"><i class="bi bi-book-half me-2"></i>PERPUSQU OPAC</a>
            <div class="d-flex gap-3">
                <a href="{{ route('opac.home') }}" class="nav-link {{ request()->routeIs('opac.home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('opac.search') }}" class="nav-link {{ request()->routeIs('opac.search') ? 'active' : '' }}">Pencarian</a>
                <a href="{{ route('opac.about') }}" class="nav-link {{ request()->routeIs('opac.about') ? 'active' : '' }}">Tentang</a>
                <a href="{{ route('opac.help') }}" class="nav-link {{ request()->routeIs('opac.help') ? 'active' : '' }}">Bantuan</a>
            </div>
        </div>
    </nav>

    @yield('hero')

    <div class="opac-content">
        <div class="container">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="opac-footer">
        <div class="container text-center">
            <p class="mb-1"><i class="bi bi-book-half me-1"></i><strong>PERPUSQU</strong> — Online Public Access Catalog</p>
            <p class="mb-0">&copy; {{ date('Y') }} Perpustakaan PERPUSQU. Hak cipta dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
