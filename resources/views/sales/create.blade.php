@extends('layouts.app')

@section('title', 'Buat Penjualan')

@section('content')
<h1>Buat Penjualan</h1>
<div class="card">
    <form method="POST" action="{{ route('sales.store') }}" id="sale-form">
        @csrf
        <div style="max-width: 320px;">
            <label for="sold_at">Tanggal Penjualan</label>
            <input type="datetime-local" id="sold_at" name="sold_at" value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}">
            @error('sold_at')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <h2 style="margin-top: 20px;">Item Produk</h2>
        @error('items')<div class="error-text">{{ $message }}</div>@enderror
        <table id="items-table">
            <thead>
                <tr><th>Produk</th><th>Stok</th><th>Harga</th><th>Qty</th><th></th></tr>
            </thead>
            <tbody id="items-body">
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary" id="add-item" style="margin-top: 8px;">Tambah Baris</button>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
const products = @json($productsJson);
let rowIndex = 0;

function addRow() {
    const tr = document.createElement('tr');
    let options = '<option value="">Pilih produk</option>';
    products.forEach(p => {
        options += `<option value="${p.id}">${p.sku} - ${p.name}</option>`;
    });
    tr.innerHTML = `
        <td><select name="items[${rowIndex}][product_id]" required>${options}</select></td>
        <td class="stock-cell">-</td>
        <td class="price-cell">-</td>
        <td><input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" required style="width: 90px;"></td>
        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
    `;
    // Tampilkan stok dan harga produk yang dipilih sebagai info.
    // Harga yang dipakai tetap dari database di server, bukan dari form ini.
    tr.querySelector('select').addEventListener('change', function () {
        const p = products.find(x => x.id == this.value);
        tr.querySelector('.stock-cell').textContent = p ? p.stock : '-';
        tr.querySelector('.price-cell').textContent = p ? 'Rp ' + Number(p.price).toLocaleString('id-ID') : '-';
        if (p) tr.querySelector('input[name$="[quantity]"]').max = p.stock;
    });
    tr.querySelector('.remove-row').addEventListener('click', () => tr.remove());
    document.getElementById('items-body').appendChild(tr);
    rowIndex++;
}

document.getElementById('add-item').addEventListener('click', addRow);
addRow();
</script>
@endsection
