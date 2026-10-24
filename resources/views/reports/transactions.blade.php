@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="page-head">
    <div>
        <h1>Laporan Transaksi</h1>
        <p class="page-desc">Ringkasan pembelian dan penjualan pada rentang tanggal.</p>
    </div>
</div>

<form method="GET" action="{{ route('reports.transactions') }}" class="filter-bar">
    <div class="field">
        <label for="dari">Dari tanggal</label>
        <input type="date" id="dari" name="dari" value="{{ $dari }}">
    </div>
    <div class="field">
        <label for="sampai">Sampai tanggal</label>
        <input type="date" id="sampai" name="sampai" value="{{ $sampai }}">
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn">Cari</button>
        <a href="{{ route('reports.transactions') }}">Reset</a>
    </div>
</form>

<table>
    <tbody>
        <tr>
            <td>Total pembelian ({{ $dari }} s/d {{ $sampai }})</td>
            <td class="num">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total penjualan ({{ $dari }} s/d {{ $sampai }})</td>
            <td class="num">Rp {{ number_format($totalSale, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div class="section">
    <h2>Pembelian</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th class="num">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td><a href="{{ route('purchases.show', $purchase) }}" class="mono">{{ $purchase->purchase_number }}</a></td>
                        <td>{{ $purchase->purchased_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $purchase->supplier?->name }}</td>
                        <td class="num">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td>{{ $purchase->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-cell">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $purchases->links() }}
</div>

<div class="section">
    <h2>Penjualan</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Oleh</th>
                    <th class="num">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td><a href="{{ route('sales.show', $sale) }}" class="mono">{{ $sale->sale_number }}</a></td>
                        <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->user?->name }}</td>
                        <td class="num">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td>{{ $sale->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-cell">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $sales->links() }}
</div>
@endsection
