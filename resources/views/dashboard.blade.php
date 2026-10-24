@extends('layouts.app')

@section('title', 'Ringkasan')

@section('content')
<div class="page-head">
    <div>
        <h1>Ringkasan</h1>
        <p class="page-desc">Angka dari query database, tanpa perhitungan lain.</p>
    </div>
</div>

<table>
    <tbody>
        <tr>
            <td>Total produk</td>
            <td class="num">{{ number_format($stats['total_produk']) }}</td>
        </tr>
        <tr>
            <td>Penjualan hari ini</td>
            <td class="num">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Penjualan bulan ini</td>
            <td class="num">Rp {{ number_format($stats['penjualan_bulan_ini'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Produk stok rendah</td>
            <td class="num">
                {{ $stats['produk_stok_rendah'] }}
                <a href="{{ route('reports.low-stock') }}">Lihat stok rendah</a>
            </td>
        </tr>
        <tr>
            <td>Pesanan menunggu konfirmasi</td>
            <td class="num">
                {{ $stats['pesanan_menunggu'] }}
                <a href="{{ route('customer-orders.index') }}">Lihat pesanan pelanggan</a>
            </td>
        </tr>
    </tbody>
</table>

<div class="section">
    <h2>Penjualan terbaru</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th class="num">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestSales as $sale)
                    <tr>
                        <td><a href="{{ route('sales.show', $sale) }}" class="mono">{{ $sale->sale_number }}</a></td>
                        <td>{{ $sale->customer?->name ?? '-' }}</td>
                        <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                        <td class="num">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td>{{ $sale->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-cell">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="section">
    <h2>Pembelian terbaru</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Supplier</th>
                    <th>Tanggal</th>
                    <th class="num">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestPurchases as $purchase)
                    <tr>
                        <td><a href="{{ route('purchases.show', $purchase) }}" class="mono">{{ $purchase->purchase_number }}</a></td>
                        <td>{{ $purchase->supplier?->name }}</td>
                        <td>{{ $purchase->purchased_at->format('d/m/Y H:i') }}</td>
                        <td class="num">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td>{{ $purchase->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-cell">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="section">
    <h2>Stok rendah</h2>
    <p class="page-desc">Stok sudah menyentuh batas minimum, urut dari paling sedikit.</p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th class="num">Stok</th>
                    <th class="num">Stok minimum</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lowStockProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="mono">{{ $product->sku }}</td>
                        <td>{{ $product->category?->name }}</td>
                        <td class="num">{{ $product->stock }}</td>
                        <td class="num">{{ $product->minimum_stock }}</td>
                        <td>@if ($product->stock <= 0)<span class="warn">Habis</span>@else<span class="warn">Menipis</span>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-cell">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
