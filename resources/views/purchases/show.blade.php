@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
<h1>Detail Pembelian</h1>
<div class="card">
    <table>
        <tr><th style="width: 180px;">No. Transaksi</th><td>{{ $purchase->purchase_number }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $purchase->purchased_at->format('d M Y H:i') }}</td></tr>
        <tr><th>Supplier</th><td>{{ $purchase->supplier->name }}</td></tr>
        <tr><th>Dibuat oleh</th><td>{{ $purchase->user->name }}</td></tr>
        <tr><th>Status</th><td>{{ $purchase->status }}</td></tr>
        <tr><th>Total</th><td><strong>Rp {{ number_format($purchase->total, 0, ',', '.') }}</strong></td></tr>
    </table>
</div>

<div class="card">
    <h2>Item</h2>
    <table>
        <thead>
            <tr><th>SKU</th><th>Produk</th><th>Qty</th><th>Harga Beli</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach ($purchase->items as $item)
                <tr>
                    <td>{{ $item->product->sku }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 16px;">
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
