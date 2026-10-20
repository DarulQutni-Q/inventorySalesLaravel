@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="page-head">
    <h1>Produk</h1>
    <span style="color:#64748b;font-size:.875rem;margin-top:.25rem;display:block;">Kelola stok dan harga produk</span>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
    @endif
</div>

<div class="card">
    <form method="GET" action="{{ route('products.index') }}" class="filter-bar">
        <div>
            <label for="search">Cari SKU / Nama</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="mis. BR-001 atau Beras">
        </div>
        <div>
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">Semua</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td><span style="background:#f1f5f9;color:#334155;border-radius:9999px;padding:.125rem .625rem;font-size:.75rem;display:inline-block;">{{ $product->category->name }}</span></td>
                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td>
                            {{ $product->stock }}
                            @if ($product->isLowStock())
                                <span class="badge badge-low">rendah</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary btn-sm">Detail</a>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Tidak ada produk yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection
