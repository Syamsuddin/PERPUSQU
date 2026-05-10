<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->library_name ?? 'GIBTHA LIBRARY' }} — Sistem Informasi Perpustakaan</title>
    <meta name="description" content="{{ $profile->about_text ?? 'Perpustakaan digital terpadu untuk mendukung pendidikan dan riset islami.' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark:    #1b5e35;
            --green-main:    #2e7d52;
            --green-mid:     #3a9d68;
            --green-light:   #5bbf88;
            --green-pale:    #e6f5ec;
            --green-mist:    #f2fbf6;
            --gold:          #c8972a;
            --gold-light:    #f0d080;
            --white:         #ffffff;
            --text-dark:     #1a3328;
            --text-body:     #3a5246;
            --text-muted:    #7aaa8e;
            --border:        #c8e6d5;
        }

        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--green-mist);
            color: var(--text-body);
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }

        /* ── Islamic Geometric SVG Pattern ─────────────────── */
        .geo-pattern-bg {
            background-color: var(--green-mist);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cg fill='none' stroke='%232e7d52' stroke-width='0.4' stroke-opacity='0.18'%3E%3Cpolygon points='40,2 75,22 75,58 40,78 5,58 5,22'/%3E%3Cpolygon points='40,14 63,27 63,53 40,66 17,53 17,27'/%3E%3Cline x1='40' y1='2' x2='40' y2='14'/%3E%3Cline x1='75' y1='22' x2='63' y2='27'/%3E%3Cline x1='75' y1='58' x2='63' y2='53'/%3E%3Cline x1='40' y1='78' x2='40' y2='66'/%3E%3Cline x1='5' y1='58' x2='17' y2='53'/%3E%3Cline x1='5' y1='22' x2='17' y2='27'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* ── Ornamental divider ──────────────────────────── */
        .ornament {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
            margin: 0.5rem 0 1.5rem;
            color: var(--gold);
            font-size: 1rem;
        }
        .ornament::before,
        .ornament::after {
            content: '';
            flex: 1;
            max-width: 120px;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold));
        }
        .ornament::after { background: linear-gradient(to left, transparent, var(--gold)); }

        /* ── Navigation ─────────────────────────────────── */
        .nav-bar {
            position: sticky; top: 0; z-index: 100;
            padding: 1rem 5%;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(46,125,82,0.08);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 0.75rem;
            font-weight: 800; font-size: 1.3rem; color: var(--green-dark);
        }
        .nav-brand img { height: 38px; width: auto; }
        .nav-brand .brand-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, var(--green-main), var(--green-light));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.3rem;
        }
        .nav-actions { display: flex; gap: 0.75rem; align-items: center; }
        .btn-nav-opac {
            padding: 0.55rem 1.3rem;
            background: var(--green-pale);
            color: var(--green-dark);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 600; font-size: 0.875rem;
            transition: all 0.25s;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-nav-opac:hover { background: var(--green-pale); border-color: var(--green-mid); color: var(--green-dark); }
        .btn-nav-login {
            padding: 0.55rem 1.3rem;
            background: linear-gradient(135deg, var(--green-main), var(--green-mid));
            color: #fff;
            border-radius: 10px;
            font-weight: 600; font-size: 0.875rem;
            transition: all 0.25s;
            display: inline-flex; align-items: center; gap: 0.4rem;
            box-shadow: 0 3px 10px rgba(46,125,82,0.3);
        }
        .btn-nav-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(46,125,82,0.35);
        }

        /* ── Hero ────────────────────────────────────────── */
        .hero {
            padding: 5rem 5% 4rem;
            text-align: center;
            position: relative;
        }
        .hero-inner {
            max-width: 820px;
            margin: 0 auto;
            animation: fadeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .bismillah {
            font-family: 'Amiri', serif;
            font-size: 2rem;
            color: var(--green-dark);
            letter-spacing: 2px;
            margin-bottom: 0.25rem;
            opacity: 0.85;
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.45rem 1.1rem;
            background: var(--green-pale);
            border: 1px solid var(--border);
            border-radius: 100px;
            color: var(--green-main);
            font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
            margin-bottom: 1.75rem;
        }
        .hero-eyebrow .dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--green-light);
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

        .hero h1 {
            font-size: clamp(2.2rem, 5.5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--text-dark);
            letter-spacing: -1.5px;
            margin-bottom: 1.25rem;
        }
        .hero h1 .highlight {
            color: var(--green-main);
            position: relative;
        }
        .hero h1 .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 0; right: 0;
            height: 6px;
            background: var(--gold-light);
            opacity: 0.5;
            border-radius: 3px;
            z-index: -1;
        }
        .hero p {
            font-size: 1.1rem;
            color: var(--text-body);
            max-width: 640px;
            margin: 0 auto 2.5rem;
            line-height: 1.75;
        }
        .hero-cta {
            display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;
        }
        .btn-primary-cta {
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--green-main), var(--green-mid));
            color: #fff;
            border-radius: 14px;
            font-weight: 700; font-size: 1rem;
            box-shadow: 0 6px 20px rgba(46,125,82,0.35);
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 0.6rem;
        }
        .btn-primary-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(46,125,82,0.4);
            color: #fff;
        }

        /* ── Stats Bar ───────────────────────────────────── */
        .stats-band {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-main) 100%);
            padding: 2.5rem 5%;
            display: flex;
            justify-content: center;
            gap: 0;
        }
        .stat-cell {
            flex: 1; max-width: 200px;
            text-align: center;
            padding: 0 1.5rem;
            position: relative;
        }
        .stat-cell + .stat-cell::before {
            content: '';
            position: absolute; left: 0; top: 15%; bottom: 15%;
            width: 1px; background: rgba(255,255,255,0.2);
        }
        .stat-cell .s-num {
            font-size: 2.4rem; font-weight: 800; color: #fff;
            line-height: 1; display: block;
        }
        .stat-cell .s-label {
            font-size: 0.75rem; font-weight: 600;
            color: rgba(255,255,255,0.65);
            text-transform: uppercase; letter-spacing: 1.5px;
            margin-top: 0.35rem;
        }

        /* ── Features ────────────────────────────────────── */
        .features-section {
            padding: 5rem 5%;
            background: #fff;
        }
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-header h2 {
            font-size: 2rem; font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .section-header p {
            color: var(--text-muted);
            font-size: 1rem;
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }
        .feature-card {
            background: var(--green-mist);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(46,125,82,0.12);
            border-color: var(--green-light);
            background: #fff;
        }
        .feature-icon {
            width: 58px; height: 58px;
            background: linear-gradient(135deg, var(--green-pale), #c8ead8);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem; color: var(--green-main);
            margin-bottom: 1.25rem;
        }
        .feature-card h3 {
            font-size: 1.15rem; font-weight: 700;
            color: var(--text-dark); margin-bottom: 0.65rem;
        }
        .feature-card p {
            font-size: 0.9rem; color: var(--text-body); line-height: 1.65;
        }

        /* ── Islamic Quote Band ──────────────────────────── */
        .quote-band {
            background: var(--green-pale);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 3rem 5%;
            text-align: center;
        }
        .quote-arabic {
            font-family: 'Amiri', serif;
            font-size: 1.8rem;
            color: var(--green-dark);
            margin-bottom: 0.5rem;
            line-height: 1.8;
        }
        .quote-trans {
            font-size: 0.92rem;
            color: var(--text-body);
            font-style: italic;
        }
        .quote-ref {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
            font-weight: 600;
        }

        /* ── Footer ──────────────────────────────────────── */
        .site-footer {
            background: var(--green-dark);
            padding: 2.5rem 5%;
            text-align: center;
        }
        .footer-brand-name {
            font-size: 1.1rem; font-weight: 800;
            color: #fff; margin-bottom: 0.4rem;
        }
        .site-footer p {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.8;
        }
        .site-footer a { color: var(--gold-light); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 640px) {
            .hero { padding: 3rem 5% 3rem; }
            .stats-band { flex-direction: column; gap: 1.5rem; }
            .stat-cell + .stat-cell::before { display: none; }
            .btn-primary-cta { width: 100%; justify-content: center; }
            .nav-actions .btn-nav-opac { display: none; }
            .bismillah { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ── Navigation ──────────────────────────────────────────────── --}}
    <nav class="nav-bar">
        <a href="/" class="nav-brand">
            @if($profile && $profile->logo_path)
                <img src="{{ Storage::url($profile->logo_path) }}" alt="{{ $profile->library_name }}">
            @else
                <div class="brand-icon"><i class="bi bi-book-half"></i></div>
            @endif
            <span>{{ $profile->library_name ?? 'GIBTHA LIBRARY' }}</span>
        </a>
        <div class="nav-actions">
            <a href="{{ route('opac.home') }}" class="btn-nav-opac">
                <i class="bi bi-search"></i> Cari Koleksi
            </a>
            <a href="{{ route('auth.login') }}" class="btn-nav-login">
                <i class="bi bi-shield-lock"></i> Login Admin
            </a>
        </div>
    </nav>

    {{-- ── Hero ─────────────────────────────────────────────────────── --}}
    <section class="hero geo-pattern-bg">
        <div class="hero-inner">
            <div class="bismillah">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
            <div class="ornament"><i class="bi bi-stars"></i></div>

            <div class="hero-eyebrow">
                <span class="dot"></span>
                {{ $profile->institution_name ?? 'Sistem Perpustakaan Modern' }}
            </div>

            <h1>
                Menuntut Ilmu adalah<br>
                <span class="highlight">Ibadah yang Mulia</span>
            </h1>

            <p>
                Selamat datang di <strong>{{ $profile->library_name ?? 'GIBTHA LIBRARY' }}</strong> —
                temukan ribuan koleksi buku, jurnal ilmiah, dan repositori digital dalam satu platform
                yang dirancang untuk mendukung perjalanan belajar Anda.
            </p>

            <div class="hero-cta">
                <a href="{{ route('opac.home') }}" class="btn-primary-cta">
                    <i class="bi bi-search-heart"></i>
                    Mulai Penelusuran (OPAC)
                </a>
            </div>
        </div>
    </section>

    {{-- ── Stats Band ───────────────────────────────────────────────── --}}
    <div class="stats-band">
        <div class="stat-cell">
            <span class="s-num">{{ number_format($totalCollections) }}</span>
            <span class="s-label">Koleksi Cetak</span>
        </div>
        <div class="stat-cell">
            <span class="s-num">{{ number_format($totalDigital) }}</span>
            <span class="s-label">Aset Digital</span>
        </div>
        <div class="stat-cell">
            <span class="s-num">{{ number_format($activeLoans) }}</span>
            <span class="s-label">Dipinjam Aktif</span>
        </div>
    </div>

    {{-- ── Islamic Quote ─────────────────────────────────────────────── --}}
    <div class="quote-band">
        <div class="ornament"><i class="bi bi-moon-stars"></i></div>
        <div class="quote-arabic">اِقْرَأْ بِاسْمِ رَبِّكَ الَّذِيْ خَلَقَ</div>
        <div class="quote-trans">"Bacalah dengan (menyebut) nama Tuhanmu yang menciptakan."</div>
        <div class="quote-ref">QS. Al-'Alaq: 1</div>
    </div>

    {{-- ── Features ─────────────────────────────────────────────────── --}}
    <section class="features-section">
        <div class="section-header">
            <h2>Layanan Perpustakaan Kami</h2>
            <div class="ornament"><i class="bi bi-flower1"></i></div>
            <p>Dirancang untuk memudahkan akses ilmu bagi seluruh civitas akademika.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-journal-text"></i></div>
                <h3>Koleksi Cetak Lengkap</h3>
                <p>Ribuan judul buku teks, referensi, dan literatur dari berbagai disiplin ilmu yang terklasifikasi dengan standar DDC internasional.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-cloud-arrow-down"></i></div>
                <h3>E-Book & Repositori Digital</h3>
                <p>Akses dokumen digital, e-book, skripsi, dan karya ilmiah mahasiswa secara instan kapan saja dan di mana saja.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-arrow-left-right"></i></div>
                <h3>Sirkulasi Terotomasi</h3>
                <p>Peminjaman dan pengembalian cepat dengan sistem terintegrasi, notifikasi otomatis, dan perpanjangan mandiri.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-search-heart"></i></div>
                <h3>OPAC — Penelusuran Pintar</h3>
                <p>Temukan koleksi dengan cepat menggunakan fitur pencarian canggih berdasarkan judul, pengarang, subjek, atau klasifikasi DDC.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-bookmark-check"></i></div>
                <h3>Katalog Terstandar</h3>
                <p>Setiap koleksi terdata lengkap dengan metadata bibliografis, cover buku, dan informasi ketersediaan eksemplar secara real-time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
                <h3>Manajemen Keanggotaan</h3>
                <p>Sistem keanggotaan terintegrasi untuk mahasiswa, dosen, dan staf dengan riwayat peminjaman dan denda yang transparan.</p>
            </div>
        </div>
    </section>

    {{-- ── Footer ───────────────────────────────────────────────────── --}}
    <footer class="site-footer">
        <div class="footer-brand-name">{{ $profile->library_name ?? 'GIBTHA LIBRARY' }}</div>
        @if($profile && $profile->address)
        <p>{{ $profile->address }}</p>
        @endif
        <p style="margin-top:0.75rem;">
            &copy; {{ date('Y') }} — Dikembangkan oleh <a href="#"><strong>Syamsuddin</strong></a>
        </p>
    </footer>

</body>
</html>
