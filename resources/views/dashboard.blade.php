@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-head">
    <div>
        <h1>Overview</h1>
        <div class="subtitle">Monitor setiap aktivitas bisnis Anda</div>
    </div>
    <div class="date-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
        </svg>
        {{ now()->startOfMonth()->format('M Y') }} &ndash; {{ now()->format('M Y') }}
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stats">
    {{-- Penjualan Bulan Ini --}}
    <div class="stat-card">
        <div class="stat-head">
            <span class="stat-label">Penjualan Bulan Ini</span>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        <div class="stat-value">Rp {{ number_format($stats['penjualan_bulan_ini'], 0, ',', '.') }}</div>
        @php
            $tp = $stats['trend_penjualan'];
            $tpClass = $tp > 0 ? 'up' : ($tp < 0 ? 'down' : 'neutral');
            $tpArrow = $tp > 0 ? '↑' : ($tp < 0 ? '↓' : '→');
        @endphp
        <span class="stat-trend {{ $tpClass }}">{{ $tpArrow }} {{ abs($tp) }}% dari bulan lalu</span>
    </div>

    {{-- Rata-rata Nilai Jual --}}
    <div class="stat-card">
        <div class="stat-head">
            <span class="stat-label">Rata-rata Nilai Jual</span>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        <div class="stat-value">Rp {{ number_format($stats['avg_penjualan'], 0, ',', '.') }}</div>
        @php
            $at = $stats['avg_trend'];
            $atClass = $at > 0 ? 'up' : ($at < 0 ? 'down' : 'neutral');
            $atArrow = $at > 0 ? '↑' : ($at < 0 ? '↓' : '→');
        @endphp
        <span class="stat-trend {{ $atClass }}">{{ $atArrow }} {{ abs($at) }}% dari bulan lalu</span>
    </div>

    {{-- Total Transaksi --}}
    <div class="stat-card">
        <div class="stat-head">
            <span class="stat-label">Total Transaksi Bulan Ini</span>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        <div class="stat-value">{{ $stats['total_transaksi'] }}</div>
        @php
            $tt = $stats['trend_transaksi'];
            $ttClass = $tt > 0 ? 'up' : ($tt < 0 ? 'down' : 'neutral');
            $ttArrow = $tt > 0 ? '↑' : ($tt < 0 ? '↓' : '→');
        @endphp
        <span class="stat-trend {{ $ttClass }}">{{ $ttArrow }} {{ abs($tt) }}% dari bulan lalu</span>
    </div>
</div>

{{-- ── Baris utama: Gauge | Top Produk | Rail Transaksi ── --}}
<div class="dash-grid">
    {{-- Kinerja Penjualan Gauge --}}
    <div class="card">
        <div class="card-head">
            <h2>Kinerja Penjualan</h2>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        @php
            $skor  = $stats['skor_kinerja'];
            // Busur gauge: mulai 135° (kiri bawah) sweep 270° ke 45° (kanan bawah).
            $r   = 70;
            $cx  = 90; $cy = 90;
            $startDeg = 135;
            $totalDeg = 270;
            $sweepDeg = ($skor / 100) * $totalDeg;
            $endDeg   = $startDeg + $sweepDeg;
            $toRad = fn($d) => $d * M_PI / 180;
            $sx = $cx + $r * cos($toRad($startDeg));
            $sy = $cy + $r * sin($toRad($startDeg));
            $ex = $cx + $r * cos($toRad($endDeg));
            $ey = $cy + $r * sin($toRad($endDeg));
            $bgEndDeg = $startDeg + $totalDeg;
            $bex = $cx + $r * cos($toRad($bgEndDeg));
            $bey = $cy + $r * sin($toRad($bgEndDeg));
            $scoreLarge = ($sweepDeg > 180) ? 1 : 0;
            $restDeg = $totalDeg - $sweepDeg;
            $restLarge = ($restDeg > 180) ? 1 : 0;
        @endphp
        <div class="gauge-wrap">
            <svg width="210" height="150" viewBox="0 0 180 150" class="gauge-svg">
                {{-- Segmen hijau (sisa) --}}
                @if ($skor < 100)
                <path
                    d="M {{ round($ex,2) }} {{ round($ey,2) }} A {{ $r }} {{ $r }} 0 {{ $restLarge }} 1 {{ round($bex,2) }} {{ round($bey,2) }}"
                    fill="none" stroke="#a3e635" stroke-width="15" stroke-linecap="round"/>
                @endif
                {{-- Segmen hitam (terisi) --}}
                @if ($skor > 0)
                <path
                    d="M {{ round($sx,2) }} {{ round($sy,2) }} A {{ $r }} {{ $r }} 0 {{ $scoreLarge }} 1 {{ round($ex,2) }} {{ round($ey,2) }}"
                    fill="none" stroke="#0f172a" stroke-width="15" stroke-linecap="round"/>
                @endif
                {{-- Angka tengah --}}
                <text x="90" y="78" text-anchor="middle" font-family="inherit">
                    <tspan class="gauge-score" x="82" y="76">{{ $skor }}</tspan>
                    @if ($stats['total_transaksi'] > 0)
                        <tspan class="gauge-delta" x="112" y="74">+{{ $stats['total_transaksi'] }}</tspan>
                    @endif
                </text>
                <text x="90" y="102" text-anchor="middle" font-family="inherit">
                    <tspan class="gauge-label">dari 100 Poin</tspan>
                </text>
            </svg>
            @if ($stats['total_transaksi'] > 0)
                <span class="stat-trend up" style="margin-top:4px;">+{{ $stats['total_transaksi'] }} transaksi bulan ini</span>
            @else
                <span class="stat-trend neutral" style="margin-top:4px;">Belum ada transaksi</span>
            @endif
            <div class="gauge-note">
                <strong>Tim Anda bekerja dengan baik! ✨</strong>
                Performa penjualan berjalan stabil. Terus pantau penjualan mingguan dan pastikan stok produk terlaris selalu tersedia.
            </div>
        </div>
    </div>

    {{-- Top Produk Terlaris --}}
    <div class="card">
        <div class="card-head">
            <h2>Top Produk Terlaris</h2>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        <div class="person-list">
            @forelse ($topProducts as $i => $tp)
                @php $rank = $i + 1; @endphp
                <div class="person-row">
                    <div class="avatar">{{ strtoupper(substr($tp->name, 0, 1)) }}</div>
                    <div style="min-width:0;">
                        <div class="person-name">{{ $tp->name }}</div>
                        <div class="person-sub">Terjual {{ $tp->terjual }} pcs &middot; {{ $tp->sku }}</div>
                    </div>
                    <div class="person-amt">{{ $tp->terjual }}</div>
                </div>
            @empty
                <div class="empty">Belum ada data penjualan.</div>
            @endforelse
        </div>
    </div>

    {{-- Rail: Transaksi Terkini --}}
    <div class="card">
        <div class="card-head">
            <h2>Transaksi Terkini</h2>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        <div class="doc-list">
            @forelse ($latestSales as $sale)
                <div class="doc-card">
                    <span class="doc-badge {{ $sale->status === 'completed' ? 'done' : 'pending' }}">
                        {{ $sale->status === 'completed' ? 'Selesai' : 'Batal' }}
                    </span>
                    <div class="doc-title">
                        <a href="{{ route('sales.show', $sale) }}" style="color:inherit;">{{ $sale->sale_number }}</a>
                    </div>
                    <div class="doc-sub">{{ $sale->user?->name ?? '—' }} &middot; {{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y') }}</div>
                    <div class="doc-grid">
                        <div>
                            <div class="k">Total</div>
                            <div class="v">Rp {{ number_format($sale->total, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="k">Item</div>
                            <div class="v">{{ $sale->items_count ?? $sale->items->count() }} produk</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty">Belum ada transaksi.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Baris bawah: Bar Chart + Pembelian Terbaru ── --}}
<div class="grid-2">
    {{-- Penjualan 7 Hari Terakhir --}}
    <div class="card">
        <div class="card-head">
            <h2>Penjualan 7 Hari Terakhir</h2>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
        <div class="chart">
            @foreach ($chart as $bar)
                <div class="bar-col">
                    <div class="bar-track">
                        <div class="bar"
                             title="Rp {{ number_format($bar['total'], 0, ',', '.') }}"
                             style="height: {{ max($bar['pct'], 3) }}%;"></div>
                    </div>
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
        <div class="card-head">
            <h2>Pembelian Terbaru</h2>
            <button class="kebab" aria-label="Opsi lain">&hellip;</button>
        </div>
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
