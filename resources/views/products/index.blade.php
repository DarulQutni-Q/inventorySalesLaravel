@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="page-head">
    <div>
        <h1>Produk</h1>
        <p class="page-desc">Daftar produk, stok, dan harga jual. Stok hanya berubah lewat transaksi.</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn">Tambah Produk</a>
    @endif
</div>

<form method="GET" action="{{ route('products.index') }}" class="filter-bar">
    <div class="field">
        <label for="search">Cari SKU atau nama</label>
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
        <a href="{{ route('products.index') }}">Reset</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th class="num">Harga beli</th>
                <th class="num">Harga jual</th>
                <th class="num">Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="mono">{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name }}</td>
                    <td class="num">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    <td class="num">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td class="num">
                        {{ $product->stock }}
                        @if ($product->stock <= 0)
                            <span class="warn">Habis</span>
                        @elseif ($product->isLowStock())
                            <span class="warn">Menipis</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('products.show', $product) }}">Detail</a>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('products.edit', $product) }}">Ubah</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-btn">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $products->links() }}
@endsection
