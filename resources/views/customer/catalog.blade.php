@extends('layouts.customer')

@section('title', 'Katalog')

@section('content')
<div class="page-head">
    <div>
        <h1>Katalog</h1>
        <p class="page-desc">Pilih barang, tentukan jumlah, tambah ke keranjang. Stok berkurang setelah staff mengkonfirmasi pesanan.</p>
    </div>
</div>

<form method="GET" action="{{ route('catalog.index') }}" class="filter-bar">
    <div class="field">
        <label for="search">Cari nama produk</label>
        <input type="text" id="search" name="search" value="{{ request('search') }}">
    </div>
    <div class="field">
        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn">Cari</button>
        <a href="{{ route('catalog.index') }}">Reset</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th class="num">Harga</th>
                <th>Ketersediaan</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name }}</td>
                    <td class="num">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td>
                        @if ($product->stock > 0)
                            Tersedia
                        @else
                            <span class="warn">Habis</span>
                        @endif
                    </td>
                    <td colspan="2">
                        @if ($product->stock > 0)
                            <form method="POST" action="{{ route('catalog.add') }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="input-mini">
                                <button type="submit" class="btn-outline">Tambah ke Keranjang</button>
                            </form>
                        @else
                            <button type="button" class="btn-outline" disabled>Habis</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $products->links() }}
@endsection
