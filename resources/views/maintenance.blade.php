<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Sedang Dalam Pemeliharaan — {{ $appName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; background: #f6f8f6; color: #1f2d24; }
        .maintenance-card { max-width: 34rem; text-align: center; padding: 3rem 2rem; }
        .maintenance-icon { font-size: 3.5rem; color: #2f7d5c; }
    </style>
</head>
<body>
    <main class="maintenance-card">
        <i class="bi bi-tools maintenance-icon"></i>
        <h1 class="h3 mt-3 mb-2">Sedang Dalam Pemeliharaan</h1>
        <p class="text-muted mb-4">
            Katalog publik {{ $appName }} untuk sementara tidak dapat diakses karena
            sedang ada pekerjaan pemeliharaan. Silakan kembali beberapa saat lagi.
        </p>
        <p class="text-muted small mb-0">
            Petugas perpustakaan dapat masuk melalui
            <a href="{{ route('auth.login') }}">halaman login</a>.
        </p>
    </main>
</body>
</html>
