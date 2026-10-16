<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventory Sales')</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Arial, sans-serif; background: #f4f4f2; color: #222; }
        header { background: #2f3b33; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: bold; font-size: 18px; }
        header .brand a { color: #fff; text-decoration: none; }
        header nav a { color: #d7ded7; text-decoration: none; margin-left: 16px; font-size: 14px; }
        header nav a:hover { color: #fff; }
        header .user { font-size: 14px; color: #d7ded7; }
        header .user button { background: none; border: 1px solid #d7ded7; color: #fff; border-radius: 4px; padding: 4px 10px; cursor: pointer; margin-left: 10px; font-size: 13px; }
        main { max-width: 1080px; margin: 24px auto; padding: 0 24px; }
        .card { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin-bottom: 16px; }
        .alert { padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #e6f4ea; border: 1px solid #a3d9b1; color: #1c5c2e; }
        .alert-error { background: #fdecea; border: 1px solid #f5a9a0; color: #8f1d12; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 8px 10px; border-bottom: 1px solid #e5e5e5; }
        th { background: #f0f0ee; }
        .btn { display: inline-block; padding: 8px 14px; border-radius: 4px; text-decoration: none; font-size: 14px; cursor: pointer; border: 1px solid #2f3b33; }
        .btn-primary { background: #2f3b33; color: #fff; }
        .btn-secondary { background: #fff; color: #2f3b33; }
        .btn-danger { background: #a83232; border-color: #a83232; color: #fff; }
        form label { display: block; font-size: 14px; margin: 12px 0 4px; }
        form input[type=text], form input[type=email], form input[type=password], form input[type=number], form input[type=date], form select, form textarea { width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px; }
        .error-text { color: #a83232; font-size: 13px; margin-top: 4px; }
        .pagination { margin-top: 16px; font-size: 14px; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px; }
        .toolbar form { display: flex; gap: 8px; align-items: end; }
        .toolbar form input, .toolbar form select { width: auto; }
        h1 { font-size: 22px; margin: 0 0 16px; }
        h2 { font-size: 18px; margin: 0 0 12px; }
    </style>
</head>
<body>
<header>
    <div class="brand"><a href="{{ url('/') }}">Inventory Sales</a></div>
    <nav>
        @auth
            @if (Route::has('dashboard'))
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @endif
            @if (Route::has('products.index'))
                <a href="{{ route('products.index') }}">Produk</a>
            @endif
            @if (Route::has('purchases.index'))
                <a href="{{ route('purchases.index') }}">Pembelian</a>
            @endif
            @if (Route::has('sales.index'))
                <a href="{{ route('sales.index') }}">Penjualan</a>
            @endif
            @if (Route::has('reports.low-stock'))
                <a href="{{ route('reports.low-stock') }}">Stok Rendah</a>
            @endif
            @if (Route::has('reports.transactions'))
                <a href="{{ route('reports.transactions') }}">Laporan</a>
            @endif
            <span class="user">
                {{ auth()->user()->name }} ({{ auth()->user()->role }})
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </span>
        @endauth
    </nav>
</header>
<main>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if ($errors->any() && !isset($hideErrors))
        <div class="alert alert-error">
            <ul style="margin:0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
