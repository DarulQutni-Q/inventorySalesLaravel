@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
<div class="page-head">
    <h1>Barang Masuk</h1>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">+ Tambah Barang Masuk</a>
</div>

<div class="card">
    <form method="GET" action="{{ route('purchases.index') }}" class="filter-bar">
        <div>
            <label for="dari">Dari Tanggal</label>
            <input type="date" id="dari" name="dari" value="{{ $filter['dari'] ?? '' }}">
        </div>
        <div>
            <label for="sampai">Sampai Tanggal</label>
            <input type="date" id="sampai" name="sampai" value="{{ $filter['sampai'] ?? '' }}">
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua</option>
                <option value="completed" @selected(($filter['status'] ?? '') === 'completed')>Selesai</option>
                <option value="cancelled" @selected(($filter['status'] ?? '') === 'cancelled')>Dibatalkan</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    @error('sampai')<div class="error-text" style="margin-bottom: 12px;">{{ $message }}</div>@enderror

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NO FAKTUR</th>
                    <th>TANGGAL</th>
                    <th>SUPPLIER</th>
                    <th>TOTAL ITEM</th>
                    <th>TOTAL HARGA</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $i => $purchase)
                    <tr>
                        <td>{{ $purchases->firstItem() + $i }}</td>
                        <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->purchase_number }}</a></td>
                        <td>{{ $purchase->purchased_at->format('d/m/Y') }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td>{{ $purchase->items_count }}</td>
                        <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('purchases.show', $purchase) }}" class="btn-action btn-action-view">Detail</a>
                                @if (auth()->user()->isAdmin() && $purchase->status === 'completed')
                                    <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                        @csrf
                                        <button type="submit" class="btn-action btn-action-delete">Batal</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Tidak ada transaksi barang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $purchases->links() }}</div>
</div>
@endsection
