<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login') - Sistem Inventory</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f4f4f2;
            color: #222;
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 32px;
            width: 100%;
            max-width: 400px;
        }
        .login-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .brand-mark {
            width: 38px; height: 38px;
            background: #2f3b33; color: #fff;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px;
        }
        .brand-name { font-weight: 700; font-size: 16px; }
        .brand-sub { font-size: 12px; color: #888; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .sub { color: #777; font-size: 13px; margin: 0 0 8px; }
        form label { display: block; font-size: 13px; font-weight: 600; color: #444; margin: 14px 0 5px; }
        form input[type=email], form input[type=password] {
            width: 100%; padding: 10px 12px;
            border: 1px solid #c9c9c9; border-radius: 6px;
            font-size: 14px; font-family: inherit;
        }
        form input:focus { outline: none; border-color: #2f3b33; box-shadow: 0 0 0 2px rgba(47, 59, 51, 0.12); }
        .remember { display: flex; align-items: center; gap: 6px; margin-top: 14px; font-size: 13px; color: #555; }
        .btn {
            display: inline-block; width: 100%;
            padding: 10px 14px; margin-top: 18px;
            border-radius: 6px; border: 1px solid #2f3b33;
            background: #2f3b33; color: #fff;
            font-size: 15px; font-weight: 600; cursor: pointer; font-family: inherit;
        }
        .btn:hover { background: #243028; }
        .error-text { color: #a83232; font-size: 13px; margin-top: 4px; }
        .alert-error {
            background: #fdecea; border: 1px solid #f5a9a0; color: #8f1d12;
            padding: 10px 14px; border-radius: 6px; margin-bottom: 4px; font-size: 13px;
        }
        .demo { font-size: 12px; color: #888; margin-top: 18px; border-top: 1px solid #eee; padding-top: 14px; }
        .demo code { background: #f4f4f2; padding: 1px 6px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-brand">
        <div class="brand-mark">IS</div>
        <div>
            <div class="brand-name">Inventory</div>
            <div class="brand-sub">Sistem Distribusi</div>
        </div>
    </div>
    @yield('content')
</div>
</body>
</html>
