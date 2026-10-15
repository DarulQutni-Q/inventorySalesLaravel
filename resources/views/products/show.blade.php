@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<h1>Detail Produk</h1>
<div class="card" style="max-width: 560px;">
    <table>
        <tr><th style="width: 160px;">SKU</th><td>{{ $product->sku }}</td></tr>
        <tr><th>Nama</th><td>{{ $product->name }}</td></tr>
        <tr><th>Kategori</th><td>{{ $product->category->name }}</td></tr>
        <tr><th>Harga Beli</th><td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td></tr>
        <tr><th>Harga Jual</th><td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td></tr>
        <tr><th>Stok</th><td>{{ $product->stock }}</td></tr>
        <tr><th>Stok Minimum</th><td>{{ $product->minimum_stock }}</td></tr>
    </table>
    <div style="margin-top: 16px;">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Ubah</a>
        @endif
    </div>
</div>
@endsection
