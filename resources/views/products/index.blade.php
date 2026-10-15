@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="toolbar">
    <h1>Produk</h1>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
    @endif
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td>
                        {{ $product->stock }}
                        @if ($product->isLowStock())
                            <span style="color: #a83232; font-size: 12px;">(rendah)</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Detail</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary">Ubah</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada produk.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection
