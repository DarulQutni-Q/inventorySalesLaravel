@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-head">
    <div>
        <h1>Overview</h1>
        <div class="subtitle">Monitor setiap aktivitas bisnis Anda</div>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Transaksi Baru
    </a>
</div>

{{-- ── Stat Cards ── --}}
<div class="stats">
    {{-- Penjualan Bulan Ini --}}
    <div class="stat-card">
        <div class="stat-card-inner">
            <div>
                <div class="stat-label">Penjualan Bulan Ini</div>
                <div class="stat-value small">Rp {{ number_format($stats['penjualan_bulan_ini'], 0, ',', '.') }}</div>
                @php
                    $tp = $stats['trend_penjualan'];
                    $tpClass = $tp > 0 ? 'up' : ($tp < 0 ? 'down' : 'neutral');
                    $tpArrow = $tp > 0 ? '↑' : ($tp < 0 ? '↓' : '→');
                @endphp
                <span class="stat-trend {{ $tpClass }}">{{ $tpArrow }} {{ abs($tp) }}%</span>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Rata-rata Nilai Jual --}}
    <div class="stat-card">
        <div class="stat-card-inner">
            <div>
                <div class="stat-label">Rata-rata Nilai Jual</div>
                <div class="stat-value small">Rp {{ number_format($stats['avg_penjualan'], 0, ',', '.') }}</div>
                @php
                    $at = $stats['avg_trend'];
                    $atClass = $at > 0 ? 'up' : ($at < 0 ? 'down' : 'neutral');
                    $atArrow = $at > 0 ? '↑' : ($at < 0 ? '↓' : '→');
                @endphp
                <span class="stat-trend {{ $atClass }}">{{ $atArrow }} {{ abs($at) }}%</span>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Transaksi --}}
    <div class="stat-card">
        <div class="stat-card-inner">
            <div>
                <div class="stat-label">Total Transaksi Bulan Ini</div>
                <div class="stat-value">{{ $stats['total_transaksi'] }}</div>
                @php
                    $tt = $stats['trend_transaksi'];
                    $ttClass = $tt > 0 ? 'up' : ($tt < 0 ? 'down' : 'neutral');
                    $ttArrow = $tt > 0 ? '↑' : ($tt < 0 ? '↓' : '→');
                @endphp
                <span class="stat-trend {{ $ttClass }}">{{ $ttArrow }} {{ abs($tt) }}%</span>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <path d="M9 12h6"/><path d="M9 16h4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Stok Menipis --}}
    <div class="stat-card">
        <div class="stat-card-inner">
            <div>
                <div class="stat-label">Produk Stok Rendah</div>
                <div class="stat-value" style="{{ $stats['stok_menipis'] > 0 ? 'color:#dc2626;' : '' }}">
                    {{ $stats['stok_menipis'] }}
                </div>
                @if ($stats['stok_menipis'] > 0)
                    <a href="{{ route('reports.low-stock') }}" class="stat-trend down" style="text-decoration:none;">Lihat detail</a>
                @else
                    <span class="stat-trend up">Semua aman</span>
                @endif
            </div>
            <div class="stat-icon {{ $stats['stok_menipis'] > 0 ? 'red' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- ── Middle Row: Gauge + Top Products + Latest Sales ── --}}
<div class="grid-3">
    {{-- Kinerja Penjualan Gauge --}}
    <div class="card">
        <h2>Kinerja Penjualan</h2>
        @php
            $skor  = $stats['skor_kinerja'];
            // SVG arc: radius 70, cx=90 cy=90, sweep from 210° to 210°+(skor/100)*300°
            $r   = 70;
            $cx  = 90; $cy = 90;
            $startDeg = 210;
            $totalDeg = 300;
            $sweepDeg = ($skor / 100) * $totalDeg;
            $endDeg   = $startDeg + $sweepDeg;
            // Convert degrees to radians for SVG
            $toRad = fn($d) => $d * M_PI / 180;
            $sx = $cx + $r * cos($toRad($startDeg));
            $sy = $cy + $r * sin($toRad($startDeg));
            $ex = $cx + $r * cos($toRad($endDeg));
            $ey = $cy + $r * sin($toRad($endDeg));
            $large = ($sweepDeg > 180) ? 1 : 0;
            // Background arc end
            $bgEndDeg = $startDeg + $totalDeg;
            $bex = $cx + $r * cos($toRad($bgEndDeg));
            $bey = $cy + $r * sin($toRad($bgEndDeg));
        @endphp
        <div class="gauge-wrap">
            <svg width="180" height="120" viewBox="0 0 180 120" class="gauge-svg">
                {{-- Background track --}}
                <path
                    d="M {{ round($sx,2) }} {{ round($sy,2) }} A {{ $r }} {{ $r }} 0 1 1 {{ round($bex,2) }} {{ round($bey,2) }}"
                    fill="none" stroke="#f1f5f9" stroke-width="12" stroke-linecap="round"/>
                {{-- Colored arc --}}
                @if ($skor > 0)
                <path
                    d="M {{ round($sx,2) }} {{ round($sy,2) }} A {{ $r }} {{ $r }} 0 {{ $large }} 1 {{ round($ex,2) }} {{ round($ey,2) }}"
                    fill="none" stroke="#a3e635" stroke-width="12" stroke-linecap="round"/>
                @endif
                {{-- Center text --}}
                <text x="90" y="80" text-anchor="middle" font-family="inherit">
                    <tspan font-size="26" font-weight="700" fill="#0f172a">{{ $skor }}</tspan>
                    <tspan font-size="14" fill="#94a3b8">/100</tspan>
                </text>
            </svg>
            <div style="text-align:center;margin-top:4px;">
                @if ($stats['total_transaksi'] > 0)
                    <span class="stat-trend up">+{{ $stats['total_transaksi'] }} transaksi bulan ini</span>
                @else
                    <span class="stat-trend neutral">Belum ada transaksi</span>
                @endif
                @if ($skor > 70)
                    <div style="font-size:13px;color:#64748b;margin-top:8px;">Tim Anda luar biasa! ✨</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Top Produk Terlaris --}}
    <div class="card">
        <h2>Top Produk Terlaris</h2>
        @forelse ($topProducts as $i => $tp)
            @php $rank = $i + 1; @endphp
            <div style="display:flex;align-items:center;gap:12px;padding:8px 0;{{ $i < count($topProducts)-1 ? 'border-bottom:1px solid #f1f5f9;' : '' }}">
                <span class="rank-badge rank-{{ $rank <= 3 ? $rank : 'n' }}">{{ $rank }}</span>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $tp->name }}</div>
                    <div style="font-size:11px;color:#94a3b8;">{{ $tp->sku }}</div>
                </div>
                <span style="font-size:13px;font-weight:700;color:#65a30d;white-space:nowrap;">{{ $tp->terjual }} pcs</span>
            </div>
        @empty
            <div class="empty">Belum ada data penjualan.</div>
        @endforelse
    </div>

    {{-- Transaksi Terkini --}}
    <div class="card">
        <h2>Transaksi Terkini</h2>
        @forelse ($latestSales as $i => $sale)
            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ $i < count($latestSales)-1 ? 'border-bottom:1px solid #f1f5f9;' : '' }}">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:#0f172a;">
                        <a href="{{ route('sales.show', $sale) }}" style="color:inherit;">{{ $sale->sale_number }}</a>
                    </div>
                    <div style="font-size:11px;color:#94a3b8;">{{ $sale->user?->name ?? '—' }} &bull; {{ \Carbon\Carbon::parse($sale->sold_at)->format('d M') }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:13px;font-weight:600;color:#334155;">Rp {{ number_format($sale->total, 0, ',', '.') }}</div>
                    <span class="badge {{ $sale->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
                        {{ $sale->status === 'completed' ? 'Selesai' : 'Batal' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="empty">Belum ada transaksi.</div>
        @endforelse
    </div>
</div>

{{-- ── Bottom Row: Bar Chart + Latest Purchases ── --}}
<div class="grid-2">
    {{-- Penjualan 7 Hari Terakhir --}}
    <div class="card">
        <h2>Penjualan 7 Hari Terakhir</h2>
        <div class="chart">
            @foreach ($chart as $bar)
                <div class="bar-col">
                    <div class="bar"
                         title="Rp {{ number_format($bar['total'], 0, ',', '.') }}"
                         style="height: {{ max($bar['pct'], 2) }}%;"></div>
                    <div class="bar-label">{{ $bar['label'] }}</div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:12px;font-size:12px;color:#94a3b8;text-align:right;">
            Total: Rp {{ number_format(collect($chart)->sum('total'), 0, ',', '.') }}
        </div>
    </div>

    {{-- Pembelian Terbaru --}}
    <div class="card">
        <h2>Pembelian Terbaru</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Supplier</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestPurchases as $purchase)
                        <tr>
                            <td>
                                <a href="{{ route('purchases.show', $purchase) }}"
                                   style="color:#0f172a;font-weight:500;">{{ $purchase->purchase_number }}</a>
                            </td>
                            <td>{{ $purchase->supplier?->name ?? '—' }}</td>
                            <td style="font-weight:600;">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="empty">Belum ada pembelian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
