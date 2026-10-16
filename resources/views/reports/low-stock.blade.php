@extends('layouts.app')

@section('title', 'Stok Rendah')

@section('content')
<div class="page-head">
    <h1>Stok Rendah</h1>
    <form method="GET" action="{{ route('reports.low-stock') }}" class="filter-bar" style="margin-bottom: 0;">
        <div>
            <label for="batas">Batas stok</label>
            <input type="number" id="batas" name="batas" value="{{ $threshold }}" min="1" style="width: 100px;">
        </div>
        <div>
            <button type="submit" class="btn btn-secondary">Terapkan</button>
        </div>
    </form>
</div>

<div class="card">
    <p class="hint" style="margin-top: 0;">Menampilkan produk dengan stok di bawah {{ $threshold }}, urut dari yang paling kritis.</p>
    <div class="table-wrap">
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
                        <td><span class="badge badge-low">{{ $product->stock }}</span></td>
                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Tidak ada produk dengan stok di bawah {{ $threshold }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection
