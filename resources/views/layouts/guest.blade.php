<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') - Inventori dan Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="guest-body">
<div class="guest-wrap">
    <div class="guest-brand">
        <span class="guest-brand-name">Inventori dan Penjualan</span>
        <span class="guest-brand-sub">Distribusi sembako</span>
    </div>
    @yield('content')
</div>
</body>
</html>
