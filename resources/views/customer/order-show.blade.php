@extends('layouts.customer')

@section('title', 'Detail Pesanan')

@section('content')
<div class="page-head">
    <div>
        <h1 class="mono">{{ $order->order_number }}</h1>
        <p class="page-desc">Status: {{ $order->statusLabel() }}</p>
    </div>
</div>

<div class="table-wrap form-narrow">
    <table class="detail-table">
        <tr><th>Nomor pesanan</th><td class="mono">{{ $order->order_number }}</td></tr>
        <tr><th>Tanggal pesan</th><td>{{ $order->ordered_at->format('d M Y H:i') }}</td></tr>
        <tr><th>Status</th><td>{{ $order->statusLabel() }}</td></tr>
        @if ($order->confirmedBy)
            <tr><th>Dikonfirmasi oleh</th><td>{{ $order->confirmedBy->name }}</td></tr>
        @endif
        <tr><th>Total</th><td>Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Item</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="num">Jumlah</th>
                    <th class="num">Harga saat pesan</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            {{ $item->product?->name ?? 'Produk terhapus' }}
                            @if ($order->status === 'pending' && $item->product && $item->product->stock < $item->quantity)
                                <div class="error-text">Stok saat ini {{ $item->product->stock }}, konfirmasi kemungkinan ditolak.</div>
                            @endif
                        </td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="num">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('customer.orders.index') }}">Kembali</a>
</div>
@endsection
