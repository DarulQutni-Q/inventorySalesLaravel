@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="toolbar">
    <h1>Laporan Transaksi</h1>
    <form method="GET" action="{{ route('reports.transactions') }}">
        <input type="date" name="dari" value="{{ $dari }}" title="Dari tanggal">
        <input type="date" name="sampai" value="{{ $sampai }}" title="Sampai tanggal">
        <button type="submit" class="btn btn-secondary">Filter</button>
    </form>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card"><h3>Total Pembelian</h3><p style="font-size: 22px; font-weight: bold;">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</p></div>
    <div class="card"><h3>Total Penjualan</h3><p style="font-size: 22px; font-weight: bold;">Rp {{ number_format($totalSale, 0, ',', '.') }}</p></div>
</div>

<div class="card">
    <h2>Pembelian ({{ $dari }} s/d {{ $sampai }})</h2>
    <table>
        <thead><tr><th>No. Transaksi</th><th>Tanggal</th><th>Supplier</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->purchase_number }}</a></td>
                    <td>{{ $purchase->purchased_at->format('d M Y H:i') }}</td>
                    <td>{{ $purchase->supplier->name }}</td>
                    <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                    <td>{{ $purchase->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada pembelian pada rentang tanggal ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $purchases->links() }}</div>
</div>

<div class="card">
    <h2>Penjualan ({{ $dari }} s/d {{ $sampai }})</h2>
    <table>
        <thead><tr><th>No. Transaksi</th><th>Tanggal</th><th>Oleh</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->sale_number }}</a></td>
                    <td>{{ $sale->sold_at->format('d M Y H:i') }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>{{ $sale->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada penjualan pada rentang tanggal ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $sales->links() }}</div>
</div>
@endsection
