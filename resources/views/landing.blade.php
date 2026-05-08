<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERPUSQU — Sistem Informasi Perpustakaan Hibrid Kampus</title>
    <meta name="description" content="PERPUSQU adalah sistem informasi perpustakaan digital terpadu untuk kampus. Kelola koleksi, sirkulasi, repositori digital, dan OPAC dalam satu platform modern.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #0f172a; --accent: #38b2ac; --accent-glow: #4fd1c5;
            --text: #e2e8f0; --text-muted: #94a3b8;
        }
        body, html { 
            height: 100%; 
            font-family: 'Inter', sans-serif; 
            background: var(--primary); 
            color: var(--text); 
            overflow: hidden; /* Mencegah scroll ke bawah */
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
            /* Semi transparent dark overlay untuk readability */
            background: linear-gradient(135deg, rgba(15,23,42,0.88) 0%, rgba(15,23,42,0.65) 100%);
            backdrop-filter: blur(3px);
        }

        .wrapper { 
            position: relative; 
            z-index: 2; 
            height: 100%; 
            display: flex; 
            flex-direction: column; 
        }

        /* Navigation */
        .nav-bar { 
            padding: 1.5rem 2rem; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
        }
        .nav-brand { 
            display: flex; align-items: center; gap: 0.75rem; 
            font-weight: 800; font-size: 1.4rem; letter-spacing: 2px; 
        }
        .nav-brand i { color: var(--accent); font-size: 1.6rem; }
        .nav-brand span { color: #fff; }
        
        .btn-login { 
            padding: 0.6rem 1.5rem; background: var(--accent); color: #fff; 
            border-radius: 8px; font-weight: 600; font-size: 0.875rem; 
            transition: all 0.3s; border: none; cursor: pointer; 
            display: inline-flex; align-items: center; gap: 0.5rem; 
        }
        .btn-login:hover { 
            background: var(--accent-glow); transform: translateY(-1px); 
            box-shadow: 0 4px 20px rgba(56,178,172,0.3); 
        }
        .btn-outline { 
            padding: 0.6rem 1.5rem; background: rgba(255,255,255,0.05); 
            color: #fff; border: 1px solid rgba(255,255,255,0.15); 
            border-radius: 8px; font-weight: 600; font-size: 0.875rem; 
            transition: all 0.3s; cursor: pointer; backdrop-filter: blur(10px); 
        }
        .btn-outline:hover { background: rgba(255,255,255,0.15); }

        /* Main Hero Content */
        .main-content { 
            flex: 1; display: flex; align-items: center; 
            justify-content: center; padding: 2rem; text-align: center; 
        }
        .hero-inner { 
            max-width: 800px; 
            animation: fadeInUp 0.8s ease-out; 
        }
        
        .hero-badge { 
            display: inline-flex; align-items: center; gap: 0.5rem; 
            padding: 0.4rem 1rem; border-radius: 50px; 
            background: rgba(56,178,172,0.15); border: 1px solid rgba(56,178,172,0.3); 
            color: var(--accent-glow); font-size: 0.85rem; font-weight: 600; 
            margin-bottom: 1.25rem; backdrop-filter: blur(10px); 
        }
        .hero-badge .pulse { 
            width: 8px; height: 8px; border-radius: 50%; 
            background: var(--accent); animation: pulse 2s infinite; 
        }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
        
        .hero-inner h1 { 
            font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 900; 
            line-height: 1.2; margin-bottom: 1rem; color: #fff; 
            text-shadow: 0 4px 12px rgba(0,0,0,0.4); 
        }
        .hero-inner p { 
            font-size: 1.05rem; color: #cbd5e1; max-width: 650px; 
            margin: 0 auto 2.5rem; line-height: 1.6; 
            text-shadow: 0 2px 8px rgba(0,0,0,0.3); 
        }

        /* Compact Stats */
        .stats-container { 
            display: flex; justify-content: center; gap: 1.5rem; 
            flex-wrap: wrap; margin-bottom: 2.5rem; 
        }
        .stat-card { 
            background: rgba(15,23,42,0.4); backdrop-filter: blur(12px); 
            border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; 
            padding: 1.25rem 2rem; display: flex; flex-direction: column; 
            align-items: center; min-width: 160px; transition: transform 0.3s; 
        }
        .stat-card:hover { 
            transform: translateY(-3px); border-color: rgba(56,178,172,0.3); 
            background: rgba(15,23,42,0.6); 
        }
        .stat-icon { font-size: 1.5rem; color: var(--accent-glow); margin-bottom: 0.5rem; }
        .stat-num { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 0.25rem; }
        .stat-label { font-size: 0.8rem; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

        .hero-actions { display: flex; gap: 1rem; justify-content: center; }

        /* Footer */
        .footer { 
            padding: 1.5rem; text-align: center; 
            border-top: 1px solid rgba(255,255,255,0.05); 
            backdrop-filter: blur(10px); background: rgba(15,23,42,0.3); 
        }
        .footer p { font-size: 0.85rem; color: var(--text-muted); }
        .footer a { color: var(--accent-glow); transition: color 0.3s; }
        .footer a:hover { color: #fff; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .stats-container { gap: 1rem; }
            .stat-card { min-width: calc(50% - 0.5rem); padding: 1rem; }
            .hero-inner h1 { font-size: 2rem; }
            .hero-actions { flex-direction: column; }
            .hero-actions a { width: 100%; justify-content: center; }
            body, html { overflow-y: auto; overflow-x: hidden; height: auto; } 
            .wrapper { min-height: 100vh; }
        }
    </style>
</head>
<body>
    <!-- Background Layer -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="wrapper">
        <!-- Minimal Navigation -->
        <nav class="nav-bar">
            <a href="/" class="nav-brand"><i class="bi bi-book-half"></i><span>PERPUSQU</span></a>
            <div class="nav-links">
                <a href="{{ route('auth.login') }}" class="btn-outline"><i class="bi bi-shield-lock"></i> Admin</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="hero-inner">
                <div class="hero-badge"><span class="pulse"></span> Sistem Perpustakaan Hibrid</div>
                
                <h1>Selamat Datang di PERPUSQU</h1>
                <p>Eksplorasi ribuan koleksi literatur, jurnal, dan repositori digital secara mudah dalam satu platform perpustakaan modern kampus.</p>
                
                <!-- Info Umum Perpustakaan -->
                <div class="stats-container">
                    <div class="stat-card">
                        <i class="bi bi-journal-bookmark-fill stat-icon"></i>
                        <div class="stat-num">12.5K</div>
                        <div class="stat-label">Jumlah Koleksi</div>
                    </div>
                    <div class="stat-card">
                        <i class="bi bi-people-fill stat-icon"></i>
                        <div class="stat-num">8.2K</div>
                        <div class="stat-label">Total Kunjungan</div>
                    </div>
                    <div class="stat-card">
                        <i class="bi bi-arrow-left-right stat-icon"></i>
                        <div class="stat-num">3.4K</div>
                        <div class="stat-label">Peminjaman Aktif</div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="{{ route('opac.home') }}" class="btn-login" style="padding: 0.85rem 2.2rem; font-size: 1rem;">
                        <i class="bi bi-search"></i> Telusuri Katalog (OPAC)
                    </a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; {{ date('Y') }} <a href="/">PERPUSQU</a> — Hak Cipta <strong>Syamsuddin</strong> 
                <a href="https://wa.me/6281349694696" target="_blank" rel="noopener" title="Hubungi via WhatsApp" style="color: #25D366; margin-left: 0.5rem; font-size: 1.1rem; vertical-align: middle;">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </p>
        </footer>
    </div>
</body>
</html>
