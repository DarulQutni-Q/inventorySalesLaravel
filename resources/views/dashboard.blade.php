@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="stats">
    <div class="stat">
        <div class="label">Total Produk</div>
        <div class="value">{{ $stats['total_produk'] }}</div>
    </div>
    <div class="stat">
        <div class="label">Penjualan Hari Ini</div>
        <div class="value small">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</div>
    </div>
    <div class="stat">
        <div class="label">Stok Menipis</div>
        <div class="value" style="{{ $stats['stok_menipis'] > 0 ? 'color: #a83232;' : '' }}">{{ $stats['stok_menipis'] }}</div>
    </div>
    <div class="stat">
        <div class="label">Nilai Persediaan</div>
        <div class="value small">Rp {{ number_format($stats['nilai_persediaan'], 0, ',', '.') }}</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h2>Penjualan 7 Hari Terakhir</h2>
        <div class="chart">
            @foreach ($chart as $bar)
                <div class="bar-col">
                    <div class="bar" title="Rp {{ number_format($bar['total'], 0, ',', '.') }}" style="height: {{ max($bar['pct'], 2) }}%;"></div>
                    <div class="bar-label">{{ $bar['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h2>5 Produk Terlaris</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>SKU</th><th>Produk</th><th>Terjual</th></tr></thead>
                <tbody>
                    @forelse ($topProducts as $tp)
                        <tr><td>{{ $tp->sku }}</td><td>{{ $tp->name }}</td><td>{{ $tp->terjual }} pcs</td></tr>
                    @empty
                        <tr><td colspan="3" class="empty">Belum ada penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h2>Penjualan Terbaru</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>No. Transaksi</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($latestSales as $sale)
                        <tr>
                            <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->sale_number }}</a></td>
                            <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $sale->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
                                    {{ $sale->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="empty">Belum ada penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h2>Pembelian Terbaru</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>No. Transaksi</th><th>Supplier</th><th>Total</th></tr></thead>
                <tbody>
                    @forelse ($latestPurchases as $purchase)
                        <tr>
                            <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->purchase_number }}</a></td>
                            <td>{{ $purchase->supplier->name }}</td>
                            <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
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
