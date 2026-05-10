<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OPAC') — GIBTHA LIBRARY</title>
    <meta name="description" content="@yield('meta-description', 'Online Public Access Catalog — Perpustakaan GIBTHA LIBRARY')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark:  #1b5e35;
            --green-main:  #2e7d52;
            --green-mid:   #3a9d68;
            --green-light: #5bbf88;
            --green-pale:  #e6f5ec;
            --green-mist:  #f2fbf6;
            --gold:        #c8972a;
            --gold-light:  #f0d080;
            --text-dark:   #1a3328;
            --text-body:   #3a5246;
            --text-muted:  #7aaa8e;
            --border:      #c8e6d5;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-body);
            background: var(--green-mist);
        }

        /* ── Navbar ───────────────────────────────────────────── */
        .opac-navbar {
            background: var(--green-dark);
            padding: .7rem 0;
            box-shadow: 0 2px 12px rgba(27,94,53,.25);
        }
        .opac-navbar .brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            text-decoration: none;
            letter-spacing: .3px;
        }
        .opac-navbar .brand-text i { color: var(--gold-light); }
        .opac-navbar .nav-link {
            color: rgba(255,255,255,.8);
            font-size: .875rem;
            font-weight: 500;
            padding: .35rem .65rem;
            border-radius: 6px;
            transition: .15s;
        }
        .opac-navbar .nav-link:hover { color: #fff; background: rgba(255,255,255,.1); }
        .opac-navbar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.15);
            font-weight: 600;
        }

        /* ── Hero ─────────────────────────────────────────────── */
        .opac-hero {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-main) 60%, var(--green-mid) 100%);
            padding: 3.5rem 0 3rem;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .opac-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='92'%3E%3Cpolygon points='40,4 76,24 76,68 40,88 4,68 4,24' fill='none' stroke='rgba(255,255,255,0.06)' stroke-width='1.2'/%3E%3Cpolygon points='40,18 62,30 62,62 40,74 18,62 18,30' fill='none' stroke='rgba(255,255,255,0.04)' stroke-width='1'/%3E%3C/svg%3E");
            background-size: 80px 92px;
            pointer-events: none;
        }
        .opac-hero .hero-inner { position: relative; z-index: 1; }
        .opac-hero h1 { font-weight: 800; font-size: 2.2rem; margin-bottom: .5rem; }
        .opac-hero p { font-size: 1.05rem; opacity: .88; }
        .opac-hero .search-box { max-width: 620px; margin: 1.5rem auto 0; }
        .opac-hero .search-box .form-control {
            border: none;
            border-radius: 50px 0 0 50px;
            padding: .75rem 1.5rem;
            font-size: 1rem;
            box-shadow: none;
        }
        .opac-hero .search-box .form-control:focus { outline: none; box-shadow: none; }
        .opac-hero .search-box .btn-search {
            border-radius: 0 50px 50px 0;
            padding: .75rem 1.6rem;
            background: var(--gold);
            border: none;
            font-weight: 700;
            color: #fff;
            transition: .2s;
        }
        .opac-hero .search-box .btn-search:hover { background: #a67a1e; color: #fff; }

        /* ── Pill links ───────────────────────────────────────── */
        .pill-link {
            display: inline-block;
            padding: .35rem .9rem;
            border-radius: 50px;
            background: rgba(255,255,255,.15);
            color: #fff;
            font-weight: 500;
            font-size: .82rem;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.25);
            transition: .2s;
        }
        .pill-link:hover { background: rgba(255,255,255,.28); color: #fff; }

        /* ── Content area ─────────────────────────────────────── */
        .opac-content { padding: 2rem 0 3.5rem; min-height: 60vh; }

        /* ── Cards ────────────────────────────────────────────── */
        .record-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            transition: all .2s ease;
        }
        .record-card:hover {
            box-shadow: 0 6px 20px rgba(46,125,82,.12);
            transform: translateY(-2px);
            border-color: var(--green-light);
        }
        .record-card .card-title { font-weight: 700; font-size: .95rem; color: var(--green-dark); }
        .record-card .card-title a { color: inherit; text-decoration: none; }
        .record-card .card-title a:hover { color: var(--green-mid); }

        /* ── Stat cards ───────────────────────────────────────── */
        .stat-card-opac {
            border: none;
            border-radius: 14px;
            background: #fff;
            border-left: 4px solid var(--green-mid);
            box-shadow: 0 2px 10px rgba(46,125,82,.08);
            transition: .2s;
        }
        .stat-card-opac:hover { box-shadow: 0 6px 18px rgba(46,125,82,.14); }
        .stat-card-opac .stat-num { font-weight: 800; font-size: 2rem; color: var(--green-main); }
        .stat-card-opac .stat-label { font-size: .82rem; color: var(--text-muted); font-weight: 500; }

        /* ── Filter sidebar ───────────────────────────────────── */
        .filter-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
        }
        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: var(--green-mid);
            box-shadow: 0 0 0 .2rem rgba(58,157,104,.2);
        }
        .btn-filter-primary {
            background: linear-gradient(135deg, var(--green-main), var(--green-mid));
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-filter-primary:hover { background: var(--green-dark); color: #fff; }

        /* ── Availability badges ──────────────────────────────── */
        .badge-available   { background: #d1fae5; color: #065f46; }
        .badge-unavailable { background: #fee2e2; color: #991b1b; }

        /* ── Section headings ─────────────────────────────────── */
        .section-heading {
            font-weight: 800;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .section-heading i { color: var(--green-mid); }

        /* ── Content card (about, help) ───────────────────────── */
        .content-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 2px 16px rgba(46,125,82,.06);
        }
        .content-card h2 { color: var(--green-dark); font-weight: 800; }
        .content-card h5 {
            color: var(--green-main);
            font-weight: 700;
            border-left: 3px solid var(--gold);
            padding-left: .75rem;
        }

        /* ── Footer ───────────────────────────────────────────── */
        .opac-footer {
            background: var(--green-dark);
            color: rgba(255,255,255,.75);
            padding: 2rem 0;
            font-size: .85rem;
        }
        .opac-footer a { color: rgba(255,255,255,.85); text-decoration: none; }
        .opac-footer a:hover { color: #fff; }
        .opac-footer .footer-divider {
            border-color: rgba(255,255,255,.15);
            margin: .75rem 0;
        }

        /* ── Table tweaks ─────────────────────────────────────── */
        .table thead th { color: var(--green-dark); font-weight: 700; border-bottom: 2px solid var(--border); }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="opac-navbar">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('opac.home') }}" class="brand-text">
                <i class="bi bi-book-half me-2"></i>GIBTHA LIBRARY OPAC
            </a>
            <div class="d-flex gap-1 align-items-center flex-wrap">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="bi bi-house-door me-1"></i>Beranda
                </a>
                <a href="{{ route('opac.search') }}" class="nav-link {{ request()->routeIs('opac.search') ? 'active' : '' }}">
                    <i class="bi bi-search me-1"></i>Pencarian
                </a>
                <a href="{{ route('opac.about') }}" class="nav-link {{ request()->routeIs('opac.about') ? 'active' : '' }}">
                    <i class="bi bi-info-circle me-1"></i>Tentang
                </a>
                <a href="{{ route('opac.help') }}" class="nav-link {{ request()->routeIs('opac.help') ? 'active' : '' }}">
                    <i class="bi bi-question-circle me-1"></i>Bantuan
                </a>
            </div>
        </div>
    </nav>

    @yield('hero')

    <div class="opac-content">
        <div class="container">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="opac-footer">
        <div class="container text-center">
            <p class="mb-1">
                <i class="bi bi-book-half me-1" style="color:var(--gold-light)"></i>
                <strong style="color:#fff">GIBTHA LIBRARY</strong> — Online Public Access Catalog
            </p>
            <hr class="footer-divider">
            <p class="mb-0">&copy; {{ date('Y') }} Perpustakaan GIBTHA LIBRARY. Hak cipta dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
