@extends('layouts.app')

@section('title', 'Ubah Produk')

@section('content')
<div class="page-head">
    <h1>Ubah Produk</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        <label for="sku">SKU</label>
        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required maxlength="50">
        @error('sku')<div class="error-text">{{ $message }}</div>@enderror

        <label for="name">Nama Produk</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-row">
            <div>
                <label for="purchase_price">Harga Beli (Rp)</label>
                <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" min="0" step="0.01" required>
                @error('purchase_price')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div>
                <label for="selling_price">Harga Jual (Rp)</label>
                <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" min="0" step="0.01" required>
                @error('selling_price')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>

        <label for="minimum_stock">Stok Minimum</label>
        <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0" required>
        @error('minimum_stock')<div class="error-text">{{ $message }}</div>@enderror

        <p class="hint">Stok saat ini: {{ $product->stock }}. Stok hanya berubah lewat transaksi pembelian/penjualan.</p>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
