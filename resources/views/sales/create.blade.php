@extends('layouts.app')

@section('title', 'Buat Penjualan')

@section('content')
<div class="page-head">
    <div>
        <h1>Buat Penjualan</h1>
        <p class="page-desc">Harga diambil dari database, bukan dari browser. Total dihitung ulang di server.</p>
    </div>
</div>

<form method="POST" action="{{ route('sales.store') }}" id="sale-form">
    @csrf

    <div class="form-grid">
        <div class="field">
            <label>Nomor transaksi</label>
            <input type="text" value="{{ $nextNumber }}" readonly>
        </div>
        <div class="field">
            <label for="sold_at">Tanggal penjualan</label>
            <input type="datetime-local" id="sold_at" name="sold_at" value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}">
            @error('sold_at')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="customer_id">Pelanggan</label>
            <select id="customer_id" name="customer_id">
                <option value="">Tanpa pelanggan (walk-in)</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="section">
        <h2>Tambah item</h2>
        <div class="form-grid">
            <div class="field">
                <label for="pick-product">Produk</label>
                <select id="pick-product">
                    <option value="">Pilih produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->selling_price }}"
                                data-stock="{{ $product->stock }}"
                                data-name="{{ $product->name }}">
                            {{ $product->sku }} {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Stok tersedia</label>
                <input type="text" id="pick-stock" readonly value="-">
            </div>
            <div class="field">
                <label>Harga jual</label>
                <input type="text" id="pick-price" readonly value="-">
            </div>
            <div class="field">
                <label for="pick-qty">Jumlah</label>
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
        <h2>Daftar item</h2>
        <div class="table-wrap">
            <table id="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="num">Stok tersedia</th>
                        <th class="num">Jumlah</th>
                        <th class="num">Harga jual</th>
                        <th class="num">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr id="empty-row"><td colspan="6" class="empty-cell">Belum ada produk ditambahkan.</td></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="num"><strong>Total (preview, dihitung ulang server)</strong></td>
                        <td class="num"><strong id="grand-total">Rp 0</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Hidden inputs diisi JS: hanya product_id dan quantity.
         Harga tidak pernah dikirim dari browser. --}}
    <div id="hidden-inputs"></div>

    <div class="form-actions">
        <button type="submit" class="btn" id="btn-submit">Simpan Penjualan</button>
        <a href="{{ route('sales.index') }}">Batal</a>
    </div>
</form>

<script>
const products = @json($productsJson);
let itemList = []; // [{productId, name, qty, price, stock}]

// Tampilkan stok dan harga dari database saat produk dipilih.
document.getElementById('pick-product').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const price = opt.dataset.price ?? '';
    const stock = opt.dataset.stock ?? '';
    document.getElementById('pick-price').value = price ? 'Rp ' + formatNumber(price) : '-';
    document.getElementById('pick-stock').value = stock !== '' ? stock : '-';
    document.getElementById('pick-qty').max = stock !== '' ? stock : '';
});

document.getElementById('btn-add-item').addEventListener('click', function () {
    const sel = document.getElementById('pick-product');
    const productId = parseInt(sel.value);
    const qty = parseInt(document.getElementById('pick-qty').value);

    if (!productId) { alert('Pilih produk terlebih dahulu.'); return; }
    if (!qty || qty < 1) { alert('Jumlah harus minimal 1.'); return; }

    const prod = products.find(p => p.id === productId);
    if (!prod) { alert('Produk tidak ditemukan.'); return; }

    // Peringatan client-side; keputusan akhir tetap di server.
    if (qty > prod.stock) {
        alert('Jumlah melebihi stok tersedia (' + prod.stock + '). Server akan menolak transaksi ini.');
        return;
    }

    // Satu produk hanya boleh sekali dalam satu transaksi (aturan distinct).
    const existing = itemList.find(i => i.productId === productId);
    if (existing) {
        alert('Produk ini sudah ada di daftar. Hapus dulu lalu tambahkan kembali.');
        return;
    }

    itemList.push({ productId, name: prod.name, qty, price: prod.price, stock: prod.stock });
    renderTable();

    sel.value = '';
    document.getElementById('pick-qty').value = 1;
    document.getElementById('pick-price').value = '-';
    document.getElementById('pick-stock').value = '-';
    document.getElementById('pick-qty').max = '';
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
            <td class="num">${item.stock}</td>
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
        `;
    });
}

document.getElementById('sale-form').addEventListener('submit', function (e) {
    if (itemList.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal satu produk ke daftar sebelum menyimpan.');
    }
});

function formatNumber(n) {
    return Math.round(n).toLocaleString('id-ID');
}
</script>
@endsection
