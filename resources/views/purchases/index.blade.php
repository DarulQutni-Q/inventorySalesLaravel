@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
<div class="page-head">
    <div>
        <h1>Barang Masuk</h1>
        <p class="page-desc">Pembelian dari supplier. Stok bertambah saat transaksi disimpan.</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="btn">Buat Pembelian</a>
</div>

<form method="GET" action="{{ route('purchases.index') }}" class="filter-bar">
    <div class="field">
        <label for="dari">Dari tanggal</label>
        <input type="date" id="dari" name="dari" value="{{ $filter['dari'] ?? '' }}">
    </div>
    <div class="field">
        <label for="sampai">Sampai tanggal</label>
        <input type="date" id="sampai" name="sampai" value="{{ $filter['sampai'] ?? '' }}">
    </div>
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            <option value="completed" @selected(($filter['status'] ?? '') === 'completed')>Selesai</option>
            <option value="cancelled" @selected(($filter['status'] ?? '') === 'cancelled')>Batal</option>
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn">Cari</button>
        <a href="{{ route('purchases.index') }}">Reset</a>
    </div>
</form>
@error('sampai')<div class="error-text" style="margin-bottom: 12px;">{{ $message }}</div>@enderror

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th class="num">Jumlah item</th>
                <th class="num">Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td><a href="{{ route('purchases.show', $purchase) }}" class="mono">{{ $purchase->purchase_number }}</a></td>
                    <td>{{ $purchase->purchased_at->format('d/m/Y') }}</td>
                    <td>{{ $purchase->supplier?->name }}</td>
                    <td class="num">{{ $purchase->items_count }}</td>
                    <td class="num">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                    <td>{{ $purchase->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('purchases.show', $purchase) }}">Detail</a>
                            @if (auth()->user()->isAdmin() && $purchase->status === 'completed')
                                <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikurangi kembali.')">
                                    @csrf
                                    <button type="submit" class="link-btn">Batalkan</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $purchases->links() }}
@endsection
