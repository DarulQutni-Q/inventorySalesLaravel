@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="page-head">
    <div>
        <h1>Penjualan</h1>
        <p class="page-desc">Stok berkurang saat transaksi disimpan, dicek dengan lockForUpdate.</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn">Buat Penjualan</a>
</div>

<form method="GET" action="{{ route('sales.index') }}" class="filter-bar">
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
        <a href="{{ route('sales.index') }}">Reset</a>
    </div>
</form>
@error('sampai')<div class="error-text" style="margin-bottom: 12px;">{{ $message }}</div>@enderror

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Oleh</th>
                <th class="num">Jumlah item</th>
                <th class="num">Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td><a href="{{ route('sales.show', $sale) }}" class="mono">{{ $sale->sale_number }}</a></td>
                    <td>{{ $sale->sold_at->format('d/m/Y') }}</td>
                    <td>{{ $sale->user?->name }}</td>
                    <td class="num">{{ $sale->items_count }}</td>
                    <td class="num">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>{{ $sale->status === 'completed' ? 'Selesai' : 'Batal' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('sales.show', $sale) }}">Detail</a>
                            @if (auth()->user()->isAdmin() && $sale->status === 'completed')
                                <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
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
{{ $sales->links() }}
@endsection
