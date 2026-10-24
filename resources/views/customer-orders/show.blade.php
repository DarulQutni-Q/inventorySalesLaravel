@extends('layouts.app')

@section('title', 'Detail Pesanan Pelanggan')

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
        <tr><th>Pelanggan</th><td>{{ $order->customer?->name }} ({{ $order->customer?->phone }})</td></tr>
        <tr><th>Tanggal pesan</th><td>{{ $order->ordered_at->format('d M Y H:i') }}</td></tr>
        <tr><th>Status</th><td>{{ $order->statusLabel() }}</td></tr>
        @if ($order->confirmedBy)
            <tr><th>Dikonfirmasi oleh</th><td>{{ $order->confirmedBy->name }}, {{ $order->confirmed_at->format('d M Y H:i') }}</td></tr>
        @endif
        <tr><th>Total saat pesan</th><td>Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Item</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="num">Stok saat ini</th>
                    <th class="num">Jumlah</th>
                    <th class="num">Harga saat pesan</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product?->name ?? 'Produk terhapus' }}</td>
                        <td class="num">{{ $item->product?->stock ?? '-' }}</td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="num">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="hint">Harga dan total sale dihitung ulang dari harga jual saat konfirmasi, bukan dari kolom harga saat pesan.</p>
</div>

@if ($order->status === 'pending')
    <div class="form-actions">
        <form method="POST" action="{{ route('customer-orders.confirm', $order) }}" onsubmit="return confirm('Konfirmasi pesanan ini? Penjualan dibuat dan stok berkurang.')">
            @csrf
            <button type="submit" class="btn">Konfirmasi jadi Penjualan</button>
        </form>
        <form method="POST" action="{{ route('customer-orders.reject', $order) }}" onsubmit="return confirm('Tolak pesanan ini? Tidak ada stok yang berubah.')">
            @csrf
            <button type="submit" class="btn btn-danger">Tolak</button>
        </form>
    </div>
@endif

<div class="form-actions">
    <a href="{{ route('customer-orders.index') }}">Kembali</a>
</div>
@endsection
