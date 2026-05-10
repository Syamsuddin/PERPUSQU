<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->library_name ?? 'GIBTHA LIBRARY' }} — Sistem Informasi Perpustakaan Hibrid</title>
    <meta name="description" content="{{ $profile->about_text ?? 'GIBTHA LIBRARY adalah sistem informasi perpustakaan digital terpadu untuk kampus.' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #0f172a; --accent: #38b2ac; --accent-glow: #4fd1c5;
            --text: #e2e8f0; --text-muted: #94a3b8;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        body, html { 
            height: 100%; 
            font-family: 'Outfit', sans-serif; 
            background: var(--primary); 
            color: var(--text); 
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }

        /* Background Image & Overlay */
        .bg-image {
            position: fixed; inset: 0; z-index: 0;
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center;
        }
        .bg-overlay {
            position: fixed; inset: 0; z-index: 1;
            background: radial-gradient(circle at center, rgba(15,23,42,0.7) 0%, rgba(15,23,42,0.95) 100%);
            backdrop-filter: blur(8px);
        }

        .wrapper { 
            position: relative; 
            z-index: 2; 
            min-height: 100vh;
            display: flex; 
            flex-direction: column; 
        }

        /* Navigation */
        .nav-bar { 
            padding: 1.5rem 5%; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            background: rgba(15,23,42,0.4);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }
        .nav-brand { 
            display: flex; align-items: center; gap: 0.75rem; 
            font-weight: 800; font-size: 1.4rem; letter-spacing: 1px; 
        }
        .nav-brand img { height: 40px; width: auto; }
        .nav-brand i { color: var(--accent); font-size: 1.8rem; }
        .nav-brand span { 
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-login { 
            padding: 0.75rem 1.8rem; background: var(--accent); color: #fff; 
            border-radius: 12px; font-weight: 700; font-size: 0.95rem; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: none; cursor: pointer; 
            display: inline-flex; align-items: center; gap: 0.6rem; 
        }
        .btn-login:hover { 
            background: var(--accent-glow); transform: translateY(-2px) scale(1.02); 
            box-shadow: 0 10px 30px rgba(56,178,172,0.4); 
        }
        .btn-outline { 
            padding: 0.7rem 1.5rem; background: rgba(255,255,255,0.05); 
            color: #fff; border: 1px solid rgba(255,255,255,0.15); 
            border-radius: 12px; font-weight: 600; font-size: 0.875rem; 
            transition: all 0.3s; cursor: pointer; backdrop-filter: blur(10px); 
        }
        .btn-outline:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.3); }

        /* Main Hero Content */
        .main-hero { 
            padding: 8rem 5% 5rem; text-align: center; 
        }
        .hero-inner { 
            max-width: 900px; 
            margin: 0 auto;
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        
        .hero-badge { 
            display: inline-flex; align-items: center; gap: 0.6rem; 
            padding: 0.5rem 1.2rem; border-radius: 100px; 
            background: rgba(56,178,172,0.1); border: 1px solid rgba(56,178,172,0.2); 
            color: var(--accent-glow); font-size: 0.8rem; font-weight: 700; 
            margin-bottom: 2rem; backdrop-filter: blur(10px);
            text-transform: uppercase; letter-spacing: 2px;
        }
        .hero-badge .pulse { 
            width: 8px; height: 8px; border-radius: 50%; 
            background: var(--accent); animation: pulse 2s infinite; 
        }
        @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.5); } }
        
        .hero-inner h1 { 
            font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 900; 
            line-height: 1.1; margin-bottom: 1.5rem; color: #fff; 
            letter-spacing: -2px;
        }
        .hero-inner p { 
            font-size: 1.2rem; color: #94a3b8; max-width: 700px; 
            margin: 0 auto 3rem; line-height: 1.7; 
        }

        /* Feature Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 0 5% 5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.4s;
            text-align: left;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(56, 178, 172, 0.4);
        }
        .feature-icon {
            width: 60px; height: 60px;
            background: rgba(56, 178, 172, 0.15);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: var(--accent-glow);
            margin-bottom: 1.5rem;
        }
        .feature-card h3 { font-size: 1.4rem; font-weight: 700; margin-bottom: 1rem; color: #fff; }
        .feature-card p { font-size: 0.95rem; color: #94a3b8; line-height: 1.6; }

        /* Stats Bar */
        .stats-bar { 
            display: flex; justify-content: center; gap: 3rem; 
            padding: 3rem 5%; background: rgba(0,0,0,0.2);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }
        .stat-item { text-align: center; }
        .stat-val { font-size: 2.5rem; font-weight: 800; color: #fff; display: block; margin-bottom: 0.2rem; }
        .stat-lab { font-size: 0.75rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; }

        /* Footer */
        .footer { 
            padding: 3rem 5%; text-align: center; 
            margin-top: auto;
        }
        .footer-brand { margin-bottom: 1.5rem; font-weight: 800; font-size: 1.2rem; opacity: 0.8; }
        .footer p { font-size: 0.85rem; color: #64748b; }
        .footer a { color: var(--accent-glow); transition: color 0.3s; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-bar { flex-direction: column; gap: 2rem; padding: 3rem 1rem; }
            .hero-inner h1 { font-size: 2.8rem; }
            .nav-bar { padding: 1rem 5%; }
            .btn-login { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="wrapper">
        <nav class="nav-bar">
            <a href="/" class="nav-brand">
                @if($profile && $profile->logo_path)
                    <img src="{{ Storage::url($profile->logo_path) }}" alt="{{ $profile->library_name }}">
                @else
                    <i class="bi bi-book-half"></i>
                @endif
                <span>{{ $profile->library_name ?? 'GIBTHA LIBRARY' }}</span>
            </a>
            <div class="nav-actions">
                <a href="{{ route('auth.login') }}" class="btn-outline"><i class="bi bi-shield-lock me-1"></i> Admin</a>
            </div>
        </nav>

        <main class="main-hero">
            <div class="hero-inner">
                <div class="hero-badge"><span class="pulse"></span> {{ $profile->institution_name ?? 'Sistem Perpustakaan Modern' }}</div>
                
                <h1>Eksplorasi Ilmu Tanpa Batas</h1>
                <p>Akses ribuan koleksi buku fisik, jurnal ilmiah, dan <strong>e-book digital</strong> dalam satu platform terpadu yang dirancang untuk mendukung riset dan pendidikan Anda.</p>
                
                <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 5rem;">
                    <a href="{{ route('opac.home') }}" class="btn-login" style="padding: 1rem 2.5rem; font-size: 1.1rem;">
                        <i class="bi bi-search"></i> Mulai Penelusuran (OPAC)
                    </a>
                </div>
            </div>
        </main>

        <section class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-journal-text"></i></div>
                <h3>Koleksi Cetak Terlengkap</h3>
                <p>Ribuan judul buku teks, referensi, dan literatur fisik dari berbagai disiplin ilmu yang terorganisir dengan sistem klasifikasi standar internasional.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-cloud-arrow-down"></i></div>
                <h3>E-Book & Repositori</h3>
                <p>Nikmati kemudahan akses ke ribuan dokumen digital, e-book, dan karya ilmiah mahasiswa secara instan kapan saja dan di mana saja.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-qr-code"></i></div>
                <h3>Sirkulasi Digital</h3>
                <p>Layanan peminjaman dan pengembalian yang cepat dengan sistem otomatisasi terintegrasi, notifikasi denda, dan perpanjangan mandiri.</p>
            </div>
        </section>

        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-val">{{ number_format($totalCollections / 1000, 1) }}K+</span>
                <span class="stat-lab">Buku Cetak</span>
            </div>
            <div class="stat-item">
                <span class="stat-val">{{ number_format($totalDigital) }}</span>
                <span class="stat-lab">Aset Digital</span>
            </div>
            <div class="stat-item">
                <span class="stat-val">{{ number_format($activeLoans) }}</span>
                <span class="stat-lab">Peminjaman Aktif</span>
            </div>
        </div>

        <footer class="footer">
            <div class="footer-brand">{{ $profile->library_name ?? 'GIBTHA LIBRARY' }}</div>
            <p>{{ $profile->address ?? '' }}</p>
            <p style="margin-top: 1rem;">&copy; {{ date('Y') }} — Dikembangkan oleh <strong>Syamsuddin</strong></p>
        </footer>
    </div>
</body>
</html>
