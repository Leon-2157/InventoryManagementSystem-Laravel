<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Inventory System') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --topbar-height: 64px;
            --bg-canvas: #f8fafc;
            --border-color: #e2e8f0;
        }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-canvas);
            color: #1e293b;
            min-height: 100vh;
        }
        #sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.2s ease-in-out;
        }
        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            color: #ffffff;
            font-size: 1.125rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
        }
        .sidebar-nav {
            padding: 1rem 0.75rem;
            list-style: none;
            margin: 0;
        }
        .nav-label {
            font-size: 0.6875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 0.5rem 0.75rem 0.25rem;
            font-weight: 600;
        }
        .sidebar-nav .nav-link {
            color: #cbd5e1;
            padding: 0.625rem 0.75rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            margin-bottom: 0.25rem;
        }
        .sidebar-nav .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }
        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background-color: var(--sidebar-active);
            font-weight: 600;
        }
        .sidebar-nav .nav-link i {
            font-size: 1.125rem;
            line-height: 1;
        }
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #topbar {
            height: var(--topbar-height);
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }
        .content-area {
            padding: 1.75rem;
            flex-grow: 1;
        }
        .card {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            background-color: #ffffff;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        .table thead th {
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: #64748b;
            padding: 0.75rem 1rem;
        }
        .table tbody td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }
        .badge-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        .badge-low-stock {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-in-stock {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-movement-in {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-movement-out {
            background-color: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0;
            }
            .content-area {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside id="sidebar" aria-label="Sidebar Navigation">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <i class="bi bi-boxes text-primary me-2 fs-4"></i>
            <span>Inventory System</span>
        </a>
        <ul class="sidebar-nav">
            <li class="nav-label">Menu Utama</li>
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i>
                    <span>Supplier</span>
                </a>
            </li>

            <li class="nav-label mt-3">Transaksi Stok</li>
            <li>
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.stock-in') }}" class="nav-link {{ request()->routeIs('transactions.stock-in') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span>Stok Masuk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.stock-out') }}" class="nav-link {{ request()->routeIs('transactions.stock-out') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-up"></i>
                    <span>Stok Keluar</span>
                </a>
            </li>

            @if (auth()->user()->isAdmin())
                <li class="nav-label mt-3">Administrasi</li>
                <li>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Topbar -->
        <header id="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" id="sidebarToggle" aria-label="Toggle Navigation">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="fw-semibold text-secondary d-none d-sm-block">
                    @yield('breadcrumb', 'Inventory Management')
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold small">{{ auth()->user()->profile?->full_name ?? auth()->user()->username }}</div>
                    <span class="badge {{ auth()->user()->isAdmin() ? 'bg-primary' : 'bg-secondary' }}" style="font-size: 0.6875rem;">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" title="Keluar dari sistem">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content-area">
            <x-alert />
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            if (toggle && sidebar) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
