@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1>Dashboard</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card"><h3>Total Produk</h3><p style="font-size: 28px; font-weight: bold;">{{ $stats['total_produk'] }}</p></div>
    <div class="card"><h3>Stok Menipis</h3><p style="font-size: 28px; font-weight: bold;">{{ $stats['stok_menipis'] }}</p></div>
    <div class="card"><h3>Nilai Persediaan</h3><p style="font-size: 22px; font-weight: bold;">Rp {{ number_format($stats['nilai_persediaan'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Penjualan Hari Ini</h3><p style="font-size: 22px; font-weight: bold;">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Pembelian Hari Ini</h3><p style="font-size: 22px; font-weight: bold;">Rp {{ number_format($stats['pembelian_hari_ini'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Total User</h3><p style="font-size: 28px; font-weight: bold;">{{ $stats['total_user'] }}</p></div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
    <div class="card">
        <h2>Penjualan 7 Hari Terakhir</h2>
        <div style="display: flex; align-items: flex-end; gap: 8px; height: 180px; padding-top: 8px;">
            @foreach ($chart as $bar)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                    <div title="Rp {{ number_format($bar['total'], 0, ',', '.') }}" style="width: 100%; background: #2f3b33; height: {{ max($bar['pct'], 2) }}%;"></div>
                    <small style="margin-top: 4px;">{{ $bar['label'] }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h2>5 Produk Terlaris</h2>
        <table>
            <thead><tr><th>SKU</th><th>Produk</th><th>Terjual</th></tr></thead>
            <tbody>
                @forelse ($topProducts as $tp)
                    <tr><td>{{ $tp->sku }}</td><td>{{ $tp->name }}</td><td>{{ $tp->terjual }}</td></tr>
                @empty
                    <tr><td colspan="3">Belum ada penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; margin-top: 16px;">
    <div class="card">
        <h2>Penjualan Terbaru</h2>
        <table>
            <thead><tr><th>No. Transaksi</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($latestSales as $sale)
                    <tr>
                        <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->sale_number }}</a></td>
                        <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td>{{ $sale->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">Belum ada penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Pembelian Terbaru</h2>
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
                    <tr><td colspan="3">Belum ada pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
