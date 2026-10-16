@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="page-head">
    <h1>Detail Penjualan</h1>
    <span class="badge {{ $sale->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
        {{ $sale->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
    </span>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="detail-table">
            <tr><th>No. Transaksi</th><td>{{ $sale->sale_number }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $sale->sold_at->format('d M Y H:i') }}</td></tr>
            <tr><th>Dibuat oleh</th><td>{{ $sale->user->name }}</td></tr>
            <tr><th>Total</th><td><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td></tr>
        </table>
    </div>
</div>

<div class="card">
    <h2>Item</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>SKU</th><th>Produk</th><th>Qty</th><th>Harga Satuan</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->sku }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-actions">
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
        @if (auth()->user()->isAdmin() && $sale->status === 'completed')
            <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Batalkan penjualan ini? Stok akan dikembalikan.')">
                @csrf
                <button type="submit" class="btn btn-danger">Batalkan Transaksi</button>
            </form>
        @endif
    </div>
</div>
@endsection
