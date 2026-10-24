@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $product->name }}</h1>
        <p class="page-desc mono">{{ $product->sku }}</p>
    </div>
</div>

<div class="table-wrap form-narrow">
    <table class="detail-table">
        <tr><th>SKU</th><td class="mono">{{ $product->sku }}</td></tr>
        <tr><th>Nama</th><td>{{ $product->name }}</td></tr>
        <tr><th>Kategori</th><td>{{ $product->category?->name }}</td></tr>
        <tr><th>Harga beli</th><td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td></tr>
        <tr><th>Harga jual</th><td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td></tr>
        <tr>
            <th>Stok</th>
            <td>
                {{ $product->stock }}
                @if ($product->stock <= 0)
                    <span class="warn">Habis</span>
                @elseif ($product->isLowStock())
                    <span class="warn">Menipis</span>
                @endif
            </td>
        </tr>
        <tr><th>Stok minimum</th><td>{{ $product->minimum_stock }}</td></tr>
    </table>
</div>

<div class="form-actions">
    <a href="{{ route('products.index') }}">Kembali</a>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('products.edit', $product) }}" class="btn">Ubah</a>
    @endif
</div>
@endsection
