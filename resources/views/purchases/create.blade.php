@extends('layouts.app')

@section('title', 'Buat Pembelian')

@section('content')
<div class="page-head">
    <h1>Buat Pembelian</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('purchases.store') }}" id="purchase-form">
        @csrf
        <div class="form-row">
            <div>
                <label for="supplier_id">Supplier</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">Pilih supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div>
                <label for="purchased_at">Tanggal Pembelian</label>
                <input type="datetime-local" id="purchased_at" name="purchased_at" value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}">
                @error('purchased_at')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>

        <h2 style="margin-top: 24px;">Item Produk</h2>
        @error('items')<div class="error-text">{{ $message }}</div>@enderror
        <div class="table-wrap">
            <table id="items-table">
                <thead>
                    <tr><th>Produk</th><th style="width: 110px;">Qty</th><th style="width: 170px;">Harga Beli (Rp)</th><th style="width: 90px;"></th></tr>
                </thead>
                <tbody id="items-body">
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-secondary" id="add-item" style="margin-top: 10px;">Tambah Baris</button>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
const products = @json($productsJson);
let rowIndex = 0;

function productOptions(selected) {
    let html = '<option value="">Pilih produk</option>';
    products.forEach(p => {
        html += `<option value="${p.id}" ${p.id == selected ? 'selected' : ''}>${p.sku} - ${p.name}</option>`;
    });
    return html;
}

function addRow(productId = '', qty = 1, price = '') {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><select name="items[${rowIndex}][product_id]" required>${productOptions(productId)}</select></td>
        <td><input type="number" name="items[${rowIndex}][quantity]" value="${qty}" min="1" required></td>
        <td><input type="number" name="items[${rowIndex}][unit_cost]" value="${price}" min="0" step="0.01" required></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
    `;
    // Isi harga beli otomatis dari data produk saat produk dipilih.
    tr.querySelector('select').addEventListener('change', function () {
        const p = products.find(x => x.id == this.value);
        if (p) tr.querySelector('input[name$="[unit_cost]"]').value = p.price;
    });
    tr.querySelector('.remove-row').addEventListener('click', () => tr.remove());
    document.getElementById('items-body').appendChild(tr);
    rowIndex++;
}

document.getElementById('add-item').addEventListener('click', () => addRow());
addRow(); // mulai dengan satu baris kosong
</script>
@endsection
