@extends('layouts.app')

@section('title', 'Stok Rendah')

@section('content')
<div class="page-head">
    <div>
        <h1>Stok Rendah</h1>
        <p class="page-desc">Produk yang stoknya sudah menyentuh batas minimum, urut dari paling sedikit.</p>
    </div>
</div>

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
            @forelse ($products as $product)
                <tr>
                    <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
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
{{ $products->links() }}
@endsection
