@extends('layouts.app')

@section('title', 'Ubah Produk')

@section('content')
<div class="page-head">
    <div>
        <h1>Ubah Produk</h1>
        <p class="page-desc">Perubahan harga berlaku untuk transaksi berikutnya.</p>
    </div>
</div>

<form method="POST" action="{{ route('products.update', $product) }}" class="form-narrow">
    @csrf
    @method('PUT')

    <div class="field">
        <label for="sku">SKU</label>
        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" maxlength="50">
        @error('sku')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="name">Nama produk</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" maxlength="150">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-grid">
        <div class="field">
            <label for="purchase_price">Harga beli (Rp)</label>
            <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" min="0" step="0.01">
            @error('purchase_price')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="selling_price">Harga jual (Rp)</label>
            <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" min="0" step="0.01">
            @error('selling_price')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="field">
        <label for="minimum_stock">Stok minimum</label>
        <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0">
        @error('minimum_stock')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label>Stok saat ini</label>
        <input type="text" value="{{ $product->stock }}" readonly>
        <p class="hint">Stok tidak diubah di sini, hanya lewat transaksi.</p>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('products.index') }}">Batal</a>
    </div>
</form>
@endsection
