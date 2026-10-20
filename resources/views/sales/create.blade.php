@extends('layouts.app')

@section('title', 'Buat Penjualan')

@section('content')
<div class="page-head">
    <h1>Buat Penjualan</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('sales.store') }}" id="sale-form">
        @csrf

        {{-- Header info: No Faktur dan Tanggal --}}
        <div class="form-row">
            <div>
                <label>No Faktur</label>
                <input type="text" value="{{ $nextNumber }}" readonly style="background:#f5f5f5;">
            </div>
            <div>
                <label for="sold_at">Tanggal Penjualan</label>
                <input type="datetime-local" id="sold_at" name="sold_at"
                       value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}">
                @error('sold_at')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Input tambah item --}}
        <div class="form-row" style="margin-top: 20px; align-items: flex-end;">
            <div>
                <label for="pick-product">Pilih Produk</label>
                <select id="pick-product">
                    <option value="">-- pilih produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->selling_price }}"
                                data-stock="{{ $product->stock }}"
                                data-name="{{ $product->name }}">
                            {{ $product->sku }} – {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Stok Tersedia</label>
                <input type="text" id="pick-stock" readonly value="-" style="background:#f5f5f5; width:100px;">
            </div>
            <div>
                <label>Harga Jual</label>
                <input type="text" id="pick-price" readonly value="-" style="background:#f5f5f5;">
            </div>
            <div>
                <label for="pick-qty">Jumlah</label>
                <input type="number" id="pick-qty" min="1" value="1" style="width:100px;">
            </div>
            <div>
                <button type="button" id="btn-add-item" class="btn btn-secondary">+ Tambah ke Daftar</button>
            </div>
        </div>

        {{-- Error items global --}}
        @error('items')<div class="error-text" style="margin-top: 8px;">{{ $message }}</div>@enderror

        {{-- Tabel daftar item yang akan disimpan --}}
        <h2 style="margin-top: 24px;">Daftar Item Penjualan</h2>
        <div class="table-wrap">
            <table id="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="width: 90px;">Stok Tersedia</th>
                        <th style="width: 80px;">Qty</th>
                        <th style="width: 160px;">Harga Jual</th>
                        <th style="width: 160px;">Subtotal</th>
                        <th style="width: 70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr id="empty-row"><td colspan="6" class="empty">Belum ada produk ditambahkan.</td></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right; font-weight:600;">Total:</td>
                        <td id="grand-total" style="font-weight:600;">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Hidden inputs untuk item list — diisi oleh JS sebelum submit --}}
        <div id="hidden-inputs"></div>

        <div class="form-actions">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" id="btn-submit">Simpan Penjualan</button>
        </div>
    </form>
</div>

<script>
const products = @json($productsJson);
// [{productId, name, qty, price, stock}]
let itemList = [];

// Auto-fill stok dan harga saat produk dipilih
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

    // Cari data produk dari array server
    const prod = products.find(p => p.id === productId);
    if (!prod) { alert('Produk tidak ditemukan.'); return; }

    // Peringatan client-side jika qty melebihi stok (server tetap validasi)
    if (qty > prod.stock) {
        alert('Perhatian: Qty (' + qty + ') melebihi stok tersedia (' + prod.stock + '). Server akan menolak transaksi ini.');
        return;
    }

    // Cek duplikat — tolak penambahan produk yang sama (distinct rule di SaleRequest)
    const existing = itemList.find(i => i.productId === productId);
    if (existing) {
        alert('Produk ini sudah ada di daftar. Hapus dulu lalu tambahkan kembali dengan qty yang diinginkan.');
        return;
    }

    itemList.push({ productId, name: prod.name, qty, price: prod.price, stock: prod.stock });
    renderTable();

    // Reset pilihan
    sel.value = '';
    document.getElementById('pick-qty').value = 1;
    document.getElementById('pick-price').value = '-';
    document.getElementById('pick-stock').value = '-';
    document.getElementById('pick-qty').max = '';
});

function renderTable() {
    const tbody = document.getElementById('items-body');
    const emptyRow = document.getElementById('empty-row');

    // Hapus semua baris item sebelumnya
    Array.from(tbody.querySelectorAll('tr.item-row')).forEach(r => r.remove());

    let grand = 0;
    itemList.forEach((item, idx) => {
        const subtotal = item.qty * item.price;
        grand += subtotal;
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>${item.name}</td>
            <td>${item.stock}</td>
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
        `;
    });
}

// Guard: pastikan ada item sebelum submit
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
