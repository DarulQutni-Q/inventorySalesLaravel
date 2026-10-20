<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login') - CRMEdge</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #374151;
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-wrap {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 24px rgba(15,23,42,0.07), 0 1px 4px rgba(15,23,42,0.04);
        }
        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #a3e635, #84cc16);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon svg { width: 24px; height: 24px; color: #fff; display: block; }
        .brand-name { font-weight: 700; font-size: 18px; color: #0f172a; display: block; }
        .brand-sub  { font-size: 12px; color: #94a3b8; display: block; margin-top: 1px; }
        .login-title { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .login-subtitle { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; margin-top: 16px; }
        label:first-of-type { margin-top: 0; }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: #374151;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus {
            border-color: #a3e635;
            box-shadow: 0 0 0 3px rgba(163,230,53,0.18);
        }
        .remember {
            display: flex; align-items: center; gap: 8px;
            margin-top: 16px; font-size: 13px; color: #475569;
        }
        .remember input[type=checkbox] { width: 16px; height: 16px; accent-color: #84cc16; }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 12px 14px;
            margin-top: 24px;
            border-radius: 12px;
            border: none;
            background: #0f172a;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: 0.01em;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #1e293b; }
        .error-text { color: #ef4444; font-size: 12px; margin-top: 4px; }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
        }
        .demo-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 24px;
            font-size: 12px;
            color: #64748b;
            line-height: 1.7;
        }
        .demo-box strong { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; margin-bottom: 8px; font-weight: 600; }
        .demo-box code {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 1px 7px;
            border-radius: 5px;
            font-size: 12px;
            font-family: 'SF Mono', 'Fira Code', monospace;
            color: #0f172a;
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.29 7 12 12 20.71 7"/>
                    <line x1="12" y1="22" x2="12" y2="12"/>
                </svg>
            </div>
            <div>
                <span class="brand-name">CRMEdge</span>
                <span class="brand-sub">Inventory &amp; Sales</span>
            </div>
        </div>
        @yield('content')
    </div>
</div>
</body>
</html>
