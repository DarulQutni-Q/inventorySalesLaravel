@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="page-head">
    <div>
        <h1 class="mono">{{ $sale->sale_number }}</h1>
        <p class="page-desc">Status: {{ $sale->status === 'completed' ? 'Selesai' : 'Batal' }}</p>
    </div>
</div>

<div class="table-wrap form-narrow">
    <table class="detail-table">
        <tr><th>Nomor transaksi</th><td class="mono">{{ $sale->sale_number }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $sale->sold_at->format('d M Y H:i') }}</td></tr>
        <tr><th>Pelanggan</th><td>{{ $sale->customer?->name ?? '-' }}</td></tr>
        @if ($sale->customerOrder)
            <tr><th>Dari pesanan</th><td><a href="{{ route('customer-orders.show', $sale->customerOrder) }}" class="mono">{{ $sale->customerOrder->order_number }}</a></td></tr>
        @endif
        <tr><th>Dibuat oleh</th><td>{{ $sale->user?->name }}</td></tr>
        <tr><th>Total</th><td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Item</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Produk</th>
                    <th class="num">Jumlah</th>
                    <th class="num">Harga satuan</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td class="mono">{{ $item->product?->sku }}</td>
                        <td>{{ $item->product?->name }}</td>
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
    <a href="{{ route('sales.index') }}">Kembali</a>
    @if (auth()->user()->isAdmin() && $sale->status === 'completed')
        <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Batalkan penjualan ini? Stok akan dikembalikan.')">
            @csrf
            <button type="submit" class="btn btn-danger">Batalkan Transaksi</button>
        </form>
    @endif
</div>
@endsection
