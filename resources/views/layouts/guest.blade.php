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
        <span class="guest-brand-name"><svg class="brand-mark" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="4" y="4" width="16" height="16"/><path d="M4 9h16"/><path d="M12 9v11"/></svg>Inventori dan Penjualan</span>
        <span class="guest-brand-sub">Distribusi sembako</span>
    </div>
    @yield('content')
</div>
</body>
</html>
