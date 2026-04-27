<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') — PERPUSQU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pq-primary: #1e3a5f;
            --pq-primary-light: #2c5282;
            --pq-accent: #38b2ac;
            --pq-accent-hover: #319795;
            --pq-bg: #f0f4f8;
            --pq-card-bg: rgba(255, 255, 255, 0.95);
        }
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, var(--pq-primary) 0%, var(--pq-primary-light) 50%, var(--pq-accent) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: var(--pq-card-bg);
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            max-width: 440px;
            width: 100%;
            padding: 2.5rem;
        }
        .auth-logo { text-align: center; margin-bottom: 1.5rem; }
        .auth-logo h1 { font-weight: 700; color: var(--pq-primary); font-size: 2rem; letter-spacing: 2px; }
        .auth-logo p { color: #718096; font-size: 0.875rem; margin-top: 0.25rem; }
        .form-label { font-weight: 500; color: #4a5568; font-size: 0.875rem; }
        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        .form-control:focus { border-color: var(--pq-accent); box-shadow: 0 0 0 3px rgba(56, 178, 172, 0.15); }
        .btn-login {
            background: var(--pq-primary);
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-login:hover { background: var(--pq-primary-light); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(30, 58, 95, 0.3); }
        .input-group-text { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem 0 0 0.5rem; }
        .form-control.is-invalid { border-color: #e53e3e; }
        .invalid-feedback { font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
