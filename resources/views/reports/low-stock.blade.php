@extends('layouts.app')

@section('title', 'Stok Rendah')

@section('content')
<div class="toolbar">
    <h1>Stok Rendah</h1>
    <form method="GET" action="{{ route('reports.low-stock') }}">
        <input type="number" name="batas" value="{{ $threshold }}" min="1" style="width: 90px;" title="Batas stok">
        <button type="submit" class="btn btn-secondary">Terapkan Batas</button>
    </form>
</div>

<div class="card">
    <p>Menampilkan produk dengan stok di bawah {{ $threshold }}.</p>
    <table>
        <thead>
            <tr><th>SKU</th><th>Produk</th><th>Kategori</th><th>Stok</th><th>Harga Beli</th></tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td><strong>{{ $product->stock }}</strong></td>
                    <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada produk dengan stok di bawah {{ $threshold }}.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection
