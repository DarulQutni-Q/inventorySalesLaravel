@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
<div class="page-head">
    <h1>Tambah Barang Masuk</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('purchases.store') }}" id="purchase-form">
        @csrf

        {{-- Header info: No Faktur dan Tanggal --}}
        <div class="form-row">
            <div>
                <label>No Faktur</label>
                <input type="text" value="{{ $nextNumber }}" readonly style="background:#f5f5f5;">
            </div>
            <div>
                <label for="purchased_at">Tanggal Masuk</label>
                <input type="datetime-local" id="purchased_at" name="purchased_at"
                       value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}">
                @error('purchased_at')<div class="error-text">{{ $message }}</div>@enderror
            </div>
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
        </div>

        {{-- Input tambah item --}}
        <div class="form-row" style="margin-top: 20px; align-items: flex-end;">
            <div>
                <label for="pick-product">Pilih Barang</label>
                <select id="pick-product">
                    <option value="">-- pilih barang --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->purchase_price }}"
                                data-name="{{ $product->name }}">
                            {{ $product->sku }} – {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="pick-price">Harga Beli</label>
                <input type="number" id="pick-price" min="0" step="1" placeholder="0">
            </div>
            <div>
                <label for="pick-qty">Jumlah Masuk</label>
                <input type="number" id="pick-qty" min="1" value="1">
            </div>
            <div>
                <button type="button" id="btn-add-item" class="btn btn-secondary">+ Tambah ke Daftar</button>
            </div>
        </div>

        {{-- Error items global --}}
        @error('items')<div class="error-text" style="margin-top: 8px;">{{ $message }}</div>@enderror

        {{-- Tabel daftar barang yang akan disimpan --}}
        <h2 style="margin-top: 24px;">Daftar Barang Masuk</h2>
        <div class="table-wrap">
            <table id="items-table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th style="width: 90px;">Jumlah</th>
                        <th style="width: 160px;">Harga Beli</th>
                        <th style="width: 160px;">Subtotal</th>
                        <th style="width: 70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr id="empty-row"><td colspan="5" class="empty">Belum ada barang ditambahkan.</td></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:600;">Total:</td>
                        <td id="grand-total" style="font-weight:600;">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Hidden inputs untuk item list — diisi oleh JS sebelum submit --}}
        <div id="hidden-inputs"></div>

        <div class="form-actions">
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" id="btn-submit">Simpan Barang Masuk</button>
        </div>
    </form>
</div>

<script>
const products = @json($productsJson);
let itemList = []; // [{productId, name, qty, price}]

// Isi harga otomatis saat produk dipilih
document.getElementById('pick-product').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const price = opt.dataset.price ?? '';
    document.getElementById('pick-price').value = price ? Math.round(price) : '';
});

document.getElementById('btn-add-item').addEventListener('click', function () {
    const sel = document.getElementById('pick-product');
    const productId = parseInt(sel.value);
    const qty = parseInt(document.getElementById('pick-qty').value);
    const price = parseFloat(document.getElementById('pick-price').value);

    if (!productId) { alert('Pilih barang terlebih dahulu.'); return; }
    if (!qty || qty < 1) { alert('Jumlah harus minimal 1.'); return; }
    if (isNaN(price) || price < 0) { alert('Harga beli tidak valid.'); return; }

    // Cek duplikat — tambahkan qty jika produk sudah ada
    const existing = itemList.find(i => i.productId === productId);
    if (existing) {
        existing.qty += qty;
        renderTable();
        return;
    }

    const name = sel.options[sel.selectedIndex].dataset.name;
    itemList.push({ productId, name, qty, price });
    renderTable();

    // Reset pilihan
    sel.value = '';
    document.getElementById('pick-qty').value = 1;
    document.getElementById('pick-price').value = '';
});

function renderTable() {
    const tbody = document.getElementById('items-body');
    const emptyRow = document.getElementById('empty-row');

    // Hapus semua baris kecuali empty-row
    Array.from(tbody.querySelectorAll('tr.item-row')).forEach(r => r.remove());

    let grand = 0;
    itemList.forEach((item, idx) => {
        const subtotal = item.qty * item.price;
        grand += subtotal;
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>${item.name}</td>
            <td>${item.qty}</td>
            <td>Rp ${formatNumber(item.price)}</td>
            <td>Rp ${formatNumber(subtotal)}</td>
            <td><button type="button" class="btn btn-danger btn-sm" data-idx="${idx}">Hapus</button></td>
        `;
        tr.querySelector('button').addEventListener('click', () => {
            itemList.splice(idx, 1);
            renderTable();
        });
        tbody.insertBefore(tr, emptyRow);
    });

    emptyRow.style.display = itemList.length ? 'none' : '';
    document.getElementById('grand-total').textContent = 'Rp ' + formatNumber(grand);

    // Rebuild hidden inputs
    syncHiddenInputs();
}

function syncHiddenInputs() {
    const container = document.getElementById('hidden-inputs');
    container.innerHTML = '';
    itemList.forEach((item, idx) => {
        container.innerHTML += `
            <input type="hidden" name="items[${idx}][product_id]" value="${item.productId}">
            <input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">
            <input type="hidden" name="items[${idx}][unit_cost]" value="${item.price}">
        `;
    });
}

// Guard: pastikan ada item sebelum submit
document.getElementById('purchase-form').addEventListener('submit', function (e) {
    if (itemList.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal satu barang ke daftar sebelum menyimpan.');
    }
});

function formatNumber(n) {
    return Math.round(n).toLocaleString('id-ID');
}
</script>
@endsection
