<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventory') - Sistem Inventory</title>
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f4f4f2;
            color: #222;
            font-size: 14px;
            line-height: 1.5;
        }
        a { color: #2f3b33; }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid #e5e5e5;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 40;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 20px;
            border-bottom: 1px solid #eee;
        }
        .brand-mark {
            width: 36px; height: 36px;
            background: #2f3b33;
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }
        .brand-name { font-weight: 700; font-size: 15px; color: #222; }
        .brand-sub { font-size: 12px; color: #888; }
        .nav { flex: 1; overflow-y: auto; padding: 12px; }
        .nav-group {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #999;
            padding: 14px 10px 6px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 6px;
            color: #444;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 2px;
        }
        .nav-link svg { width: 17px; height: 17px; flex-shrink: 0; opacity: 0.75; }
        .nav-link:hover { background: #f0f0ee; color: #222; }
        .nav-link.active { background: #e9efe9; color: #2f3b33; font-weight: 600; }
        .nav-link.active svg { opacity: 1; }
        .sidebar-user {
            border-top: 1px solid #eee;
            padding: 14px 20px;
        }
        .sidebar-user .name { font-weight: 600; font-size: 14px; }
        .sidebar-user .role {
            display: inline-block;
            font-size: 11px;
            background: #eef2ee;
            color: #2f3b33;
            border: 1px solid #d7e0d7;
            border-radius: 20px;
            padding: 1px 9px;
            margin-top: 4px;
            text-transform: capitalize;
        }
        .logout-btn {
            background: none;
            border: none;
            color: #a83232;
            font-size: 13px;
            cursor: pointer;
            padding: 6px 0 0;
            font-family: inherit;
        }
        .logout-btn:hover { text-decoration: underline; }

        .backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 30;
        }
        .backdrop.show { display: block; }

        /* Konten */
        .main { flex: 1; margin-left: 240px; min-width: 0; display: flex; flex-direction: column; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .page-title { margin: 0; font-size: 19px; font-weight: 700; }
        .menu-btn {
            display: none;
            background: none;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 7px 9px;
            cursor: pointer;
        }
        .menu-btn svg { display: block; width: 18px; height: 18px; }
        .content { padding: 24px 28px 40px; max-width: 1180px; width: 100%; margin: 0 auto; }

        /* Kartu */
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }
        .card h2 { margin: 0 0 14px; font-size: 16px; }
        .card h3 { margin: 0 0 6px; font-size: 13px; color: #666; font-weight: 600; }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .page-head h1 { margin: 0; font-size: 22px; }

        /* Stat */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .stat { background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 16px; }
        .stat .label { font-size: 12px; color: #777; margin-bottom: 4px; }
        .stat .value { font-size: 24px; font-weight: 700; color: #222; }
        .stat .value.small { font-size: 19px; }

        /* Tabel */
        .table-wrap { overflow-x: auto; margin: 0 -20px; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; color: #888; font-weight: 600; background: #fafafa; }
        tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }
        .detail-table th { width: 170px; background: none; text-transform: none; font-size: 14px; color: #666; letter-spacing: 0; }

        /* Badge status */
        .badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            padding: 2px 10px;
        }
        .badge-done { background: #e6f4ea; color: #1c5c2e; border: 1px solid #bfe3c9; }
        .badge-cancel { background: #f1f1f1; color: #777; border: 1px solid #ddd; }
        .badge-low { background: #fdecea; color: #8f1d12; border: 1px solid #f5c6c0; }

        /* Tombol */
        .btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid #2f3b33;
            font-family: inherit;
            line-height: 1.4;
            white-space: nowrap;
        }
        .btn-primary { background: #2f3b33; color: #fff; }
        .btn-primary:hover { background: #243028; }
        .btn-secondary { background: #fff; color: #2f3b33; border-color: #c9d2c9; }
        .btn-secondary:hover { background: #f4f6f4; }
        .btn-danger { background: #a83232; border-color: #a83232; color: #fff; }
        .btn-danger:hover { background: #8f2a2a; }
        .btn-sm { padding: 5px 10px; font-size: 13px; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }

        /* Form */
        form label { display: block; font-size: 13px; font-weight: 600; color: #444; margin: 14px 0 5px; }
        form input[type=text], form input[type=email], form input[type=password],
        form input[type=number], form input[type=date], form input[type=datetime-local],
        form select, form textarea {
            width: 100%;
            padding: 9px 11px;
            border: 1px solid #c9c9c9;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
        }
        form input:focus, form select:focus, form textarea:focus {
            outline: none;
            border-color: #2f3b33;
            box-shadow: 0 0 0 2px rgba(47, 59, 51, 0.12);
        }
        form input[type=checkbox] { width: auto; margin-right: 6px; }
        .form-row { display: flex; gap: 14px; flex-wrap: wrap; }
        .form-row > div { flex: 1; min-width: 220px; }
        .form-narrow { max-width: 560px; }
        .error-text { color: #a83232; font-size: 13px; margin-top: 4px; }
        .hint { font-size: 13px; color: #777; margin-top: 10px; }
        .form-actions { margin-top: 20px; display: flex; gap: 8px; }

        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .filter-bar > div { min-width: 150px; }
        .filter-bar label { margin-top: 0; }

        /* Alert */
        .alert { padding: 11px 15px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #e6f4ea; border: 1px solid #a3d9b1; color: #1c5c2e; }
        .alert-error { background: #fdecea; border: 1px solid #f5a9a0; color: #8f1d12; }

        /* Grafik batang */
        .chart { display: flex; align-items: flex-end; gap: 10px; height: 190px; padding-top: 10px; }
        .chart .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; min-width: 0; }
        .chart .bar { width: 100%; max-width: 44px; background: #2f3b33; border-radius: 4px 4px 0 0; min-height: 3px; }
        .chart .bar-label { margin-top: 6px; font-size: 11px; color: #888; white-space: nowrap; }

        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
        .empty { color: #888; padding: 24px 0; text-align: center; }

        .pagination { margin-top: 16px; }
        .pagination nav { display: flex; gap: 4px; flex-wrap: wrap; }

        /* Mobile */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.2s ease; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .menu-btn { display: inline-block; }
            .content { padding: 16px 16px 32px; }
            .topbar { padding: 12px 16px; }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-mark">IS</div>
            <div>
                <div class="brand-name">Inventory</div>
                <div class="brand-sub">Sistem Distribusi</div>
            </div>
        </div>
        <nav class="nav">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
            @endif
            <div class="nav-group">Master Data</div>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
                Produk
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12l-8 8-9-9V4h7l10 8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                Kategori
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="5" width="14" height="11" rx="1"/><path d="M15 9h4l4 4v3h-8V9z"/><circle cx="6" cy="18.5" r="1.8"/><circle cx="18" cy="18.5" r="1.8"/></svg>
                Supplier
            </a>
            <div class="nav-group">Transaksi</div>
            <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M4 21h16"/></svg>
                Pembelian
            </a>
            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21V9"/><path d="M7 14l5-5 5 5"/><path d="M4 3h16"/></svg>
                Penjualan
            </a>
            @if (auth()->user()->isAdmin())
                <div class="nav-group">Laporan</div>
                <a href="{{ route('reports.low-stock') }}" class="nav-link {{ request()->routeIs('reports.low-stock') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l10 18H2L12 3z"/><path d="M12 10v4"/><circle cx="12" cy="17.5" r="0.5" fill="currentColor"/></svg>
                    Stok Rendah
                </a>
                <a href="{{ route('reports.transactions') }}" class="nav-link {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20V10"/><path d="M10 20V4"/><path d="M16 20v-8"/><path d="M22 20H2"/></svg>
                    Laporan Transaksi
                </a>
                <div class="nav-group">Admin</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><circle cx="17.5" cy="9.5" r="2.5"/><path d="M16 14.6c2.8.4 5 2.4 5 5.4"/></svg>
                    Kelola User
                </a>
            @endif
        </nav>
        <div class="sidebar-user">
            <div class="name">{{ auth()->user()->name }}</div>
            <span class="role">{{ auth()->user()->role }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Keluar</button>
            </form>
        </div>
    </aside>
    <div class="backdrop" id="backdrop"></div>

    <div class="main">
        <header class="topbar">
            <button class="menu-btn" id="menuBtn" aria-label="Buka menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>
            </button>
            <h1 class="page-title">@yield('title', 'Inventory')</h1>
        </header>
        <main class="content">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script>
document.getElementById('menuBtn').addEventListener('click', function () {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('backdrop').classList.add('show');
});
document.getElementById('backdrop').addEventListener('click', function () {
    document.getElementById('sidebar').classList.remove('open');
    this.classList.remove('show');
});
</script>
</body>
</html>
