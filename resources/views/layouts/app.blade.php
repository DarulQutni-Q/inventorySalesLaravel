<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventori dan Penjualan') - Inventori dan Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="app-header">
    <div class="app-header-inner">
        <a href="{{ url('/') }}" class="app-name">Inventori dan Penjualan</a>
        <div class="app-header-right">
            <span class="app-user">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="link-btn">Keluar</button>
            </form>
        </div>
    </div>
</header>

<div class="app-body">
    <aside class="sidebar">
        <nav class="app-nav">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Ringkasan</a>
            @endif

            <div class="nav-group-title">Master Data</div>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Produk</a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">Kategori</a>
            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">Supplier</a>

            <div class="nav-group-title">Transaksi</div>
            <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">Barang Masuk</a>
            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">Penjualan</a>

            @if (auth()->user()->isAdmin())
                <div class="nav-group-title">Laporan</div>
                <a href="{{ route('reports.low-stock') }}" class="nav-link {{ request()->routeIs('reports.low-stock') ? 'active' : '' }}">Stok Rendah</a>
                <a href="{{ route('reports.transactions') }}" class="nav-link {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">Laporan Transaksi</a>

                <div class="nav-group-title">Admin</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Kelola User</a>
            @endif
        </nav>
    </aside>

    <main class="content">
        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
