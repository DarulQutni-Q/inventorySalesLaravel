@extends('layouts.app')

@section('title', 'Buat Pembelian')

@section('content')
<div class="page-head">
    <div>
        <h1>Buat Pembelian</h1>
        <p class="page-desc">Pilih supplier lalu tambah barang satu per satu. Total dihitung ulang di server.</p>
    </div>
</div>

<form method="POST" action="{{ route('purchases.store') }}" id="purchase-form">
    @csrf

    <div class="form-grid">
        <div class="field">
            <label>Nomor transaksi</label>
            <input type="text" value="{{ $nextNumber }}" readonly>
        </div>
        <div class="field">
            <label for="purchased_at">Tanggal masuk</label>
            <input type="datetime-local" id="purchased_at" name="purchased_at" value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}">
            @error('purchased_at')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id">
                <option value="">Pilih supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="section">
        <h2>Tambah barang</h2>
        <div class="form-grid">
            <div class="field">
                <label for="pick-product">Barang</label>
                <select id="pick-product">
                    <option value="">Pilih barang</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->purchase_price }}"
                                data-name="{{ $product->name }}">
                            {{ $product->sku }} {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="pick-price">Harga beli (Rp)</label>
                <input type="number" id="pick-price" min="0" step="1" placeholder="0">
            </div>
            <div class="field">
                <label for="pick-qty">Jumlah masuk</label>
                <input type="number" id="pick-qty" min="1" value="1">
            </div>
            <div class="field">
                <label>&nbsp;</label>
                <button type="button" id="btn-add-item" class="btn-outline">Tambah ke daftar</button>
            </div>
        </div>
    </div>

    @error('items')<div class="error-text" style="margin-top: 8px;">{{ $message }}</div>@enderror

    <div class="section">
        <h2>Daftar barang</h2>
        <div class="table-wrap">
            <table id="items-table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th class="num">Jumlah</th>
                        <th class="num">Harga beli</th>
                        <th class="num">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr id="empty-row"><td colspan="5" class="empty-cell">Belum ada barang ditambahkan.</td></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="num"><strong>Total (preview, dihitung ulang server)</strong></td>
                        <td class="num"><strong id="grand-total">Rp 0</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Hidden inputs diisi JS: hanya product_id, quantity, dan unit_cost.
         Harga jual tidak pernah dikirim dari browser. --}}
    <div id="hidden-inputs"></div>

    <div class="form-actions">
        <button type="submit" class="btn" id="btn-submit">Simpan Pembelian</button>
        <a href="{{ route('purchases.index') }}">Batal</a>
    </div>
</form>

<script>
const products = @json($productsJson);
let itemList = []; // [{productId, name, qty, price}]

// Isi harga otomatis saat produk dipilih.
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

    // Duplikat produk digabung jumlahnya.
    const existing = itemList.find(i => i.productId === productId);
    if (existing) {
        existing.qty += qty;
        renderTable();
        return;
    }

    const name = sel.options[sel.selectedIndex].dataset.name;
    itemList.push({ productId, name, qty, price });
    renderTable();

    sel.value = '';
    document.getElementById('pick-qty').value = 1;
    document.getElementById('pick-price').value = '';
});

function renderTable() {
    const tbody = document.getElementById('items-body');
    const emptyRow = document.getElementById('empty-row');

    Array.from(tbody.querySelectorAll('tr.item-row')).forEach(r => r.remove());

    let grand = 0;
    itemList.forEach((item, idx) => {
        const subtotal = item.qty * item.price;
        grand += subtotal;
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>${item.name}</td>
            <td class="num">${item.qty}</td>
            <td class="num">Rp ${formatNumber(item.price)}</td>
            <td class="num">Rp ${formatNumber(subtotal)}</td>
            <td><button type="button" class="link-btn" data-idx="${idx}">Hapus</button></td>
        `;
        tr.querySelector('button').addEventListener('click', () => {
            itemList.splice(idx, 1);
            renderTable();
        });
        tbody.insertBefore(tr, emptyRow);
    });

    emptyRow.style.display = itemList.length ? 'none' : '';
    document.getElementById('grand-total').textContent = 'Rp ' + formatNumber(grand);

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
