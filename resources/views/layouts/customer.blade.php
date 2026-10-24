<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Katalog') - Inventori dan Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="app-header">
    <div class="app-header-inner">
        <span class="app-name"><svg class="brand-mark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="4" y="4" width="16" height="16"/><path d="M4 9h16"/><path d="M12 9v11"/></svg>Inventori dan Penjualan</span>
        <div class="app-header-right">
            <span class="app-user">{{ session('customer_name') }}</span>
            <a href="{{ route('catalog.index') }}">Katalog</a>
            <a href="{{ route('cart.index') }}">Keranjang{{ $cartCount > 0 ? ' (' . $cartCount . ')' : '' }}</a>
            <a href="{{ route('customer.orders.index') }}">Pesanan Saya</a>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="link-btn">Keluar</button>
            </form>
        </div>
    </div>
</header>

<main class="customer-main">
    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>
</body>
</html>
