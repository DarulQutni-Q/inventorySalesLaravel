<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - CRMEdge</title>
    <style>
        :root {
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --accent: #a3e635;
            --accent-dark: #84cc16;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-500: #64748b;
            --slate-400: #94a3b8;
            --slate-300: #cbd5e1;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --green-50: #f0fdf4;
            --green-200: #bbf7d0;
            --green-700: #15803d;
            --red-50: #fef2f2;
            --red-200: #fecaca;
            --red-600: #dc2626;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--slate-700);
            font-size: 14px;
            line-height: 1.5;
        }
        a { color: inherit; text-decoration: none; }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 40;
            transition: transform 0.22s ease;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #a3e635, #84cc16);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon svg { width: 22px; height: 22px; color: #fff; display: block; }
        .brand-name { font-weight: 700; font-size: 16px; color: var(--slate-900); display: block; }
        .brand-sub  { font-size: 11px; color: var(--slate-400); display: block; margin-top: 1px; }

        .nav { flex: 1; overflow-y: auto; padding: 12px; scrollbar-width: none; }
        .nav::-webkit-scrollbar { width: 0; }
        .nav-section {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--slate-400);
            padding: 16px 12px 6px;
            font-weight: 600;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 9999px;
            color: var(--slate-500);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 2px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-link:hover { background: var(--slate-50); color: var(--slate-900); }
        .nav-link.active { background: var(--slate-900); color: #fff; font-weight: 600; }
        .nav-link.active svg { color: #fff; }

        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 16px 20px;
        }
        .user-info { display: flex; gap: 10px; align-items: center; }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #d9f99d;
            color: #3f6212;
            font-weight: 700;
            font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-details { min-width: 0; }
        .user-name { font-weight: 600; font-size: 14px; color: var(--slate-900); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-email { font-size: 11px; color: var(--slate-400); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role {
            display: inline-block;
            font-size: 10px;
            border-radius: 20px;
            padding: 2px 8px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .user-role.admin { background: #d9f99d; color: #3f6212; }
        .user-role.staff { background: var(--slate-100); color: #475569; }
        .logout-btn {
            display: block;
            margin-top: 8px;
            background: none;
            border: none;
            color: #ef4444;
            font-size: 12px;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
        }
        .logout-btn:hover { text-decoration: underline; }

        /* ── Backdrop ── */
        .backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 30;
        }
        .backdrop.show { display: block; }

        /* ── Main ── */
        .main { flex: 1; margin-left: 260px; display: flex; flex-direction: column; min-width: 0; }

        /* ── Topbar ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .menu-btn {
            display: none;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px;
            cursor: pointer;
        }
        .menu-btn svg { width: 18px; height: 18px; display: block; }

        .topbar-search { flex: 1; max-width: 340px; position: relative; }
        .topbar-search svg {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--slate-400);
            pointer-events: none;
        }
        .topbar-search input[type=text] {
            width: 100%;
            padding: 9px 14px 9px 38px;
            border: 1px solid var(--border);
            border-radius: 9999px;
            background: var(--slate-50);
            font-size: 13px;
            color: #475569;
            outline: none;
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .topbar-search input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(163,230,53,0.15);
        }

        .topbar-actions { margin-left: auto; display: flex; gap: 10px; align-items: center; }
        .btn-new-tx {
            background: var(--slate-900);
            color: #fff;
            border: none;
            border-radius: 9999px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(15,23,42,0.18);
            transition: background 0.15s, transform 0.15s;
        }
        .btn-new-tx svg { width: 15px; height: 15px; }
        .btn-new-tx:hover { background: #1e293b; color: #fff; transform: translateY(-1px); }
        .btn-notif {
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            border-radius: 9999px;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            position: relative;
        }
        .btn-notif svg { width: 17px; height: 17px; color: var(--slate-500); }
        .notif-dot {
            position: absolute;
            top: 5px; right: 5px;
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* ── Content ── */
        .content { padding: 28px; max-width: 1280px; margin: 0 auto; width: 100%; }

        /* ── Card ── */
        .card {
            background: #fff;
            border: 1px solid #eef1f5;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(15,23,42,0.03);
        }
        .card h2 { margin: 0 0 18px; font-size: 16px; font-weight: 600; color: var(--slate-900); }

        /* ── Page Head ── */
        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .page-head h1 { margin: 0; font-size: 26px; font-weight: 700; color: var(--slate-900); letter-spacing: -0.02em; }
        .page-head .subtitle { font-size: 14px; color: var(--slate-400); margin-top: 2px; }

        /* ── Stats ── */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .stat-card {
            background: #fff;
            border: 1px solid #eef1f5;
            border-radius: 20px;
            padding: 22px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(15,23,42,0.03);
        }
        .stat-card-inner { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .stat-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
        .stat-label { font-size: 13px; color: var(--slate-500); font-weight: 500; }
        .stat-value { font-size: 30px; font-weight: 700; color: var(--slate-900); line-height: 1.05; letter-spacing: -0.02em; margin-top: 12px; }
        .stat-value.small { font-size: 22px; }
        .stat-trend { font-size: 12px; margin-top: 10px; display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 9999px; font-weight: 600; }
        .stat-trend.up { color: #16a34a; background: #f0fdf4; }
        .stat-trend.down { color: var(--red-600); background: var(--red-50); }
        .stat-trend.neutral { color: var(--slate-500); background: var(--slate-100); }
        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 14px;
            background: #ecfccb;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 22px; height: 22px; color: #65a30d; }
        .stat-icon.red { background: var(--red-50); }
        .stat-icon.red svg { color: var(--red-600); }

        /* ── Grid ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .dash-grid { display: grid; grid-template-columns: 1.05fr 1fr 1.15fr; gap: 20px; margin-bottom: 20px; align-items: stretch; }
        .dash-grid > .card { margin-bottom: 0; display: flex; flex-direction: column; }
        .dash-grid > .card > .person-list,
        .dash-grid > .card > .doc-list { flex: 1; }
        @media (max-width: 1180px) { .dash-grid { grid-template-columns: 1fr 1fr; } .dash-grid > :last-child { grid-column: 1 / -1; } }
        @media (max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } .dash-grid > :last-child { grid-column: auto; } }

        /* ── Kebab / date chip ── */
        .card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 18px; }
        .card-head h2 { margin: 0; }
        .kebab {
            width: 26px; height: 26px; border: none; background: none;
            color: var(--slate-400); cursor: pointer; border-radius: 6px;
            font-size: 15px; line-height: 1; display: inline-flex;
            align-items: center; justify-content: center;
        }
        .kebab:hover { background: var(--slate-50); color: var(--slate-700); }
        .date-chip {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; border: 1px solid #e6eaf0; border-radius: 10px;
            padding: 8px 12px; font-size: 13px; font-weight: 500; color: var(--slate-700);
        }
        .date-chip svg { width: 15px; height: 15px; color: var(--slate-500); }

        /* ── Doc rail ── */
        .doc-list { display: flex; flex-direction: column; gap: 14px; }
        .doc-card { border: 1px solid #eef1f5; border-radius: 16px; padding: 16px; background: #fff; }
        .doc-badge { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 9999px; margin-bottom: 10px; }
        .doc-badge.done { background: #dcfce7; color: #15803d; }
        .doc-badge.pending { background: #fff7ed; color: #c2410c; }
        .doc-title { font-size: 14px; font-weight: 600; color: var(--slate-900); }
        .doc-sub { font-size: 12px; color: var(--slate-400); margin-top: 2px; }
        .doc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 16px; margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--slate-100); }
        .doc-grid .k { font-size: 11px; color: var(--slate-400); }
        .doc-grid .v { font-size: 13px; font-weight: 600; color: var(--slate-900); margin-top: 1px; }

        /* ── Person list ── */
        .person-list { display: flex; flex-direction: column; }
        .person-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--slate-100); }
        .person-row:last-child { border-bottom: none; }
        .avatar { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #3f6212; background: #d9f99d; flex-shrink: 0; }
        .person-name { font-size: 13px; font-weight: 600; color: var(--slate-900); }
        .person-sub { font-size: 11px; color: var(--slate-400); }
        .person-amt { margin-left: auto; font-size: 13px; font-weight: 700; color: var(--slate-900); white-space: nowrap; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; margin: 0 -24px; padding: 0 24px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--slate-400);
            font-weight: 600;
            padding: 12px 16px;
            background: var(--slate-50);
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--slate-100);
            font-size: 14px;
            color: var(--slate-700);
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--slate-50); }
        .detail-table th { width: 180px; background: transparent; text-transform: none; font-size: 14px; color: var(--slate-500); letter-spacing: 0; font-weight: 500; }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            border-radius: 9999px;
            padding: 3px 10px;
        }
        .badge-done   { background: var(--green-50); color: var(--green-700); border: 1px solid var(--green-200); }
        .badge-cancel { background: var(--slate-50); color: var(--slate-500); border: 1px solid var(--border); }
        .badge-low    { background: var(--red-50); color: var(--red-600); border: 1px solid var(--red-200); }
        .badge-admin  { background: #d9f99d; color: #3f6212; }
        .badge-staff  { background: var(--slate-100); color: #475569; }
        .badge-pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            font-family: inherit;
            white-space: nowrap;
            line-height: 1.4;
            transition: all 0.15s;
        }
        .btn-primary { background: var(--slate-900); color: #fff; }
        .btn-primary:hover { background: #1e293b; color: #fff; }
        .btn-accent { background: var(--accent); color: var(--slate-900); }
        .btn-accent:hover { background: var(--accent-dark); }
        .btn-secondary { background: #fff; color: #475569; border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--slate-50); }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: var(--red-600); color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 0; }

        /* ── Form ── */
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        input[type=text], input[type=email], input[type=password],
        input[type=number], input[type=date], input[type=datetime-local],
        select, textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: #374151;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(163,230,53,0.15);
        }
        input[type=checkbox] { width: auto; }
        .error-text { color: #ef4444; font-size: 12px; margin-top: 4px; }
        .hint { font-size: 13px; color: var(--slate-400); margin-top: 6px; }
        .form-row { display: flex; gap: 16px; flex-wrap: wrap; }
        .form-row > div { flex: 1; min-width: 200px; }
        .form-narrow { max-width: 560px; }
        .form-actions { margin-top: 24px; display: flex; gap: 10px; }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filter-bar label { margin-top: 0; }

        /* ── Alerts ── */
        .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: var(--green-50); border: 1px solid var(--green-200); color: var(--green-700); }
        .alert-error   { background: var(--red-50); border: 1px solid var(--red-200); color: var(--red-600); }

        /* ── Bar Chart ── */
        .chart { display: flex; align-items: flex-end; gap: 10px; height: 150px; padding-top: 10px; }
        .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; min-width: 0; }
        .bar-track { width: 100%; max-width: 44px; height: 100%; background: var(--slate-100); border-radius: 8px; display: flex; align-items: flex-end; overflow: hidden; }
        .bar { width: 100%; background: var(--accent); border-radius: 8px; min-height: 4px; transition: opacity 0.15s; }
        .bar:hover { opacity: 0.85; }
        .bar-label { margin-top: 8px; font-size: 11px; color: var(--slate-400); white-space: nowrap; }

        /* ── Misc ── */
        .empty { color: var(--slate-400); text-align: center; padding: 32px 0; font-size: 14px; }
        .pagination { margin-top: 16px; }
        .pg { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
        .pg-info { font-size: 13px; color: var(--slate-500); }
        .pg-info strong { color: var(--slate-700); font-weight: 600; }
        .pg-pages { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
        .pg-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 10px;
            background: #fff; border: 1px solid var(--border); border-radius: 10px;
            font-size: 13px; font-weight: 500; color: var(--slate-700);
            line-height: 1; transition: all 0.15s;
        }
        .pg-btn:hover:not(.pg-disabled):not(.pg-active) { background: var(--slate-50); color: var(--slate-900); }
        .pg-btn svg { width: 15px; height: 15px; display: block; }
        .pg-active { background: var(--slate-900); border-color: var(--slate-900); color: #fff; font-weight: 600; }
        .pg-disabled { opacity: .45; cursor: not-allowed; }
        .pg-ellipsis { color: var(--slate-400); padding: 0 2px; }

        /* ── Notif dropdown ── */
        .notif-dropdown { position: relative; }
        .btn-notif {
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            border-radius: 9999px;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            position: relative;
        }
        .btn-notif svg { width: 17px; height: 17px; color: var(--slate-500); }
        .btn-notif:hover { background: var(--slate-50); }
        .notif-dot {
            position: absolute;
            top: 5px; right: 5px;
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }
        .notif-panel {
            position: absolute;
            top: calc(100% + 8px); right: 0;
            width: 320px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 50;
            overflow: hidden;
        }
        .notif-panel[hidden] { display: none; }
        .notif-header { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid var(--border); }
        .notif-header strong { font-size: 14px; color: var(--slate-900); }
        .notif-mark-all { font-size: 12px; color: var(--accent); font-weight: 500; }
        .notif-mark-all:hover { text-decoration: underline; }
        .notif-count { font-size: 11px; color: var(--slate-400); font-weight: 600; }
        .notif-list { max-height: 280px; overflow-y: auto; }
        .notif-item {
            display: flex; gap: 10px; padding: 12px 16px;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.15s;
        }
        .notif-item:hover { background: var(--slate-50); }
        .notif-item.unread { background: var(--green-50); }
        .notif-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--slate-100);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .notif-icon svg { width: 15px; height: 15px; color: var(--slate-500); }
        .notif-icon.sale { background: var(--green-50); }
        .notif-icon.sale svg { color: var(--green-700); }
        .notif-icon.purchase { background: #eff6ff; }
        .notif-icon.purchase svg { color: #2563eb; }
        .notif-icon.low-stock { background: var(--red-50); }
        .notif-icon.low-stock svg { color: var(--red-600); }
        .notif-content { flex: 1; min-width: 0; }
        .notif-title { display: block; font-size: 13px; font-weight: 600; color: var(--slate-900); margin-bottom: 2px; }
        .notif-desc { display: block; font-size: 12px; color: var(--slate-500); }
        .notif-time { display: block; font-size: 11px; color: var(--slate-400); margin-top: 4px; }
        .notif-empty { padding: 24px 16px; text-align: center; color: var(--slate-400); font-size: 13px; }
        .notif-footer { padding: 10px 16px; border-top: 1px solid var(--border); background: var(--slate-50); }
        .notif-footer a { font-size: 12px; color: var(--accent); font-weight: 500; }
        .notif-footer a:hover { text-decoration: underline; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

        /* ── Gauge ── */
        .gauge-wrap { display: flex; flex-direction: column; align-items: center; padding: 8px 0 4px; }
        .gauge-svg { overflow: visible; }
        .gauge-center { text-anchor: middle; dominant-baseline: middle; }
        .gauge-score { font-size: 40px; font-weight: 700; fill: var(--slate-900); font-family: inherit; letter-spacing: -0.03em; }
        .gauge-label { font-size: 12px; fill: var(--slate-400); font-family: inherit; }
        .gauge-delta { font-size: 13px; font-weight: 700; fill: #16a34a; font-family: inherit; }
        .gauge-caption { font-size: 12px; color: var(--slate-400); margin-top: 2px; }
        .gauge-note { font-size: 13px; color: var(--slate-500); margin-top: 16px; line-height: 1.6; }
        .gauge-note strong { display: block; color: var(--slate-900); font-size: 14px; margin-bottom: 6px; }

        /* ── Top-product rank ── */
        .rank-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 24px; height: 24px; border-radius: 8px;
            font-size: 12px; font-weight: 700;
        }
        .rank-1 { background: #fef9c3; color: #a16207; }
        .rank-2 { background: #f1f5f9; color: #475569; }
        .rank-3 { background: #fff7ed; color: #c2410c; }
        .rank-n { background: var(--slate-100); color: var(--slate-500); }

        /* ── Mobile ── */
        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                top: 0; bottom: 0; left: 0;
                transform: translateX(-100%);
                box-shadow: 0 0 40px rgba(15,23,42,0.2);
            }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .menu-btn { display: inline-flex; }
            .content { padding: 18px; }
            .topbar { padding: 12px 16px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .stats { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 560px) {
            .stats { grid-template-columns: 1fr; }
            .topbar-search { max-width: 160px; margin: 0; }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
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

        <nav class="nav">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
            @endif

            <div class="nav-section">Master Data</div>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/>
                </svg>
                Produk
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                Kategori
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13" rx="1"/>
                    <path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                Supplier
            </a>

            <div class="nav-section">Transaksi</div>
            <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v12"/><path d="M7.5 10.5 12 15l4.5-4.5"/>
                    <path d="M20 21H4"/></svg>
                Barang Masuk
            </a>
            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 21V9"/><path d="M7.5 13.5 12 9l4.5 4.5"/>
                    <path d="M20 3H4"/>
                </svg>
                Penjualan
            </a>

            @if (auth()->user()->isAdmin())
                <div class="nav-section">Laporan</div>
                <a href="{{ route('reports.low-stock') }}" class="nav-link {{ request()->routeIs('reports.low-stock') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Stok Rendah
                </a>
                <a href="{{ route('reports.transactions') }}" class="nav-link {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/>
                    </svg>
                    Laporan Transaksi
                </a>

                <div class="nav-section">Admin</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Kelola User
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-email">{{ auth()->user()->email }}</span>
                    <span class="user-role {{ auth()->user()->role === 'admin' ? 'admin' : 'staff' }}">{{ auth()->user()->role }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">↩ Keluar</button>
            </form>
        </div>
    </aside>

    <div class="backdrop" id="backdrop"></div>

    <div class="main">
        <header class="topbar">
            <button class="menu-btn" id="menuBtn" aria-label="Buka menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/>
                </svg>
            </button>

            <div class="topbar-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Cari transaksi, produk...">
            </div>

            <div class="topbar-actions">
                <div class="notif-dropdown" id="notifDropdown">
                    <button class="btn-notif" id="notifBtn" aria-label="Notifikasi" aria-expanded="false" aria-haspopup="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span class="notif-dot" id="notifDot" @if ($notifications->isEmpty()) style="display:none;" @endif></span>
                    </button>
                    <div class="notif-panel" id="notifPanel" role="menu" aria-label="Daftar notifikasi" hidden>
                        <div class="notif-header">
                            <strong>Notifikasi</strong>
                            <span class="notif-count">{{ $notifications->count() }} baru</span>
                        </div>
                        <div class="notif-list" id="notifList">
                            @forelse ($notifications as $n)
                                <a href="{{ $n['url'] }}" class="notif-item">
                                    <span class="notif-icon {{ $n['type'] }}">
                                        @if ($n['type'] === 'sale')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                            </svg>
                                        @endif
                                    </span>
                                    <span class="notif-content">
                                        <span class="notif-title">{{ $n['title'] }}</span>
                                        <span class="notif-desc">{{ $n['desc'] }}</span>
                                        <span class="notif-time">{{ $n['time'] }}</span>
                                    </span>
                                </a>
                            @empty
                                <div class="notif-empty">Tidak ada notifikasi baru</div>
                            @endforelse
                        </div>
                        <div class="notif-footer">
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('reports.transactions') }}">Lihat semua aktivitas</a>
                            @else
                                <a href="{{ route('sales.index') }}">Lihat semua aktivitas</a>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('sales.create') }}" class="btn-new-tx">+ Tambah Transaksi</a>
            </div>
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
    var menuBtn  = document.getElementById('menuBtn');
    var sidebar  = document.getElementById('sidebar');
    var backdrop = document.getElementById('backdrop');
    menuBtn.addEventListener('click', function () {
        sidebar.classList.add('open');
        backdrop.classList.add('show');
    });
    backdrop.addEventListener('click', function () {
        sidebar.classList.remove('open');
        backdrop.classList.remove('show');
    });

    // Notif dropdown
    var notifBtn  = document.getElementById('notifBtn');
    var notifPanel = document.getElementById('notifPanel');
    var notifDropdown = document.getElementById('notifDropdown');
    if (notifBtn && notifPanel) {
        notifBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = !notifPanel.hidden;
            notifPanel.hidden = isOpen;
            notifBtn.setAttribute('aria-expanded', !isOpen);
        });
        document.addEventListener('click', function (e) {
            if (!notifDropdown.contains(e.target)) {
                notifPanel.hidden = true;
                notifBtn.setAttribute('aria-expanded', 'false');
            }
        });
        // Prevent panel clicks from closing
        notifPanel.addEventListener('click', function (e) { e.stopPropagation(); });
    }
</script>
</body>
</html>
