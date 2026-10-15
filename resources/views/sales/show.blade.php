@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<h1>Detail Penjualan</h1>
<div class="card">
    <table>
        <tr><th style="width: 180px;">No. Transaksi</th><td>{{ $sale->sale_number }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $sale->sold_at->format('d M Y H:i') }}</td></tr>
        <tr><th>Dibuat oleh</th><td>{{ $sale->user->name }}</td></tr>
        <tr><th>Status</th><td>{{ $sale->status }}</td></tr>
        <tr><th>Total</th><td><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td></tr>
    </table>
</div>

<div class="card">
    <h2>Item</h2>
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
    <div style="margin-top: 16px;">
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
