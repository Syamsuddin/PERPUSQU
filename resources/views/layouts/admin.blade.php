<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PERPUSQU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pq-primary: #1e3a5f;
            --pq-primary-light: #2c5282;
            --pq-accent: #38b2ac;
            --pq-accent-hover: #319795;
            --pq-sidebar-bg: #1a202c;
            --pq-sidebar-width: 260px;
            --pq-header-height: 60px;
            --pq-body-bg: #f0f4f8;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: var(--pq-body-bg); overflow-x: hidden; }

        /* ── Sidebar ────────────────────────────────────── */
        .pq-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--pq-sidebar-width);
            background: var(--pq-sidebar-bg);
            color: #a0aec0;
            overflow-y: auto;
            z-index: 1040;
            transition: transform 0.3s;
        }
        .pq-sidebar-brand {
            height: var(--pq-header-height);
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.2);
            font-size: 1.25rem; font-weight: 700; color: #fff; letter-spacing: 2px;
            text-decoration: none;
        }
        .pq-sidebar-nav { padding: 1rem 0; }
        .pq-nav-label {
            padding: 0.5rem 1.25rem; font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 1.5px; color: #4a5568; font-weight: 600; margin-top: 0.5rem;
        }
        .pq-nav-item {
            display: flex; align-items: center; padding: 0.6rem 1.25rem;
            color: #a0aec0; text-decoration: none; font-size: 0.875rem;
            transition: all 0.2s; border-left: 3px solid transparent;
        }
        .pq-nav-item:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .pq-nav-item.active { color: #fff; background: rgba(56,178,172,0.15); border-left-color: var(--pq-accent); }
        .pq-nav-item i { width: 20px; margin-right: 0.75rem; font-size: 1rem; }
        .pq-submenu { padding-left: 2rem; }
        .pq-submenu .pq-nav-item { font-size: 0.8125rem; padding: 0.4rem 1.25rem; }
        .pq-collapse-btn { margin-left: auto; font-size: 0.75rem; transition: transform 0.3s; }
        .pq-collapse-btn.collapsed { transform: rotate(-90deg); }

        /* ── Header ─────────────────────────────────────── */
        .pq-header {
            position: fixed; top: 0; left: var(--pq-sidebar-width); right: 0;
            height: var(--pq-header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center;
            padding: 0 1.5rem;
            z-index: 1030;
        }
        .pq-header-title { font-weight: 600; color: var(--pq-primary); font-size: 1rem; }
        .pq-header-actions { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
        .pq-header-user { font-size: 0.875rem; color: #4a5568; font-weight: 500; }
        .pq-header-role { font-size: 0.7rem; color: #a0aec0; }

        /* ── Content ────────────────────────────────────── */
        .pq-content {
            margin-left: var(--pq-sidebar-width);
            margin-top: var(--pq-header-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--pq-header-height));
        }

        /* ── Breadcrumb ─────────────────────────────────── */
        .pq-breadcrumb { background: transparent; padding: 0; margin-bottom: 1rem; }
        .pq-breadcrumb .breadcrumb-item a { color: var(--pq-accent); text-decoration: none; }
        .pq-breadcrumb .breadcrumb-item.active { color: #718096; }

        /* ── Cards ──────────────────────────────────────── */
        .pq-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .pq-stat-card {
            border-radius: 0.75rem; padding: 1.25rem; color: #fff;
            transition: transform 0.2s;
        }
        .pq-stat-card:hover { transform: translateY(-2px); }
        .pq-stat-card .stat-icon { font-size: 2rem; opacity: 0.7; }
        .pq-stat-card .stat-value { font-size: 1.75rem; font-weight: 700; }
        .pq-stat-card .stat-label { font-size: 0.8rem; opacity: 0.85; }

        /* ── Table ──────────────────────────────────────── */
        .pq-table { border-collapse: separate; border-spacing: 0; }
        .pq-table thead th {
            background: #f7fafc; font-size: 0.8rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568;
            border-bottom: 2px solid #e2e8f0; padding: 0.75rem 1rem;
        }
        .pq-table tbody td { padding: 0.75rem 1rem; border-bottom: 1px solid #edf2f7; font-size: 0.875rem; vertical-align: middle; }
        .pq-table tbody tr:hover { background: #f7fafc; }

        /* ── Badges ─────────────────────────────────────── */
        .badge-active { background: #c6f6d5; color: #22543d; }
        .badge-inactive { background: #fed7d7; color: #742a2a; }

        /* ── Footer ─────────────────────────────────────── */
        .pq-footer {
            margin-left: var(--pq-sidebar-width);
            padding: 1rem 1.5rem;
            text-align: center; font-size: 0.75rem; color: #a0aec0;
            border-top: 1px solid #e2e8f0;
        }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 991.98px) {
            .pq-sidebar { transform: translateX(-100%); }
            .pq-sidebar.show { transform: translateX(0); }
            .pq-header, .pq-content, .pq-footer { margin-left: 0; }
            .pq-header { left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- ── Sidebar ── --}}
    <aside class="pq-sidebar" id="pqSidebar">
        <a href="{{ route('admin.dashboard.index') }}" class="pq-sidebar-brand">
            <i class="bi bi-book-half me-2"></i> PERPUSQU
        </a>
        <nav class="pq-sidebar-nav">
            {{-- Dashboard --}}
            @can('core.view_dashboard')
            <a href="{{ route('admin.dashboard.index') }}" class="pq-nav-item {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            @endcan

            {{-- Master Data --}}
            @canany(['authors.view','publishers.view','languages.view','classifications.view','subjects.view','collection_types.view','rack_locations.view','faculties.view','study_programs.view','item_conditions.view'])
            <div class="pq-nav-label">Master Data</div>
            @can('authors.view')
            <a href="{{ route('admin.master-data.authors.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.authors.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Pengarang</a>
            @endcan
            @can('publishers.view')
            <a href="{{ route('admin.master-data.publishers.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.publishers.*') ? 'active' : '' }}"><i class="bi bi-building"></i> Penerbit</a>
            @endcan
            @can('languages.view')
            <a href="{{ route('admin.master-data.languages.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.languages.*') ? 'active' : '' }}"><i class="bi bi-translate"></i> Bahasa</a>
            @endcan
            @can('classifications.view')
            <a href="{{ route('admin.master-data.classifications.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.classifications.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Klasifikasi</a>
            @endcan
            @can('subjects.view')
            <a href="{{ route('admin.master-data.subjects.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.subjects.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Subjek</a>
            @endcan
            @can('collection_types.view')
            <a href="{{ route('admin.master-data.collection-types.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.collection-types.*') ? 'active' : '' }}"><i class="bi bi-collection"></i> Jenis Koleksi</a>
            @endcan
            @can('rack_locations.view')
            <a href="{{ route('admin.master-data.rack-locations.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.rack-locations.*') ? 'active' : '' }}"><i class="bi bi-bookshelf"></i> Lokasi Rak</a>
            @endcan
            @can('faculties.view')
            <a href="{{ route('admin.master-data.faculties.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.faculties.*') ? 'active' : '' }}"><i class="bi bi-mortarboard"></i> Fakultas</a>
            @endcan
            @can('study_programs.view')
            <a href="{{ route('admin.master-data.study-programs.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.study-programs.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Program Studi</a>
            @endcan
            @can('item_conditions.view')
            <a href="{{ route('admin.master-data.item-conditions.index') }}" class="pq-nav-item {{ request()->routeIs('admin.master-data.item-conditions.*') ? 'active' : '' }}"><i class="bi bi-shield-check"></i> Kondisi Item</a>
            @endcan
            @endcanany

            {{-- Katalog --}}
            @can('catalog.view')
            <div class="pq-nav-label">Katalog</div>
            <a href="{{ route('admin.catalog.records.index') }}" class="pq-nav-item {{ request()->routeIs('admin.catalog.records.*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> Daftar Katalog</a>
            @endcan

            {{-- Koleksi Fisik --}}
            @can('collections.view')
            <div class="pq-nav-label">Koleksi Fisik</div>
            <a href="{{ route('admin.collections.items.index') }}" class="pq-nav-item {{ request()->routeIs('admin.collections.items.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Daftar Item</a>
            @endcan

            {{-- Anggota --}}
            @can('members.view')
            <div class="pq-nav-label">Anggota</div>
            <a href="{{ route('admin.members.index') }}" class="pq-nav-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Daftar Anggota</a>
            @endcan

            {{-- Sirkulasi --}}
            @canany(['circulation.process_loan','circulation.process_return','circulation.process_renewal','circulation.view_active_loans','circulation.view_history','circulation.view_fines'])
            <div class="pq-nav-label">Sirkulasi</div>
            @can('circulation.process_loan')
            <a href="{{ route('admin.circulation.loans.create') }}" class="pq-nav-item {{ request()->routeIs('admin.circulation.loans.create') ? 'active' : '' }}"><i class="bi bi-box-arrow-right"></i> Peminjaman</a>
            @endcan
            @can('circulation.process_return')
            <a href="{{ route('admin.circulation.returns.create') }}" class="pq-nav-item {{ request()->routeIs('admin.circulation.returns.*') ? 'active' : '' }}"><i class="bi bi-box-arrow-in-left"></i> Pengembalian</a>
            @endcan
            @can('circulation.view_active_loans')
            <a href="{{ route('admin.circulation.loans.active') }}" class="pq-nav-item {{ request()->routeIs('admin.circulation.loans.active') || request()->routeIs('admin.circulation.loans.show') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Pinjaman Aktif</a>
            @endcan
            @can('circulation.view_history')
            <a href="{{ route('admin.circulation.loans.history') }}" class="pq-nav-item {{ request()->routeIs('admin.circulation.loans.history') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Histori</a>
            @endcan
            @can('circulation.view_fines')
            <a href="{{ route('admin.circulation.fines.index') }}" class="pq-nav-item {{ request()->routeIs('admin.circulation.fines.*') ? 'active' : '' }}"><i class="bi bi-cash-stack"></i> Denda</a>
            @endcan
            @endcanany

            {{-- Repositori Digital --}}
            @can('digital_assets.view')
            <div class="pq-nav-label">Repositori Digital</div>
            <a href="{{ route('admin.digital-assets.index') }}" class="pq-nav-item {{ request()->routeIs('admin.digital-assets.*') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-up"></i> Aset Digital</a>
            @endcan

            {{-- Laporan --}}
            @canany(['reports.view_dashboard','reports.view_collections','reports.view_members','reports.view_circulation','reports.view_fines'])
            <div class="pq-nav-label">Laporan</div>
            @can('reports.view_dashboard')
            <a href="#" class="pq-nav-item"><i class="bi bi-bar-chart-line"></i> Dashboard Statistik</a>
            @endcan
            @endcanany

            {{-- Audit --}}
            @can('audit_logs.view')
            <div class="pq-nav-label">Audit & Monitoring</div>
            <a href="#" class="pq-nav-item"><i class="bi bi-file-earmark-text"></i> Audit Log</a>
            @endcan

            {{-- Pengaturan --}}
            @canany(['core.view_institution_profile','core.view_operational_rules'])
            <div class="pq-nav-label">Pengaturan</div>
            @can('core.view_institution_profile')
            <a href="{{ route('admin.settings.institution_profile.edit') }}" class="pq-nav-item {{ request()->routeIs('admin.settings.institution_profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Profil Institusi
            </a>
            @endcan
            @can('core.view_operational_rules')
            <a href="{{ route('admin.settings.operational_rules.edit') }}" class="pq-nav-item {{ request()->routeIs('admin.settings.operational_rules.*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> Aturan Operasional
            </a>
            @endcan
            @endcanany

            {{-- Manajemen Akses --}}
            @canany(['users.view','roles.view','permissions.view'])
            <div class="pq-nav-label">Manajemen Akses</div>
            @can('users.view')
            <a href="{{ route('admin.access.users.index') }}" class="pq-nav-item {{ request()->routeIs('admin.access.users.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Pengguna
            </a>
            @endcan
            @can('roles.view')
            <a href="{{ route('admin.access.roles.index') }}" class="pq-nav-item {{ request()->routeIs('admin.access.roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Role
            </a>
            @endcan
            @can('permissions.view')
            <a href="{{ route('admin.access.permissions.index') }}" class="pq-nav-item {{ request()->routeIs('admin.access.permissions.*') ? 'active' : '' }}">
                <i class="bi bi-key"></i> Permission
            </a>
            @endcan
            @endcanany
        </nav>
    </aside>

    {{-- ── Header ── --}}
    <header class="pq-header">
        <button class="btn btn-sm d-lg-none me-3" onclick="document.getElementById('pqSidebar').classList.toggle('show')">
            <i class="bi bi-list fs-4"></i>
        </button>
        <span class="pq-header-title">@yield('page-title', 'Dashboard')</span>
        <div class="pq-header-actions">
            <div class="text-end">
                <div class="pq-header-user">{{ auth()->user()->name }}</div>
                <div class="pq-header-role">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</div>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile.show') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.profile.password.edit') }}"><i class="bi bi-lock me-2"></i>Ubah Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- ── Content ── --}}
    <main class="pq-content">
        {{-- Breadcrumb --}}
        @hasSection('breadcrumb')
        <nav class="pq-breadcrumb">
            <ol class="breadcrumb mb-0">
                @yield('breadcrumb')
            </ol>
        </nav>
        @endif

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- ── Footer ── --}}
    <footer class="pq-footer">
        &copy; {{ date('Y') }} PERPUSQU — Sistem Informasi Perpustakaan Hibrid Kampus
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
