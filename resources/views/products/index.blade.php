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
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td><span class="badge-sku">{{ $product->sku }}</span></td>
                        <td>{{ $product->name }}</td>
                        <td><span class="badge-category">{{ $product->category->name }}</span></td>
                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td>
                            @if ($product->stock <= 0)
                                <span class="badge-stock out">Habis</span>
                            @elseif ($product->isLowStock())
                                <span class="badge-stock low">{{ $product->stock }} (Menipis)</span>
                            @else
                                <span class="badge-stock ok">{{ $product->stock }} pcs</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('products.show', $product) }}" class="btn-action btn-action-view">Detail</a>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="btn-action btn-action-edit">Ubah</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete">Hapus</button>
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
