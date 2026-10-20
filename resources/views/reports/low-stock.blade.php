@extends('layouts.app')

@section('title', 'Stok Rendah')

@section('content')
<div class="page-head">
    <h1>Stok Rendah</h1>
    <form method="GET" action="{{ route('reports.low-stock') }}" class="filter-bar" style="margin-bottom:0;">
        <div>
            <label for="batas">Batas stok</label>
            <input type="number" id="batas" name="batas" value="{{ $threshold }}" min="1" style="width:100px;">
        </div>
        <div>
            <button type="submit" class="btn btn-ghost">Terapkan</button>
        </div>
    </form>
</div>

<div class="card">
    <p class="hint" style="margin-top:0;">Menampilkan produk dengan stok di bawah <strong>{{ $threshold }}</strong>, urut dari yang paling kritis.</p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Min. Stok</th>
                    <th>Harga Beli</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td><code style="font-size:12px;background:#f1f5f9;padding:2px 6px;border-radius:4px;color:#475569;">{{ $product->sku }}</code></td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" style="color:#0f172a;font-weight:500;text-decoration:none;">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td style="color:#64748b;">{{ $product->category->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-low">{{ $product->stock }}</span>
                        </td>
                        <td style="color:#64748b;">{{ $product->minimum_stock }}</td>
                        <td style="color:#0f172a;font-weight:500;">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Tidak ada produk dengan stok di bawah {{ $threshold }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection
