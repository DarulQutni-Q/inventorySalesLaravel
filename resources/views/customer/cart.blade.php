@extends('layouts.customer')

@section('title', 'Keranjang')

@section('content')
<div class="page-head">
    <div>
        <h1>Keranjang</h1>
        <p class="page-desc">Keranjang disimpan di session, bukan di database. Harga dibaca dari database setiap kali halaman ini dibuka.</p>
    </div>
</div>

@if ($errors->has('cart'))
    <div class="alert alert-error">{{ $errors->first('cart') }}</div>
@endif

@if (count($products) === 0)
    <table>
        <tbody>
            <tr><td class="empty-cell">Keranjang kosong. <a href="{{ route('catalog.index') }}">Pilih barang di katalog</a>.</td></tr>
        </tbody>
    </table>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="num">Harga</th>
                    <th>Jumlah</th>
                    <th class="num">Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="num">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.update') }}" class="inline-form">
                                @csrf
                                <input type="number" name="quantity[{{ $product->id }}]" value="{{ $cart[$product->id] }}" min="1" class="input-mini">
                                <button type="submit" class="link-btn">Perbarui</button>
                            </form>
                        </td>
                        <td class="num">Rp {{ number_format($product->selling_price * $cart[$product->id], 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.remove', $product) }}">
                                @csrf
                                <button type="submit" class="link-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="num"><strong>Total (preview, dihitung ulang saat konfirmasi)</strong></td>
                    <td class="num"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="form-actions">
        <form method="POST" action="{{ route('cart.checkout') }}">
            @csrf
            <button type="submit" class="btn">Buat Pesanan</button>
        </form>
        <a href="{{ route('catalog.index') }}">Lanjut memilih barang</a>
    </div>
    <p class="hint">Stok belum berkurang. Stok berkurang saat staff mengkonfirmasi pesanan.</p>
@endif
@endsection
