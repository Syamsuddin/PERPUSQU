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
            --primary: #0f172a; --primary-light: #1e293b; --accent: #38b2ac;
            --accent-glow: #4fd1c5; --accent-dark: #2c7a7b; --surface: #1a202c;
            --text: #e2e8f0; --text-muted: #94a3b8; --glass: rgba(255,255,255,0.05);
            --glass-border: rgba(255,255,255,0.08); --gradient-1: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--primary); color: var(--text); overflow-x: hidden; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }

        /* Animated background */
        .bg-grid { position: fixed; inset: 0; z-index: 0; opacity: 0.03;
            background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .bg-glow { position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.15; z-index: 0; pointer-events: none; }
        .bg-glow-1 { width: 600px; height: 600px; background: var(--accent); top: -200px; right: -100px; animation: float 12s ease-in-out infinite; }
        .bg-glow-2 { width: 500px; height: 500px; background: #6366f1; bottom: -150px; left: -100px; animation: float 15s ease-in-out infinite reverse; }
        .bg-glow-3 { width: 400px; height: 400px; background: #f59e0b; top: 50%; left: 50%; transform: translate(-50%,-50%); animation: float 18s ease-in-out infinite; opacity: 0.08; }
        @keyframes float { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-40px) scale(1.05); } }

        .wrapper { position: relative; z-index: 1; }

        /* Navigation */
        .nav-bar { position: fixed; top: 0; left: 0; right: 0; z-index: 100; padding: 1rem 2rem;
            background: rgba(15,23,42,0.7); backdrop-filter: blur(20px); border-bottom: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: space-between; transition: all 0.3s;
        }
        .nav-bar.scrolled { padding: 0.6rem 2rem; background: rgba(15,23,42,0.95); }
        .nav-brand { display: flex; align-items: center; gap: 0.75rem; font-weight: 800; font-size: 1.4rem; letter-spacing: 2px; }
        .nav-brand i { color: var(--accent); font-size: 1.6rem; }
        .nav-brand span { background: linear-gradient(135deg, #fff 0%, var(--accent-glow) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a { font-size: 0.875rem; font-weight: 500; color: var(--text-muted); transition: color 0.3s; }
        .nav-links a:hover { color: #fff; }
        .btn-login { padding: 0.6rem 1.5rem; background: var(--accent); color: #fff; border-radius: 8px; font-weight: 600; font-size: 0.875rem;
            transition: all 0.3s; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-login:hover { background: var(--accent-glow); transform: translateY(-1px); box-shadow: 0 8px 30px rgba(56,178,172,0.3); }
        .btn-outline { padding: 0.6rem 1.5rem; background: transparent; color: var(--accent); border: 1.5px solid var(--accent);
            border-radius: 8px; font-weight: 600; font-size: 0.875rem; transition: all 0.3s; cursor: pointer;
        }
        .btn-outline:hover { background: rgba(56,178,172,0.1); }

        /* Hero */
        .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 8rem 2rem 4rem; text-align: center; }
        .hero-inner { max-width: 820px; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; border-radius: 50px;
            background: rgba(56,178,172,0.1); border: 1px solid rgba(56,178,172,0.2); color: var(--accent-glow); font-size: 0.8rem; font-weight: 600; margin-bottom: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }
        .hero-badge .pulse { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
        .hero h1 { font-size: clamp(2.5rem, 6vw, 4.2rem); font-weight: 900; line-height: 1.1; margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 40%, var(--accent-glow) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: fadeInUp 0.8s ease-out;
        }
        .hero p { font-size: 1.15rem; color: var(--text-muted); max-width: 600px; margin: 0 auto 2.5rem; animation: fadeInUp 1s ease-out; }
        .hero-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; animation: fadeInUp 1.2s ease-out; }
        .hero-actions .btn-login { padding: 0.85rem 2.2rem; font-size: 1rem; }
        .hero-actions .btn-outline { padding: 0.85rem 2.2rem; font-size: 1rem; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* Stats bar */
        .stats-bar { display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 4rem; animation: fadeInUp 1.4s ease-out; }
        .stat-item { text-align: center; }
        .stat-item .stat-num { font-size: 2.2rem; font-weight: 800; color: #fff; }
        .stat-item .stat-num span { color: var(--accent); }
        .stat-item .stat-desc { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; }

        /* Section */
        section { padding: 5rem 2rem; }
        .section-title { text-align: center; margin-bottom: 3.5rem; }
        .section-title h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 0.75rem; }
        .section-title h2 em { font-style: normal; color: var(--accent); }
        .section-title p { color: var(--text-muted); max-width: 550px; margin: 0 auto; }
        .container { max-width: 1200px; margin: 0 auto; }

        /* Feature cards */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; }
        .feature-card { background: var(--glass); border: 1px solid var(--glass-border); border-radius: 16px; padding: 2rem;
            transition: all 0.4s; position: relative; overflow: hidden;
        }
        .feature-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--accent), #6366f1); opacity: 0; transition: opacity 0.4s;
        }
        .feature-card:hover { transform: translateY(-4px); border-color: rgba(56,178,172,0.3); background: rgba(255,255,255,0.07); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin-bottom: 1.25rem;
        }
        .feature-icon.teal { background: rgba(56,178,172,0.15); color: var(--accent-glow); }
        .feature-icon.indigo { background: rgba(99,102,241,0.15); color: #818cf8; }
        .feature-icon.amber { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .feature-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
        .feature-icon.sky { background: rgba(14,165,233,0.15); color: #38bdf8; }
        .feature-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
        .feature-card h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; }
        .feature-card p { font-size: 0.875rem; color: var(--text-muted); line-height: 1.7; }
        .feature-tag { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600;
            background: rgba(56,178,172,0.1); color: var(--accent); margin-top: 0.75rem;
        }

        /* Modules showcase */
        .modules-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
        .module-item { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; border-radius: 12px;
            background: var(--glass); border: 1px solid var(--glass-border); transition: all 0.3s;
        }
        .module-item:hover { background: rgba(255,255,255,0.07); border-color: rgba(56,178,172,0.2); }
        .module-item i { font-size: 1.5rem; color: var(--accent); flex-shrink: 0; }
        .module-item h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.15rem; }
        .module-item p { font-size: 0.75rem; color: var(--text-muted); }

        /* Tech stack */
        .tech-bar { display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; padding: 3rem 2rem;
            border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border);
        }
        .tech-item { display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; }
        .tech-item .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); }

        /* CTA */
        .cta { text-align: center; padding: 6rem 2rem; position: relative; }
        .cta-box { max-width: 650px; margin: 0 auto; padding: 3.5rem 2.5rem; border-radius: 24px;
            background: linear-gradient(135deg, rgba(56,178,172,0.1) 0%, rgba(99,102,241,0.08) 100%);
            border: 1px solid rgba(56,178,172,0.15);
        }
        .cta-box h2 { font-size: 2rem; font-weight: 800; margin-bottom: 1rem; }
        .cta-box p { color: var(--text-muted); margin-bottom: 2rem; }

        /* Footer */
        .footer { padding: 2.5rem 2rem; border-top: 1px solid var(--glass-border); text-align: center; }
        .footer p { font-size: 0.8rem; color: var(--text-muted); }
        .footer a { color: var(--accent); }

        /* Responsive */
        .mobile-menu { display: none; background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; }
        @media (max-width: 768px) {
            .nav-links { display: none; position: absolute; top: 100%; left: 0; right: 0; flex-direction: column;
                background: rgba(15,23,42,0.98); padding: 1.5rem; gap: 1rem; border-bottom: 1px solid var(--glass-border);
            }
            .nav-links.open { display: flex; }
            .mobile-menu { display: block; }
            .features-grid { grid-template-columns: 1fr; }
            .stats-bar { gap: 2rem; }
            .hero h1 { font-size: 2.2rem; }
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>
    <div class="bg-glow bg-glow-3"></div>

    <div class="wrapper">
        <!-- Navigation -->
        <nav class="nav-bar" id="navbar">
            <a href="/" class="nav-brand"><i class="bi bi-book-half"></i><span>PERPUSQU</span></a>
            <button class="mobile-menu" onclick="document.getElementById('navLinks').classList.toggle('open')"><i class="bi bi-list"></i></button>
            <div class="nav-links" id="navLinks">
                <a href="#fitur">Fitur</a>
                <a href="#modul">Modul</a>
                <a href="#teknologi">Teknologi</a>
                <a href="{{ route('auth.login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
            </div>
        </nav>

        <!-- Hero -->
        <section class="hero">
            <div class="hero-inner">
                <div class="hero-badge"><span class="pulse"></span> Sistem Perpustakaan Generasi Baru</div>
                <h1>Perpustakaan Digital Terpadu untuk Kampus Modern</h1>
                <p>Kelola koleksi fisik, repositori digital, sirkulasi, dan layanan anggota dalam satu platform terintegrasi yang dirancang untuk kebutuhan perpustakaan pendidikan tinggi.</p>
                <div class="hero-actions">
                    <a href="{{ route('auth.login') }}" class="btn-login"><i class="bi bi-rocket-takeoff"></i> Mulai Sekarang</a>
                    <a href="#fitur" class="btn-outline"><i class="bi bi-arrow-down-circle"></i> Jelajahi Fitur</a>
                </div>
                <div class="stats-bar">
                    <div class="stat-item"><div class="stat-num">11<span>+</span></div><div class="stat-desc">Modul Terintegrasi</div></div>
                    <div class="stat-item"><div class="stat-num">7<span>+</span></div><div class="stat-desc">Role Akses</div></div>
                    <div class="stat-item"><div class="stat-num">80<span>+</span></div><div class="stat-desc">Fitur Lengkap</div></div>
                    <div class="stat-item"><div class="stat-num">100<span>%</span></div><div class="stat-desc">Berbasis Web</div></div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="fitur">
            <div class="container">
                <div class="section-title">
                    <h2>Fitur <em>Unggulan</em></h2>
                    <p>Dirancang untuk menjawab kebutuhan perpustakaan kampus yang sesungguhnya — dari katalog hingga laporan analitik.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon teal"><i class="bi bi-search"></i></div>
                        <h3>OPAC — Katalog Publik Online</h3>
                        <p>Portal pencarian terbuka bagi mahasiswa, dosen, dan masyarakat umum untuk menemukan koleksi perpustakaan secara cepat dengan pencarian berbasis kata kunci.</p>
                        <span class="feature-tag">Akses Publik</span>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon indigo"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <h3>Katalogisasi Bibliografi</h3>
                        <p>Kelola metadata buku dan sumber daya dengan alur kerja Draft → Terbit → Arsip. Terintegrasi penuh dengan data master pengarang, penerbit, dan subjek.</p>
                        <span class="feature-tag">Authority Control</span>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon amber"><i class="bi bi-arrow-left-right"></i></div>
                        <h3>Sirkulasi Cerdas</h3>
                        <p>Peminjaman, pengembalian, perpanjangan, dan perhitungan denda otomatis. Pantau pinjaman aktif secara real-time dengan validasi kelayakan anggota.</p>
                        <span class="feature-tag">Otomatis</span>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon rose"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                        <h3>Repositori Digital & OCR</h3>
                        <p>Unggah, kelola, dan publikasikan aset digital. Dilengkapi integrasi OCR untuk ekstraksi teks otomatis dari dokumen hasil pindai.</p>
                        <span class="feature-tag">AI-Powered</span>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon sky"><i class="bi bi-people-fill"></i></div>
                        <h3>Manajemen Anggota</h3>
                        <p>Registrasi, aktivasi, pemblokiran, dan riwayat transaksi anggota. Mendukung tipe mahasiswa, dosen, dan umum dengan self-service portal.</p>
                        <span class="feature-tag">Multi-Tipe</span>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon emerald"><i class="bi bi-shield-lock-fill"></i></div>
                        <h3>Keamanan & RBAC</h3>
                        <p>Kontrol akses berbasis peran dengan 7 role dan 80+ permission granular. Dilengkapi audit trail untuk setiap perubahan data sistem.</p>
                        <span class="feature-tag">Enterprise-Grade</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modules -->
        <section id="modul">
            <div class="container">
                <div class="section-title">
                    <h2>Arsitektur <em>Modular</em></h2>
                    <p>11 modul domain-driven yang saling terintegrasi — mudah dikembangkan, mudah dipelihara.</p>
                </div>
                <div class="modules-grid">
                    <div class="module-item"><i class="bi bi-search"></i><div><h4>OPAC</h4><p>Katalog publik online</p></div></div>
                    <div class="module-item"><i class="bi bi-journal-bookmark"></i><div><h4>Catalog</h4><p>Manajemen bibliografi</p></div></div>
                    <div class="module-item"><i class="bi bi-box-seam"></i><div><h4>Collection</h4><p>Pelacakan item fisik</p></div></div>
                    <div class="module-item"><i class="bi bi-arrow-left-right"></i><div><h4>Circulation</h4><p>Pinjam, kembali, & denda</p></div></div>
                    <div class="module-item"><i class="bi bi-cloud-arrow-up"></i><div><h4>Digital Repository</h4><p>Aset digital & OCR</p></div></div>
                    <div class="module-item"><i class="bi bi-people"></i><div><h4>Member</h4><p>Manajemen anggota</p></div></div>
                    <div class="module-item"><i class="bi bi-person-lock"></i><div><h4>Identity</h4><p>Autentikasi & RBAC</p></div></div>
                    <div class="module-item"><i class="bi bi-database"></i><div><h4>Master Data</h4><p>Data referensi terpusat</p></div></div>
                    <div class="module-item"><i class="bi bi-speedometer2"></i><div><h4>Core</h4><p>Dashboard & pengaturan</p></div></div>
                    <div class="module-item"><i class="bi bi-bar-chart-line"></i><div><h4>Reporting</h4><p>Analitik & laporan</p></div></div>
                    <div class="module-item"><i class="bi bi-file-earmark-text"></i><div><h4>Audit</h4><p>Jejak aktivitas sistem</p></div></div>
                    <div class="module-item"><i class="bi bi-person-circle"></i><div><h4>Profile</h4><p>Profil pengguna</p></div></div>
                </div>
            </div>
        </section>

        <!-- Tech Stack -->
        <div class="tech-bar" id="teknologi">
            <div class="tech-item"><span class="dot"></span> Laravel 13</div>
            <div class="tech-item"><span class="dot"></span> PHP 8.4</div>
            <div class="tech-item"><span class="dot"></span> MySQL 8.4</div>
            <div class="tech-item"><span class="dot"></span> Bootstrap 5</div>
            <div class="tech-item"><span class="dot"></span> Vite 6</div>
            <div class="tech-item"><span class="dot"></span> Spatie RBAC</div>
            <div class="tech-item"><span class="dot"></span> Laravel Horizon</div>
            <div class="tech-item"><span class="dot"></span> Meilisearch</div>
        </div>

        <!-- CTA -->
        <section class="cta">
            <div class="cta-box">
                <h2>Siap Memulai?</h2>
                <p>Akses dashboard admin untuk mulai mengelola perpustakaan Anda, atau gunakan OPAC untuk menjelajahi koleksi yang tersedia.</p>
                <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ route('auth.login') }}" class="btn-login" style="padding:0.85rem 2.5rem;font-size:1rem;"><i class="bi bi-box-arrow-in-right"></i> Login Admin</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; {{ date('Y') }} <a href="/">PERPUSQU</a> — Sistem Informasi Perpustakaan Hibrid Kampus. All rights reserved.</p>
        </footer>
    </div>

    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
        });
        // Intersection Observer for fade-in
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.style.opacity = '1'; e.target.style.transform = 'translateY(0)'; } });
        }, { threshold: 0.1 });
        document.querySelectorAll('.feature-card, .module-item').forEach(el => {
            el.style.opacity = '0'; el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
