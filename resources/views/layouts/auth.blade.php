<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - {{ config('app.name', 'Inventory System') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 2.25rem;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            background-color: #0f172a;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="brand-header">
            <div class="brand-icon">
                <i class="bi bi-boxes"></i>
            </div>
            <h1 class="h4 fw-bold mb-1">Inventory System</h1>
            <p class="text-muted small mb-0">Masuk untuk mengelola inventaris dan stok</p>
        </div>

        <x-alert />

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
